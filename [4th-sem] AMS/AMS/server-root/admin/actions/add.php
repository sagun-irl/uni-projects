<?php
require_once '../../includes/connection.php';
require_once '../../includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header('Location: /admin/add-account.php');
	exit;
}

$role = $roleFromPOST();
$name = $post('name');
$department = $post('dept');
$email = $post('email');
$password = $post('password');

$regNo = $post('regNo');
$batch = $post('batch');

$insertUser = "insert into user (Name, Department, Email, Password, Role)
		values ('$name', '$department', '$email', '$password', '$role')";

if ($role == 'student') {
	$insertRole = "insert into $role (uid, RegistrationNumber, Batch)
		values (LAST_INSERT_ID(), $regNo, $batch)";
} else {
	$insertRole = "insert into $role (uid)
		values (LAST_INSERT_ID())";
}

try {
	mysqli_query($conn, $insertUser);
	mysqli_query($conn, $insertRole);
} catch(Exception $err) {
	if ($err->getCode() == 1062) { // duplicate primary key error
		$prompt("Error creating account: A student account with Registration No. <b>$regNo</b> already exists!", 'error');
	}

	$prompt('Error creating account: ' . $err->getMessage(), 'error');
}

$mailBody = "
Your account for AMS (http://ams.test/) has successfully been created.
Below are the details for logging in to your account:
- Username: $name
- Password: $password
- Role: $role
";

if ($role == 'student') {
	$mailBody = $mailBody . "- Registration No.: $regNo";
}

$mailOpts = [
	'to' => $email,
	'subject' => 'AMS Account Created',
	'body' => $mailBody
];

$mailtoHref = $mailLink('mailto', $mailOpts);
$gmailHref = $mailLink('gmail', $mailOpts);

$message = "Account for $role <b>$name</b> successfully created!" . '
	<div class="mt-3">
		<a class="bg-blue-500 px-3 py-1 rounded-md text-lg text-white mr-2" href="' . $mailtoHref . '">
			Send email
		</a>
		<a class="bg-blue-500 px-3 py-1 rounded-md text-lg text-white mr-2" href="' . $gmailHref . '" target="_blank">
			Open in browser
		</a>
	</div>';
$prompt($message, 'success');

mysqli_close($conn);
require_once '../../includes/html_footer.php';
