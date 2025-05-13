<?php
if (session_status() != PHP_SESSION_ACTIVE) {
	session_start();
}

if ($newSession ?? false) {
	session_unset();
} else {
	if (!isset($_SESSION['role'])) {
		header('Location: /');
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AMS | Attendance Mangement System</title>
	<link rel="icon" href="/images/logo.png">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round">
	<script src="https://cdn.tailwindcss.com/3.4.3"></script>
	<script src="/includes/utils.js" defer></script>

	<style>
	.material-icons-round {
		vertical-align: middle;
		user-select: none;
	}
	</style>
</head>
<body style="font-family: 'Noto Sans', sans-serif;">
	<div class="sticky top-0">
		<h1 class="text-3xl text-center font-medium py-5 bg-blue-500">
			Attendance Management System
		</h1>
		<?php require_once __DIR__ . '/../common-ui/dashboard.php' ?>
	</div>
	<?php
		require_once __DIR__ . '/utils.php';
		if ($roleFromPath()) {
			if ($roleFromSession() != $roleFromPath()) {
				require_once __DIR__ . '/403-forbidden.php';
				exit;
			}
		}
	?>
