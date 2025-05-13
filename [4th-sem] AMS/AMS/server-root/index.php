<?php
$newSession = true;
require_once './includes/html_header.php';
?>

<div class="mx-auto w-full max-w-sm px-4">
	<h2 class="text-3xl text-center mt-10">Login</h2>
	<form action="/login.php" method="POST" class="flex flex-col mt-5 px-10 py-7 rounded-2xl shadow-2xl w-full" data-allowcache="true">
		<label class="mb-7">
			<span class="text-xl block">Username</span>
			<input type="text" placeholder="Enter your full name" name="name" class="w-full border-b-2 border-blue-500 mt-2 py-1 pl-1" required>
		</label>
		<label class="mb-7 relative">
			<span class="text-xl block">Password</span>
			<input type="password" placeholder="Enter your password" name="password" class="w-full border-b-2 border-blue-500 mt-2 py-1 pl-1" required>
			<button type="button" class="absolute bottom-1 right-1 material-icons-round text-xl" onclick="togglePassword(event)">visibility</button>
		</label>
		<label class="mb-7">
			<span class="text-xl block">Role</span>
			<select name="role" class="w-full border-b-2 border-blue-500 mt-2 py-1 pl-1" required>
				<option hidden selected value="">Enter your role</option>
				<option value="admin">Admin</option>
				<option value="teacher">Teacher</option>
				<option value="student">Student</option>
			</select>
		</label>
		<div class="flex justify-center">
			<button type="submit" class="text-xl text-white bg-gray-800 py-1 border-2 rounded-md border-black w-full">Login</button>
		</div>
	</form>
</div>

<?php
require_once './includes/html_footer.php';
?>
