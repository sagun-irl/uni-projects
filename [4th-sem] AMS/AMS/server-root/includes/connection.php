<?php

require_once __DIR__ . '/utils.php';

$server = 'localhost';
$user = 'root';
$password = '';
$database = 'AMS';

try {
	$conn = mysqli_connect($server, $user, $password, $database);
} catch(Exception) {
	$prompt(mysqli_connect_error());
}
