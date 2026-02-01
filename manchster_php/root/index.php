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
$totalTickets = 0;
$totalOpen = 0;
$totalUnassigned = 0;
$totalProgress = 0;
$totalDone = 0;
$totalPending = 0;









$qu_QS_sel = "SELECT COUNT(`ticket_id`) FROM  `support_tickets_list` WHERE ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalTickets = (int) $QS_DATA[0];
}


$status_id = 1;
$qu_QS_sel = "SELECT COUNT(`ticket_id`) FROM  `support_tickets_list` WHERE ( (`status_id` = $status_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalOpen = (int) $QS_DATA[0];
}

$status_id = 1;
$qu_QS_sel = "SELECT COUNT(`ticket_id`) FROM  `support_tickets_list` WHERE ( (`assigned_to` = 0 ) AND (`status_id` = $status_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalUnassigned = (int) $QS_DATA[0];
}

$status_id = 2;
$qu_QS_sel = "SELECT COUNT(`ticket_id`) FROM  `support_tickets_list` WHERE ( (`status_id` = $status_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalProgress = (int) $QS_DATA[0];
}
$status_id = 3;
$qu_QS_sel = "SELECT COUNT(`ticket_id`) FROM  `support_tickets_list` WHERE ( (`status_id` = $status_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalDone = (int) $QS_DATA[0];
}

?>

