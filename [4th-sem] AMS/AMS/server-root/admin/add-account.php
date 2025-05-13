<?php
require_once '../includes/html_header.php';
$role = $roleFromGET('student');
?>

<div class="m-auto max-w-2xl py-5">
	<h2 class="text-center text-2xl mb-7">
		New <?= $role ?> details
	</h2>
	<form action="/admin/actions/add.php" method="POST" onsubmit="validateLogin(event)" data-allowcache="true" class="flex flex-col px-10 py-10 rounded-2xl shadow-2xl mx-16">
		<input type="hidden" name="role" value="<?= $role ?>">
		<label class="mb-3 flex flex-col text-lg">
			Username
			<input type="text" name="name" placeholder="Enter <?= $role ?> name" class="border-2 border-gray-500 rounded-md p-1" required>
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
			<input type="email" name="email" placeholder="e.g. abc@gmail.com" class="border-2 border-gray-500 rounded-md p-1" required>
		</label>
		<label class="mb-3 flex flex-col text-lg relative">
			Password
			<input type="password" name="password" placeholder="Enter password" class="border-2 border-gray-500 rounded-md p-1" required>
			<button type="button" class="absolute bottom-1 right-1 material-icons-round text-xl" onclick="togglePassword(event)">visibility</button>
		</label>

		<?php if ($role == 'student'): ?>
			<label class="mb-3 flex flex-col text-lg">
				Registration No.
				<input type="number" name="regNo" placeholder="Enter reg. no." class="border-2 border-gray-500 rounded-md p-1" required>
			</label>
			<label class="mb-3 flex flex-col text-lg">
				Batch
				<input type="number" name="batch" min="2000" max="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" class="border-2 border-gray-500 rounded-md p-1" required>
			</label>
		<?php endif ?>

		<div class="flex justify-center mt-5">
			<button type="submit" class="text-lg text-white bg-gray-800 border-2 px-2 py-1 border-2 rounded-md border-black">
				Add <?= ucfirst($role) ?>
			</button>
		</div>
	</form>
</div>

<?php
require_once '../includes/html_footer.php';
?>
