<?php
require_once '../../includes/connection.php';
require_once '../../includes/utils.php';

$date = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$day = date('D');
	$department = $get('dept');
	$batch = $get('batch');

	$missingParams = !($batch && $department);

	if ($missingParams) {
		header('Location: /teacher/take-attendance.php');
		exit;
	}

	require_once '../../includes/html_header.php';

	$sql = "SELECT * from student_view where Batch = '$batch' AND Department = '$department'";
	$result = mysqli_query($conn, $sql);

	$num_rows = mysqli_num_rows($result);
	$noStudent = $num_rows == 0;
	if ($noStudent) {
		$prompt("No student found in $department, Batch $batch", 'error');
	}
	?>

	<h2 class="text-center text-3xl mt-10">Today's attendance</h2>
	<h3 class="text-center text-2xl mt-5">
		<?= "$department, Batch $batch" ?>
	</h3>
	<form method="POST" onsubmit="confirmUncheckedRadios(event)">
		<input type="hidden" name="dept" value="<?= $department ?>">
		<input type="hidden" name="batch" value="<?= $batch ?>">
		<table class="mx-auto w-2/3 shadow-2xl">
			<caption class="text-xl text-right mb-2">
				<?= "$date ($day)" ?>
			</caption>
			<thead>
				<tr class="text-xl text-white border-b-2 bg-gray-800">
					<th class="text-left py-2 pl-4">Reg. No.</th>
					<th class="text-left">Name</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>

				<?php
				while ($row = mysqli_fetch_assoc($result)) {
					$name = $row['Name'];
					$regNo = $row['RegistrationNumber'];

					$sql = "SELECT Status from attendance where Date = '$date' and RegistrationNumber = $regNo";
					$todaysRecord = mysqli_query($conn, $sql);
					$recordFound = mysqli_num_rows($todaysRecord) != 0;
					$status = '';

					if ($recordFound) {
						$record = mysqli_fetch_assoc($todaysRecord);
						$status = $record['Status'];
					}

					echo '
					<tr class="text-lg border-b-2 border-gray-300">
						<td class="pl-4">' . $regNo . '
							<input type="hidden" name="regNo[]" value="' . $regNo . '">
						</td>
						<td>' . $name . '</td>
						<td class="py-2 pr-4 text-center">
							<label>
								<input type="radio" name="status[' . $regNo . ']" value="Present" class="accent-green-600 align-[-0.1em]" ' . ($status == 'Present' ? 'checked' : '') . '>
								Present
							</label>
							<label>
								<input type="radio" name="status[' . $regNo . ']" value="Absent" class="accent-red-500 align-[-0.1em]" ' . ($status == 'Absent' ? 'checked' : '') . '>
								Absent
							</label>
						</td>
					</tr>';
				}

				mysqli_close($conn);
				?>

			</tbody>
		</table>
		<div class="mx-auto w-2/3 text-right">
			<?php if ($recordFound): ?>
				<button class="bg-gray-800 text-white text-center py-1 px-3 rounded-sm mr-2 my-2 hover:underline" type="reset">Restore last save</button>
			<?php endif ?>
			<button class="bg-gray-800 text-white text-center py-1 px-3 rounded-sm mr-2 my-2 hover:underline" type="reset" onclick="clearRadios(event)">Clear all</button>
			<button class="bg-gray-800 text-white text-center py-1 px-3 rounded-sm mr-2 my-2 hover:underline" type="submit">Submit</button>
		</div>
	</form>
	<?php
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$regNos = $_POST['regNo'];
	$department = $post('dept');
	$batch = $post('batch');

	foreach ($regNos as $i => $regNo) {
		$statusInput = $_POST['status'][$regNo] ?? null;
		$isMarked = $statusInput || false;
		$status = $isMarked ? "'{$statusInput}'" : 'null';

		$sql = "SELECT 1 from attendance where Date = '$date' and RegistrationNumber = $regNo";
		$findTodaysRecord = mysqli_query($conn, $sql);
		$recordFound = mysqli_num_rows($findTodaysRecord) != 0;

		if ($recordFound) {
			$sql = "UPDATE attendance set Status = $status where Date = '$date' and RegistrationNumber = $regNo";
		} else {
			$sql = "INSERT into attendance (RegistrationNumber, Status, Date) values ($regNo, $status, '$date')";
		}

		try {
			mysqli_query($conn, $sql);
		} catch(Exception $err) {
			$prompt('Error recording attendance: ' . $err->getMessage(), 'error');
		}
	}

	mysqli_close($conn);
	header("Location: /teacher/student-report.php?scope=class&dept={$department}&batch={$batch}&date={$date}");
}
?>

<?php
require_once '../../includes/html_footer.php';
?>