<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("Tickets", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a href="<?= $DIR_tickets; ?>?stt=0" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Total_tickets", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalTickets; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("open_tickets", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalOpen; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Unassigned_tickets", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalUnassigned; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=2" class="boxKpi">
			<div class="dataName"><?= lang("in_progress", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalProgress; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=3" class="boxKpi">
			<div class="dataName"><?= lang("resolved", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalDone; ?>">0</div>
		</a>


	</div>
</div>


<div class="row">
	<div class="col-3" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Tickets by Department</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="doughnut" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<div class="col-3" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Tickets by Priority</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="ticketsByPriority" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<?php
	$totalOpenPerc = calculatePercentage($totalOpen, $totalTickets);
	$totalProgressPerc = calculatePercentage($totalProgress, $totalTickets);
	$totalDonePerc = calculatePercentage($totalDone, $totalTickets);
	?>
	<div class="col-3" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>All tickets by Status</h1>
			<!-- ----------------- -->
			<div class="viewGroup">
				<div class="dataName">Open</div>
				<div class="dataValue"><?= $totalOpen; ?></div>
				<div class="viewSlider">
					<div class="sliderValue" style="width:<?= $totalOpenPerc; ?>%;"></div>
				</div>
			</div>
			<!-- ----------------- -->
			<!-- ----------------- -->
			<div class="viewGroup">
				<div class="dataName">In Progress</div>
				<div class="dataValue"><?= $totalProgress; ?></div>
				<div class="viewSlider">
					<div class="sliderValue" style="width:<?= $totalProgressPerc; ?>%;"></div>
				</div>
			</div>
			<!-- ----------------- -->
			<!-- ----------------- -->
			<div class="viewGroup">
				<div class="dataName">Resolved</div>
				<div class="dataValue"><?= $totalDone; ?></div>
				<div class="viewSlider">
					<div class="sliderValue" style="width:<?= $totalDonePerc; ?>%;"></div>
				</div>
			</div>
			<!-- ----------------- -->
		</div>
	</div>
</div>

<div class="row">

	<div class="col-1" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Recent Tickets</h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("REF"); ?></div>
							<div class="th"><?= lang("Subject"); ?></div>
							<div class="th"><?= lang("Added_By"); ?></div>
							<div class="th"><?= lang("Added_Date"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="">
						<?php

						$qu_support_tickets_list_sel = "SELECT 
														`support_tickets_list`.`ticket_id`,  
														`support_tickets_list`.`ticket_ref`,  
														`support_tickets_list`.`ticket_subject`,  
														`support_tickets_list`.`ticket_added_date`,  
														`support_tickets_list`.`added_by`,  
														CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_employee`

														FROM `support_tickets_list` 
														INNER JOIN `employees_list` `a` ON `support_tickets_list`.`added_by` = `a`.`employee_id` 
														ORDER BY `support_tickets_list`.`ticket_id` ASC LIMIT 5";
						$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
						if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
							while ($support_tickets_list_REC = mysqli_fetch_assoc($qu_support_tickets_list_EXE)) {
								$thstcktID = (int) $support_tickets_list_REC['ticket_id'];
								$thsTcktRef = $support_tickets_list_REC['ticket_ref'];
								$thsTcktSubj = $support_tickets_list_REC['ticket_subject'];
								$thsTcktAddedDate = $support_tickets_list_REC['ticket_added_date'];
								$thsAddedBy = "" . $support_tickets_list_REC['added_employee'];
								?>

								<a href="<?= $POINTER; ?>tickets/view/?ticket_id=<?= $thstcktID; ?>" class="tr levelRow"
									id="ticket-<?= $thstcktID; ?>">
									<div class="td"><?= $thsTcktRef; ?></div>
									<div class="td"><?= $thsTcktSubj; ?></div>
									<div class="td"><?= $thsTcktAddedDate; ?></div>
									<div class="td"><?= $thsAddedBy; ?></div>
								</a>
								<?php
							}
						}

						?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>


<?php
$totalAssets = 0;
$totalAssetsInStock = 0;
$totalAssetsInUse = 0;
$totalAssetsRetired = 0;

$qu_QS_sel = "SELECT COUNT(`asset_id`) FROM  `z_assets_list` WHERE  ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalAssets = (int) $QS_DATA[0];
}



$status_id = 1;
$qu_QS_sel = "SELECT COUNT(`asset_id`) FROM  `z_assets_list` WHERE ( (`status_id` = $status_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalAssetsInStock = (int) $QS_DATA[0];
}

$status_id = 2;
$qu_QS_sel = "SELECT COUNT(`asset_id`) FROM  `z_assets_list` WHERE ( (`status_id` = $status_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalAssetsInUse = (int) $QS_DATA[0];
}


$status_id = 3;
$qu_QS_sel = "SELECT COUNT(`asset_id`) FROM  `z_assets_list` WHERE ( (`status_id` = $status_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
	$totalAssetsRetired = (int) $QS_DATA[0];
}


?>

<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("Assets", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a href="<?= $DIR_tickets; ?>?stt=0" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Total_Assets", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalAssets; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("In_use", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalAssetsInUse; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=2" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("In_Stock", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalAssetsInStock; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=3" class="boxKpi">
			<div class="dataName"><?= lang("Retired", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalAssetsRetired; ?>">0</div>
		</a>


	</div>
</div>



<div class="row">
	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Assets Distribution by Department</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="assetsByDept" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Assets Distribution by Status</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="assetsByStt" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

</div>


<div class="row">
	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1>Assets Distribution by Category</h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="assetsByCat" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>



</div>


<?php
include("app/footer.php");
?>

<script src="<?= $POINTER; ?>../public/assets/js/chart.umd.min.js"></script>

<script>
	//assetsByDept
	function makeDoughnut() {
		const ctx = document.getElementById('doughnut');

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


							$qu_support_tickets_list_sel = "SELECT COUNT(`department_id`) FROM  `support_tickets_list` WHERE ( (`department_id` = $department_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
							$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
							if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
								$support_tickets_list_DATA = mysqli_fetch_array($qu_support_tickets_list_EXE);
								$deptCount = (int) $support_tickets_list_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $department_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																																																																																																																																																																																																																																																																																																																																																								'<?= $department_name . ' ID=' . $department_id; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("SS"); ?>',
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
	//ticketByPrioity
	function makeTicketsByPriority() {
		const ctx = document.getElementById('ticketsByPriority');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_sys_list_priorities_sel = "SELECT `theme_id`, `priority_name` FROM  `sys_list_priorities`";
					$qu_sys_list_priorities_EXE = mysqli_query($KONN, $qu_sys_list_priorities_sel);
					if (mysqli_num_rows($qu_sys_list_priorities_EXE)) {
						while ($sys_list_priorities_REC = mysqli_fetch_assoc($qu_sys_list_priorities_EXE)) {
							$theme_id = (int) $sys_list_priorities_REC['theme_id'];
							$priority_name = $sys_list_priorities_REC['priority_name'];

							$deptCount = 0;


							$qu_support_tickets_list_sel = "SELECT COUNT(`theme_id`) FROM  `support_tickets_list` WHERE ( (`theme_id` = $theme_id ) AND ( (`ticket_added_date` >= '$startDate') AND ( `ticket_added_date` <= '$endDate' ) ) )";
							$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
							if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
								$support_tickets_list_DATA = mysqli_fetch_array($qu_support_tickets_list_EXE);
								$deptCount = (int) $support_tickets_list_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $theme_id;
								$totsArrC[$tots] = $deptCount;
								?>
																														'<?= $priority_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("SS"); ?>',
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


	}

	function makeAssetsByDept() {
		const ctx = document.getElementById('assetsByDept');

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


							$qu_SS_sel = "SELECT COUNT(`department_id`) FROM  `z_assets_list` WHERE ( (`department_id` = $department_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
							$qu_SS_EXE = mysqli_query($KONN, $qu_SS_sel);
							if (mysqli_num_rows($qu_SS_EXE)) {
								$SS_DATA = mysqli_fetch_array($qu_SS_EXE);
								$deptCount = (int) $SS_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $department_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																																																																																																																																																																																																																																																																																																																																																								'<?= $department_name . ' ID=' . $department_id; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("SS"); ?>',
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

	function makeAssetsByStt() {
		const ctx = document.getElementById('assetsByStt');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT `status_id`, `status_name` FROM  `z_assets_list_status`";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$status_id = (int) $employees_list_departments_REC['status_id'];
							$status_name = $employees_list_departments_REC['status_name'];

							$deptCount = 0;


							$qu_SS_sel = "SELECT COUNT(`status_id`) FROM  `z_assets_list` WHERE ( (`status_id` = $status_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
							$qu_SS_EXE = mysqli_query($KONN, $qu_SS_sel);
							if (mysqli_num_rows($qu_SS_EXE)) {
								$SS_DATA = mysqli_fetch_array($qu_SS_EXE);
								$deptCount = (int) $SS_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $status_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																																																																																																																																																																																																	'<?= $status_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("SS"); ?>',
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
	function makeAssetsByCat() {
		const ctx = document.getElementById('assetsByCat');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT `category_id`, `category_name` FROM  `z_assets_list_cats`";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$category_id = (int) $employees_list_departments_REC['category_id'];
							$category_name = $employees_list_departments_REC['category_name'];

							$deptCount = 0;


							$qu_SS_sel = "SELECT COUNT(`category_id`) FROM  `z_assets_list` WHERE ( (`category_id` = $category_id ) AND ( (`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) ) )";
							$qu_SS_EXE = mysqli_query($KONN, $qu_SS_sel);
							if (mysqli_num_rows($qu_SS_EXE)) {
								$SS_DATA = mysqli_fetch_array($qu_SS_EXE);
								$deptCount = (int) $SS_DATA[0];

							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $category_id;
								$totsArrC[$tots] = $deptCount;
								?>
																																																																																																																																																																																																																																																																																																	'<?= $category_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("SS"); ?>',
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

	makeDoughnut();
	makeAssetsByDept();
	makeAssetsByStt();
	makeAssetsByCat();
	makeTicketsByPriority();
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