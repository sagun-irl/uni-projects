<?php
require_once '../../includes/connection.php';
require_once '../../includes/utils.php';

if (empty($_GET) && empty($_POST)) {
	header('Location: /admin/view-account.php');
	exit;
}

$role = $roleFromGET();
$id = $get('id');
$isStudent = ($role == 'student');
$idName = $isStudent ? 'Reg. No.' : 'ID';

$sql = "select * from {$role}_view" . $where([ 'id' => $id ]);
$result = mysqli_query($conn, $sql);
$user_found = mysqli_num_rows($result) != 0;

if (!$user_found) {
	$prompt("Error: No $role with $idName '$id' found", 'error');
}

$row = mysqli_fetch_assoc($result);
$uid = $row['uid'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$name = $row['Name'];
	$department = $row['Department'];
	$email = $row['Email'];
	$password = $row['Password'];

	if ($isStudent) {
		$batch = $row['Batch'];
	}

	require_once '../../includes/html_header.php';
	?>
	<div class="m-auto max-w-2xl py-5">
		<h2 class="text-center text-2xl mb-7">
			<?= $name ?> details
		</h2>
		<form method="POST" onsubmit="validateLogin(event)" data-autofill="true" data-allowcache="true" class="flex flex-col px-10 py-10 rounded-2xl shadow-2xl mx-16">
			<input type="hidden" name="role" value="<?= $role ?>">
			<label class="mb-3 flex flex-col text-lg">
				Username
				<input type="text" name="name" value="<?= $name ?>" placeholder="Enter <?= $role ?> name" class="border-2 border-gray-500 rounded-md p-1" required>
			</label>
			<label class="mb-3 flex flex-col text-lg">
				Department
				<select name="dept" class="border-2 border-gray-500 rounded-md p-1" required>
					<option hidden selected value="">Select</option>
					<option value="Architecture">Architecture</option>
					<option value="Civil">Civil</option>
					<option value="Electronics">Electronics</option>
					<option value="CSIT">CSIT</option>
					<option value="BCA">BCA</option>
				</select>
			</label>
			<label class="mb-3 flex flex-col text-lg">
				Email
				<input type="email" name="email" value="<?= $email ?>" placeholder="e.g. abc@gmail.com" class="border-2 border-gray-500 rounded-md p-1" required>
			</label>
			<label class="mb-3 flex flex-col text-lg relative">
				Password
				<input type="password" name="password" value="<?= $password ?>" placeholder="Enter password" class="border-2 border-gray-500 rounded-md p-1" required>
				<button type="button" class="absolute bottom-1 right-1 material-icons-round text-xl" onclick="togglePassword(event)">visibility</button>
			</label>

			<?php if ($isStudent): ?>
				<label class="mb-3 flex flex-col text-lg">
					Registration No.
					<input type="number" name="regNo" value="<?= $id ?>" placeholder="Enter reg. no." class="border-2 border-gray-500 rounded-md p-1" required>
				</label>
				<label class="mb-3 flex flex-col text-lg">
					Batch
					<input type="number" name="batch" value="<?= $batch ?>" min="2000" max="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" class="border-2 border-gray-500 rounded-md p-1" required>
				</label>
			<?php endif ?>

			<div class="flex justify-center mt-5">
				<button type="submit" class="text-lg text-white bg-gray-800 border-2 px-2 py-1 border-2 rounded-md border-black">
					Update Account
				</button>
			</div>
		</form>
	</div>
	<?php
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$name = $post('name');
	$department = $post('dept');
	$email = $post('email');
	$password = $post('password');

	$regNo = $post('regNo');
	$batch = $post('batch');

	$updateUser = "update user
		set Name = '$name', Department = '$department', Email = '$email', Password = '$password'
		where uid = $uid";

	if ($isStudent) {
		$updateStudent = "update student
			set RegistrationNumber = $regNo, Batch = $batch
			where uid = $uid";
	} else {
		$updateStudent = 'select 1';
	}

	try {
		mysqli_query($conn, $updateStudent);
		mysqli_query($conn, $updateUser);
	} catch(Exception $err) {
		if ($err->getCode() == 1062) { // duplicate primary key error
			$prompt("Error editing account: A student account with Registration No. <b>$regNo</b> already exists!", 'error');
		}

		$prompt('Error editing account: ' . $err->getMessage(), 'error');
	}

	$mailBody = "
Your account for AMS (http://ams.test/) has been updated.
Below are the new details for logging in to your account:
- Username: $name
- Password: $password
- Role: $role
";

	if ($isStudent) {
		$mailBody = $mailBody . "- Registration No.: $regNo";
	}

	$mailOpts = [
		'to' => $email,
		'subject' => 'AMS Account Updated',
		'body' => $mailBody
	];

	$mailtoHref = $mailLink('mailto', $mailOpts);
	$gmailHref = $mailLink('gmail', $mailOpts);

	$message = "Account details for <b>$name</b> successfully updated!" . '
		<div class="mt-3">
			<a class="bg-blue-500 px-3 py-1 rounded-md text-lg text-white mr-2" href="' . $mailtoHref . '">
				Send email
			</a>
			<a class="bg-blue-500 px-3 py-1 rounded-md text-lg text-white mr-2" href="' . $gmailHref . '" target="_blank">
				Open in browser
			</a>
		</div>';
	$prompt($message, 'success', 'history.go(-2)');
}

mysqli_close($conn);
require_once '../../includes/html_footer.php';
