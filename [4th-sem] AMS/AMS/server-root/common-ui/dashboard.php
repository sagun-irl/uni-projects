<?php
require __DIR__ . '/../includes/utils.php';

$role = $roleFromSession();
if (empty($role)) return;
?>

<nav class="bg-blue-900 flex justify-between items-center text-xl text-white px-5">
	<div>
		<?= ucfirst($role) ?> Panel
	</div>
	<div class="flex flex-wrap gap-1">
		<a href="/<?= $role ?>/" class="py-3 px-5 hover:bg-white hover:text-black">Home</a>
		<?php if ($role == 'admin'): ?>
			<details class="inline-block relative hover:text-black hover:bg-white">
				<summary class="py-3 px-5 hover:bg-white hover:text-black cursor-pointer">Add Account</summary>
				<div class="bg-blue-800 absolute p-1 flex">
					<a href="/admin/add-account.php?role=admin" class="py-1 px-4 text-white hover:bg-white hover:text-black">Admin</a>
					<a href="/admin/add-account.php?role=teacher" class="py-1 px-4 text-white hover:bg-white hover:text-black">Teacher</a>
					<a href="/admin/add-account.php?role=student" class="py-1 px-4 text-white hover:bg-white hover:text-black">Student</a>
				</div>
			</details>
			<details class="inline-block relative hover:text-black hover:bg-white">
				<summary class="py-3 px-5 hover:bg-white hover:text-black cursor-pointer">View Accounts</summary>
				<div class="bg-blue-800 absolute p-1 flex">
					<a href="/admin/view-account.php?role=admin" class="py-1 px-4 text-white hover:bg-white hover:text-black">Admin</a>
					<a href="/admin/view-account.php?role=teacher" class="py-1 px-4 text-white hover:bg-white hover:text-black">Teacher</a>
					<a href="/admin/view-account.php?role=student" class="py-1 px-4 text-white hover:bg-white hover:text-black">Student</a>
				</div>
			</details>
			<details class="inline-block relative hover:text-black hover:bg-white">
				<summary class="py-3 px-5 hover:bg-white hover:text-black cursor-pointer">View Attendance</summary>
				<div class="bg-blue-800 absolute p-1 flex text-nowrap">
					<a href="/admin/view-attendance.php?scope=student" class="py-1 px-4 text-white hover:bg-white hover:text-black">Per student</a>
					<a href="/admin/view-attendance.php?scope=class" class="py-1 px-4 text-white hover:bg-white hover:text-black">Per class</a>
				</div>
			</details>
		<?php elseif ($role == 'teacher'): ?>
			<a href="/teacher/student-list.php" class="py-3 px-4 hover:bg-white hover:text-black">Student List</a>
			<a href="/teacher/take-attendance.php" class="py-3 px-4 hover:bg-white hover:text-black">Take Attendance</a>
			<details class="inline-block relative hover:text-black hover:bg-white">
				<summary class="py-3 px-5 hover:bg-white hover:text-black cursor-pointer">View Attendance</summary>
				<div class="bg-blue-800 absolute p-1 flex text-nowrap">
					<a href="/teacher/student-report.php?scope=student" class="py-1 px-4 text-white hover:bg-white hover:text-black">Per student</a>
					<a href="/teacher/student-report.php?scope=class" class="py-1 px-4 text-white hover:bg-white hover:text-black">Per class</a>
				</div>
			</details>
		<?php elseif ($role == 'student'): ?>
			<a href="/student/student-list.php" class="py-3 px-4 hover:bg-white hover:text-black">Student list</a>
			<a href="/student/student-report.php" class="py-3 px-4 hover:bg-white hover:text-black">Attendance Report</a>
		<?php endif ?>
	</div>
	<div>
		<a href="/" class="text-xl h-full py-3 px-5 hover:bg-white hover:text-black">
			<span class="material-icons-round leading-[0.75]">logout</span>
			Logout
		</a>
	</div>
</nav>
