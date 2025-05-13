<?php
require_once '../includes/html_header.php';
?>

<h2 class="text-center text-3xl mt-10">Today's attendance</h2>
<form action="/teacher/actions/attendance.php" class="px-40 py-5 flex gap-10 justify-center">
	<label>
		<span class="text-xl">Department</span>
		<select name="dept" class="border-2 border-black-600 rounded-md w-48" required>
			<option hidden selected value="">Select</option>
			<option value="Architecture">Architecture</option>
			<option value="Civil">Civil</option>
			<option value="Electronics">Electronics</option>
			<option value="CSIT">CSIT</option>
			<option value="BCA">BCA</option>
		</select>
	</label>
	<label>
		<span class="text-xl">Batch</span>
		<input class="border-2 border-black-600 rounded-md w-48" type="number" name="batch" id="batch" min="2000" max="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" required>
	</label>
	<button type="submit" class="material-icons-round">search</button>
</form>

<?php
require_once '../includes/html_footer.php';
?>
