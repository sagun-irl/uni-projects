<?php

require_once __DIR__ . '/server-root/includes/utils.php';

$server = 'localhost';
$user = 'root';
$password = '';
$database = 'AMS';

try {
	$conn = mysqli_connect($server, $user, $password);
} catch(Exception) {
	$prompt(mysqli_connect_error());
}

$sql = "create database if not exists `$database`";
mysqli_query($conn, $sql);
mysqli_select_db($conn, $database);

$table_schema = [
	'user' => [
		'uid int(10) primary key auto_increment',
		'Name varchar(40) not null collate utf8mb4_bin',
		'Department varchar(20)',
		'Email varchar(50) not null',
		'Password varchar(20) not null collate utf8mb4_bin',
		'Role enum("admin", "teacher", "student") not null'
	],
	'admin' => [
		'uid int(10)',
		'ID int(4) primary key auto_increment',
		'foreign key (uid) references user(uid)
			on delete cascade on update cascade'
	],
	'teacher' => [
		'uid int(10)',
		'ID int(4) primary key auto_increment',
		'foreign key (uid) references user(uid)
			on delete cascade on update cascade'
	],
	'student' => [
		'uid int(10)',
		'RegistrationNumber int(10) primary key',
		'Batch int(4)',
		'foreign key (uid) references user(uid)
			on delete cascade on update cascade'
	],
	'attendance' => [
		'ID int(10) primary key auto_increment',
		'RegistrationNumber int(10)',
		'Status enum("Present", "Absent")',
		'Date date not null',
		'foreign key (RegistrationNumber) references student(RegistrationNumber)
			on delete cascade on update cascade'
	]
];

foreach ($table_schema as $table => $columns) {
	$columns_str = implode(', ', $columns);
	$sql = "create table if not exists $table ($columns_str)";
	mysqli_query($conn, $sql);
}

foreach (['admin', 'teacher', 'student'] as $role) {
	$role_cols = [$role . '.uid', 'ID'];
	$user_cols = ['Name', 'Department', 'Email', 'Password'];
	if ($role == 'student') {
		$role_cols[1] = 'RegistrationNumber as ID';
		$role_cols[] = 'RegistrationNumber';
		$role_cols[] = 'Batch';
	}

	$columns_str = implode(', ', array_merge($role_cols, $user_cols));
	$sql = "create view if not exists {$role}_view as
		select $columns_str from $role
		left join user on {$role}.uid = user.uid";
	mysqli_query($conn, $sql);
}

$sql = 'create view if not exists attendance_view as
	select attendance.RegistrationNumber, Name, Department, Batch, Status, Date
	from attendance left join student_view
	on attendance.RegistrationNumber = student_view.RegistrationNumber';
mysqli_query($conn, $sql);

$sql = 'replace into user (Name, Email, Password, Role) values ("admin", "admin@gmail.com", "admin", "admin")';
mysqli_query($conn, $sql);

$sql = 'replace into admin (uid) values (LAST_INSERT_ID())';
mysqli_query($conn, $sql);

echo "
Database initialization successful!
Default admin login details: (make sure to delete this account after setup)

	Name: 'admin'
	Password: 'admin'
	Role: 'admin'
";

mysqli_close($conn);
