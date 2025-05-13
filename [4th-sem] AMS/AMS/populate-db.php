<?php

require_once './server-root/includes/connection.php';

$firstNames = ['Alice', 'Bob', 'Charlie', 'David', 'Eva', 'Frank', 'Grace', 'Hank', 'Ivy', 'Jack', 'Karen', 'Leo', 'Mona', 'Nathan', 'Olivia', 'Paul', 'Quinn', 'Rachel', 'Sam', 'Tina'];
$departments = ['CSIT', 'Architecture', 'BCA', 'Civil', 'Electronics'];

function generatePassword() {
	$upper = chr(rand(65, 90));
	$lower = chr(rand(97, 122));
	$number = chr(rand(48, 57));
	$remaining = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, rand(5, 8));
	return str_shuffle($upper . $lower . $number . $remaining);
}

// Admins
for ($i = 0; $i < 3; $i++) {
	$name = "Admin " . $firstNames[array_rand($firstNames)];
	$department = $departments[array_rand($departments)];
	$email = strtolower(str_replace(' ', '.', $name)) . "@admin.com";
	$password = generatePassword();

	$sql_user = "REPLACE INTO user (Name, Password, Department, Email, Role) VALUES ('$name', '$password', '$department', '$email', 'admin')";
	mysqli_query($conn, $sql_user);

	$sql_admin = "REPLACE INTO admin (uid) VALUES (LAST_INSERT_ID())";
	mysqli_query($conn, $sql_admin);
}

// Teachers
for ($i = 0; $i < 5; $i++) {
	$name = (rand(0, 1) ? "Prof. " : "Er. ") . $firstNames[array_rand($firstNames)];
	$department = $departments[array_rand($departments)];
	$email = strtolower(str_replace(' ', '', $name)) . "@university.com";
	$password = generatePassword();

	$sql_user = "REPLACE INTO user (Name, Password, Department, Email, Role) VALUES ('$name', '$password', '$department', '$email', 'teacher')";
	mysqli_query($conn, $sql_user);

	$sql_teacher = "REPLACE INTO teacher (uid) VALUES (LAST_INSERT_ID())";
	mysqli_query($conn, $sql_teacher);
}

// Students
$reg_start = rand(1, 9) * 1000;
for ($i = 0; $i < 50; $i++) {
	$reg = $reg_start + $i;
	$name = $firstNames[array_rand($firstNames)];
	$department = $departments[array_rand($departments)];
	$batch = date('Y') - rand(0, 4);
	$email = strtolower($name) . "$i@example.com";
	$password = generatePassword();

	$sql_user = "REPLACE INTO user (Name, Password, Department, Email, Role) VALUES ('$name', '$password', '$department', '$email', 'student')";
	mysqli_query($conn, $sql_user);

	$sql_student = "REPLACE INTO student (uid, RegistrationNumber, Batch) VALUES (LAST_INSERT_ID(), '$reg', '$batch')";
	mysqli_query($conn, $sql_student);
}

// Attendance
for ($i = 31; $i >= 1; $i--) {
	$date = date("Y-m-d", strtotime("-{$i} days"));
	for ($j = 0; $j < 20; $j++) {
		$reg = $reg_start + $j;
		$status = rand(0, 2) ? 'Present' : 'Absent';
		$sql = "REPLACE INTO attendance (Date, RegistrationNumber, Status) VALUES ('$date', '$reg', '$status')";
		mysqli_query($conn, $sql);
	}
}

echo "Successfully populated database with randomized data!";

mysqli_close($conn);
