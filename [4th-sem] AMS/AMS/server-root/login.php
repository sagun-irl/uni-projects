<?php
require_once './includes/connection.php';
require_once './includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	header('Location: /');
	exit;
}

$name = $post('name');
$password = $post('password');
$role = $roleFromPOST();

$sql = "SELECT 1 from user where
	Name = '$name' and
	Password = '$password' and
	Role = '$role'";

$result = mysqli_query($conn, $sql);
$accountFound = mysqli_num_rows($result) != 0;

session_start();

if (!$accountFound) {
	$_SESSION['role'] = '';
	$prompt('User not registered. Please visit the technical department to register.', 'error');
}

$_SESSION['role'] = $role;

header("Location: /$role/");

mysqli_close($conn);
