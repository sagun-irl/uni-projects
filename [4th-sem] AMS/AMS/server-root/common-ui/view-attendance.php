<?php
require_once '../includes/connection.php';
require_once '../includes/html_header.php';
require_once '../includes/utils.php';

$scope = $get('scope', 'student');
if (!in_array($scope, ['', 'student', 'class'])) {
	$prompt("Invalid scope: $scope");
}
?>

<h2 class="text-center text-3xl mt-10">Student attendance</h2>
<form class="px-40 py-5 flex gap-10 justify-center" data-autofill="true">
	<?php if ($get('scope')): ?>
		<input type="hidden" name="scope" value="<?= $scope ?>">
	<?php endif ?>

	<?php if ($scope == 'student'): ?>
		<label>
			<span class="text-xl block">Registration No.</span>
			<input class="border-2 border-black-600 rounded-md px-2 w-48" type="number" name="regNo" placeholder="Enter reg. no." required>
		</label>
		<fieldset class="flex gap-4 text-lg">
			<legend class="text-xl">Filter by</legend>
			<label>
				<input class="align-middle" type="radio" name="filterBy" value="day" checked> Day
			</label>
			<label>
				<input class="align-middle" type="radio" name="filterBy" value="month"> Month
			</label>
			<label>
				<input class="align-middle" type="radio" name="filterBy" value="year"> Year
			</label>
		</fieldset>
		<div class="filter-inputs">
			<label class="hidden">
				<span class="text-xl block">Day</span>
				<input class="border-2 border-black-600 rounded-md px-2 w-48" type="date" name="day" min="2000-01-01" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required disabled>
			</label>
			<label class="hidden">
				<span class="text-xl block">Month</span>
				<input class="border-2 border-black-600 rounded-md px-2 w-48" type="month" name="month" min="2000-01" max="<?= date('Y-m') ?>" value="<?= date('Y-m') ?>" required disabled>
			</label>
			<label class="hidden">
				<span class="text-xl block">Year</span>
				<input class="border-2 border-black-600 rounded-md px-2 w-48" type="number" name="year" min="2000" max="<?= date('Y') ?>" value="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" required disabled>
			</label>
		</div>
	<?php elseif ($scope == 'class'): ?>
		<label>
			<span class="text-xl block">Department</span>
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
			<span class="text-xl block">Batch</span>
			<input class="border-2 border-black-600 rounded-md px-2 w-48" type="number" name="batch" min="2000" max="<?= date('Y') ?>" placeholder="e.g. <?= date('Y') ?>" required>
		</label>
		<label>
			<span class="text-xl block">Date</span>
			<input class="border-2 border-black-600 rounded-md px-2 w-48" type="date" name="date" min="2000-01-01" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
		</label>
	<?php endif ?>

	<button type="submit" class="material-icons-round text-5xl">search</button>
</form>

<?php
$regNo = $get('regNo');
$department = $get('dept');
$batch = $get('batch');
$timePeriod = $get('date') ?: $get('day') ?: $get('month') ?: $get('year');

$runQuery = ($regNo || ($department && $batch)) && $timePeriod;

if ($runQuery) {
	$tokens = explode('-', $timePeriod);
	$year = $tokens[0];
	$month = $tokens[1] ?? '';
	$day = $tokens[2] ?? '';

	$sql = 'select * from attendance_view' . $where([
		'RegistrationNumber' => $regNo,
		'Department' => $department,
		'Batch' => $batch,
		'year(Date)' => $year,
		'month(Date)' => $month,
		'day(Date)' => $day
	]) . ' order by Date desc, RegistrationNumber';

	try {
		$result = mysqli_query($conn, $sql);
		$num_rows = mysqli_num_rows($result);
	} catch(Exception $err) {
		$prompt('Malformed query: ' . $err->getMessage(), 'error');
	}

	?>
	<table class="mx-auto w-2/3 shadow-2xl">
		<thead>
			<tr class="text-xl text-white border-b-2 bg-gray-800">
				<th class="text-left py-2 pl-4">Reg. No.</th>
				<th class="text-left">Name</th>
				<th>Status</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>

			<?php
			$num_present = 0;
			$num_absent = 0;

			while ($row = mysqli_fetch_assoc($result)) {
				$regNo = $row['RegistrationNumber'];
				$name = $row['Name'];
				$status = $row['Status'];
				$date = $row['Date'];

				if ($row['Status'] == 'Present') {
					$statusIcon = 'check_box';
					$statusColor = 'green';
					$num_present++;
				} else {
					$statusIcon = 'disabled_by_default';
					$statusColor = 'red';
					$num_absent++;
				}

				echo '
				<tr class="text-lg border-b-2 border-gray-300">
					<td class="py-2 pl-4">' . $regNo . '</td>
					<td>' . $name . '</td>
					<td class="text-center">
						<span aria-hidden="true" class="material-icons-round text-2xl text-' . $statusColor . '-500">' . $statusIcon . '</span>
						<span class="sr-only">' . $status . '</span>
					</td>
					<td class="text-center">' . $date . '</td>
				</tr>';
			}

			mysqli_close($conn);
			?>

		</tbody>
		<?php if ($num_rows): ?>
			<caption class="text-left">
				<dl>
					<dd>Total: <?= $num_rows ?></dd>
					<dd>Present: <?= $num_present ?></dd>
					<dd>Absent: <?= $num_absent ?></dd>
				</dl>
			</caption>
		<?php endif ?>
	</table>
<?php
}
?>

<?php
require_once '../includes/html_footer.php';
?>
