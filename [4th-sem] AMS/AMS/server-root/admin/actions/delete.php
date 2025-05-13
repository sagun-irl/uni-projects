<?php
require_once '../../includes/connection.php';
require_once '../../includes/utils.php';

if (empty($_GET) && empty($_POST)) {
	header('Location: /admin/view-account.php');
	exit;
}

$role = $roleFromGET();
$id = $get('id');
$idName = ($role == 'student') ? 'Reg. No.' : 'ID';

$sql = "select uid, Name from {$role}_view" . $where([ 'id' => $id ]);
$result = mysqli_query($conn, $sql);
$user_found = mysqli_num_rows($result) != 0;

if (!$user_found) {
	$prompt("Error: " . ucfirst($role) . " with $idName '$id' does not exist", 'error');
}

$row = mysqli_fetch_assoc($result);
$uid = $row['uid'];
$name = $row['Name'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$message = "Confirm deletion of $role account <b>$name</b> ($idName <b>#$id</b>) ?" . '
		<form method="POST" class="mt-3">
			<button type="submit" class="bg-red-500 px-3 py-[0.1rem] rounded-md text-lg text-white">
				Delete
			</button>
			<button type="button" onclick="history.back()" class="bg-blue-500 px-3 py-[0.1rem] rounded-md text-lg text-white">
				Cancel
			</button>
		</form>';
	$prompt($message);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sql = "delete from user where uid = $uid";
	mysqli_query($conn, $sql);

	$message = 'User deleted. Redirecting back…' . '
		<script>
			setTimeout(() => history.go(-2), 1000);
		</script>';
	$prompt($message, 'success');
}

mysqli_close($conn);
require_once '../../includes/html_footer.php';
