<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Dashboard");



$pageId = 100;
$subPageId = 100;
include("app/assets.php");
$mode = isset($_GET['mode']) ? test_inputs($_GET['mode']) : 'today';
$startDate = isset($_GET['start_date']) ? test_inputs($_GET['start_date']) : date('Y-m-d');
$endDate = isset($_GET['end_date']) ? test_inputs($_GET['end_date']) : date('Y-m-d');

if ($mode == 'today') {
	$startDate = date('Y-m-d 00:00:00');
	$endDate = date('Y-m-d 23:59:59');
} else if ($mode == 'this_week') {

	$monday = strtotime('next Monday -1 week');
	$monday = date('w', $monday) == date('w') ? strtotime(date("Y-m-d", $monday) . " +7 days") : $monday;
	$sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
	$startDate = date("Y-m-d 00:00:00", $monday);
	$endDate = date("Y-m-d 23:59:59", $sunday);
} else if ($mode == 'this_month') {
	$totalDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	$startDate = date('Y-m-1 00:00:00');
	$endDate = date("Y-m-$totalDays  23:59:59");
} else if ($mode == 'custom') {

	$startDate = isset($_GET['start_date']) ? test_inputs($_GET['start_date']) : date('Y-m-d');
	$endDate = isset($_GET['end_date']) ? test_inputs($_GET['end_date']) : date('Y-m-d');


}

?>


<?php
include('index_nav.php');
?>

<?php
$averageAge = 0;
$genderDiversity = 0;
$totalEmps = 0;
$totalDone = 0;
$totalPending = 0;


$qu_QS_sel = "SELECT COUNT(`employee_id`) FROM  `employees_list` WHERE ( `is_hidden` = 0 )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalEmps = (int) $QS_DATA[0];
}

$qu_QS_sel = "SELECT AVG(TIMESTAMPDIFF(YEAR, `employee_dob`, CURDATE())) AS avg_age FROM  `employees_list` WHERE ( `is_hidden` = 0 )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$averageAge = (int) $QS_DATA[0];
}



$qu_QS_sel = "SELECT 
					`gender_id`,
					COUNT(*) AS gender_count,
					ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM `employees_list`), 2) AS percentage
				FROM `employees_list`
				GROUP BY `gender_id`;";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$genderDiversity = (int) $QS_DATA[0];
	$genderDiversity = $genderDiversity . '-' . $QS_DATA[1];
}



?>



<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("Performance", ""); ?>
	</div>
	<div class="kpiBoxes">

		<div class="boxKpi">
			<div class="dataName"><?= lang("Total_Employees", "AAR"); ?></div>
			<div class="dataValue"><?= $totalEmps; ?></div>
		</div>
		<div class="boxKpi">
			<div class="dataName"><?= lang("Average_Age", "AAR"); ?></div>
			<div class="dataValue"><?= $averageAge; ?> years</div>
		</div>
		<div class="boxKpi">
			<div class="dataName"><?= lang("Overhead_Ratio", "AAR"); ?></div>
			<div class="dataValue"><?= $genderDiversity; ?></div>
		</div>
		<div class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Diversity", "AAR"); ?></div>
			<div class="dataValue"><?= $genderDiversity; ?></div>
		</div>
	</div>
</div>




<div class="row">
	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Employees Distribution by Department</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="empByDept" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Employees Distribution by Gender</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="empBygender" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

</div>


<div class="row">
	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Employees Distribution by Certifications</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="certByEmp" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">

	</div>

</div>



<?php
include("app/footer.php");
?>

<script src="<?= $POINTER; ?>../public/assets/js/chart.umd.min.js"></script>

