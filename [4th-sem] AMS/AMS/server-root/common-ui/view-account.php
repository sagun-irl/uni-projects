<?php
require_once '../includes/connection.php';
require_once '../includes/html_header.php';
require_once '../includes/utils.php';

$hasAdminRights = ($roleFromSession() == 'admin');
$role = $roleFromGET('student');
$isStudent = ($role == 'student');

$sql = "SELECT * from {$role}_view" . $where([
	'Department' => $get('dept'),
	'Batch' => $isStudent ? $get('batch') : ''
]);

$hasFilters = $get('dept') && $get('batch') || false;

$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
?>

<h2 class="text-center text-3xl mt-10">
	<?= $hasAdminRights ? ucfirst($role) . ' accounts' : 'Enrolled students' ?>
</h2>

<?php if ($isStudent): ?>
	<form class="px-40 py-5 flex gap-10 justify-center" method="GET" data-autofill="true">
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
			<input class="border-2 border-black-600 rounded-md px-2 w-48" type="number" name="batch" min="2000" max="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" required>
		</label>
		<button type="submit" class="material-icons-round">search</button>
	</form>
<?php endif ?>

<?php if ($hasFilters || !$isStudent): ?>
	<table class="mx-auto w-2/3 shadow-2xl">
		<caption class="text-left">
			<?= 'Displayed records: ' . $num_rows ?>
		</caption>
		<thead>
			<tr class="text-xl text-white border-b-2 bg-blue-900">
				<th class="text-left py-2 pl-4"><?= $isStudent ? 'Reg. No.' : ucfirst($role) . ' ID' ?></th>
				<th class="text-left">Name</th>
				<th class="text-left">Email</th>
				<?php if (!$hasFilters): ?>
					<th class="text-left">Department</th>
				<?php endif ?>
				<?php if ($hasAdminRights): ?>
					<th class="text-left">Password</th>
					<th class="text-center">Actions</th>
				<?php endif ?>
			</tr>
		</thead>
		<tbody>

			<?php
			while ($row = mysqli_fetch_assoc($result)) {
				$id = $row['ID'];
				$name = $row['Name'];
				$email = $row['Email'];
				$password = $row['Password'];
				$department = $row['Department'];

				if (!$hasFilters) {
					$departmentRow = '
					<td>' . $department . '</td>';
				}
				if ($hasAdminRights) {
					$adminControlsRow = '
					<td>
						<input type="password" value="' . $password . '" class="[field-sizing:content]" disabled>
					</td>
					<td class="py-4 pr-4 text-center">
						<a href="/admin/actions/edit.php?role=' . $role . '&id=' . $id . '&dept=' . $department . '" class="bg-green-600 text-white py-2 px-3 rounded-sm">Edit</a>
						<a href="/admin/actions/delete.php?role=' . $role . '&id=' . $id . '" class="bg-red-600 text-white py-2 px-3 rounded-sm">Delete</a>
					</td>';
				}

				echo '
				<tr class="text-lg border-b-2 border-gray-300">
					<td class="py-2 pl-4">' . $id . '</td>
					<td>' . $name . '</td>
					<td>' . $email . '</td>' .
					($departmentRow ?? '') .
					($adminControlsRow ?? '') . '
				</tr>';
			}
			?>

		</tbody>
	</table>
<?php endif ?>

<?php
mysqli_close($conn);
require_once '../includes/html_footer.php';
?>
