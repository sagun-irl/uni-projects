<?php

require_once './server-root/includes/connection.php';

$tables = [
	'attendance',
	'admin',
	'teacher',
	'student',
	'user'
];

foreach($tables as $table) {
	$sql = "delete from $table";
	mysqli_query($conn, $sql);
	$sql = "alter table $table AUTO_INCREMENT = 1";
	mysqli_query($conn, $sql);
}

echo "Successfully deleted all records from tables!";

mysqli_close($conn);