<script>
	//assetsByDept 
	function makeCertByEmps() {
		const ctx = document.getElementById('certByEmp');

		const myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT `certificate_id`, `certificate_name` FROM  `hr_certificates`";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$certificate_id = (int) $employees_list_departments_REC['certificate_id'];
							$certificate_name = $employees_list_departments_REC['certificate_name'];

							$deptCount = 0;


							$qu_support_tickets_list_sel = "SELECT COUNT(`certificate_id`) FROM  `employees_list`  WHERE ( (`certificate_id` = $certificate_id) AND ( `is_hidden` = 0 ) )";
							$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
							if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
								$support_tickets_list_DATA = mysqli_fetch_array($qu_support_tickets_list_EXE);
								$deptCount = (int) $support_tickets_list_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $certificate_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																										'<?= $certificate_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang(""); ?>',
					data: [
						<?php
						for ($i = 1; $i <= count($totsArrS); $i++) {

							$thsA = $totsArrS[$i];
							$deptCount = $totsArrC[$i];

							?>
																																																										<?= $deptCount; ?>,
							<?php

						}

						?>


					],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});


		ctx.onclick = (evt) => {
			const activePoints = myChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

			if (activePoints.length > 0) {
				// Get the first clicked element (e.g., a bar, a point, a pie slice)
				const firstPoint = activePoints[0];

				// Access information about the clicked element
				const label = myChart.data.labels[firstPoint.index];
				const value = myChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];

				// Perform your desired action
				console.log(`Clicked on: ${label}, Value: ${value}`);
			}
		};

	}
	//assetsByDept
	function makeDoughnut() {
		const ctx = document.getElementById('empByDept');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments`";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$department_id = (int) $employees_list_departments_REC['department_id'];
							$department_name = $employees_list_departments_REC['department_name'];

							$deptCount = 0;


							$qu_support_tickets_list_sel = "SELECT COUNT(`department_id`) FROM  `employees_list` WHERE ( (`department_id` = $department_id) AND ( `is_hidden` = 0 ) )";
							$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
							if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
								$support_tickets_list_DATA = mysqli_fetch_array($qu_support_tickets_list_EXE);
								$deptCount = (int) $support_tickets_list_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $department_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																																											'<?= $department_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang(""); ?>',
					data: [
						<?php
						for ($i = 1; $i <= count($totsArrS); $i++) {

							$thsA = $totsArrS[$i];
							$deptCount = $totsArrC[$i];

							?>
																																																										<?= $deptCount; ?>,
							<?php

						}

						?>


					],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});


		ctx.onclick = (evt) => {
			const activePoints = myChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

			if (activePoints.length > 0) {
				// Get the first clicked element (e.g., a bar, a point, a pie slice)
				const firstPoint = activePoints[0];

				// Access information about the clicked element
				const label = myChart.data.labels[firstPoint.index];
				const value = myChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];

				// Perform your desired action
				console.log(`Clicked on: ${label}, Value: ${value}`);
			}
		};

	}

	function makeAssetsByDept() {
		const ctx = document.getElementById('empBygender');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT `item_id`, `item_name` FROM  `sys_lists` WHERE (`item_category` = 'gender')";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$item_id = (int) $employees_list_departments_REC['item_id'];
							$item_name = $employees_list_departments_REC['item_name'];

							$deptCount = 0;


							$qu__sel = "SELECT COUNT(`employee_id`) FROM  `employees_list` WHERE ( (`gender_id` = $item_id) AND ( `is_hidden` = 0 ) )";
							$qu__EXE = mysqli_query($KONN, $qu__sel);
							if (mysqli_num_rows($qu__EXE)) {
								$_DATA = mysqli_fetch_array($qu__EXE);
								$deptCount = (int) $_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $item_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																													'<?= $item_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang(""); ?>',
					data: [
						<?php
						for ($i = 1; $i <= count($totsArrS); $i++) {

							$thsA = $totsArrS[$i];
							$deptCount = $totsArrC[$i];

							?>
																																													<?= $deptCount; ?>,
							<?php

						}

						?>


					],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});


		ctx.onclick = (evt) => {
			const activePoints = myChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

			if (activePoints.length > 0) {
				// Get the first clicked element (e.g., a bar, a point, a pie slice)
				const firstPoint = activePoints[0];

				// Access information about the clicked element
				const label = myChart.data.labels[firstPoint.index];
				const value = myChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];

				// Perform your desired action
				console.log(`Clicked on: ${label}, Value: ${value}`);
			}
		};

	}



	function countNums() {
		$('.count').each(function () {
			var $this = $(this);
			jQuery({ Counter: 0 }).animate({ Counter: $this.attr('data-stop') }, {
				duration: 1500,
				easing: 'swing',
				step: function (now) {
					$this.text(Math.ceil(now));
				}
			});
		});

	}

	makeCertByEmps();
	makeDoughnut();
	makeAssetsByDept();
	makeAssetsByStt();
	makeAssetsByCat();
	countNums();
</script>

<script>
	<?php
	if ($mode == 'custom') {
		?>
		showdateForms(false);
		<?php

	}
	?>
</script>