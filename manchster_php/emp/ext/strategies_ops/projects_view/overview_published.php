<?php
$totalCount = 0;
$COND = "(`plan_id` = '$plan_id') AND ";
//$COND = "(`added_date` >= '$startDate') AND ( `added_date` <= '$endDate' ) AND ";
?>


<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("Overview", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a href="<?= $DIR_tickets; ?>?stt=0" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Themes", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('theme_id', 'm_strategic_plans_themes', $COND); ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Objectives", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('objective_id', 'm_strategic_plans_objectives', $COND); ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("KPIs", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('kpi_id', 'm_strategic_plans_kpis', $COND); ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Milestones-KPIs", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('milestone_id', 'm_strategic_plans_milestones', $COND); ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=2" class="boxKpi">
			<div class="dataName"><?= lang("External_Objectives", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('record_id', 'm_strategic_plans_external_maps', $COND); ?>">0</div>
		</a>
		


	</div>
</div>



<?php
$fff = 20;
?>
<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("Mapped_Milestones", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a href="<?= $DIR_tickets; ?>?stt=0" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Total", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('local_map_id', 'm_strategic_plans_internal_maps', $COND); ?>">0</div>
		</a>
<?php
$COND .= "(`is_task_assigned` = '0') AND ";
?>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Unassigned", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('local_map_id', 'm_strategic_plans_internal_maps', $COND); ?>">0</div>
		</a>
<?php
$COND .= "(`is_task_assigned` = '1') AND ";
?>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Assigned", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= getTotals('local_map_id', 'm_strategic_plans_internal_maps', $COND); ?>">0</div>
		</a>
		
<?php

$totalOpenTasks = 0;
$totalProgressTasks = 0;
$totalFinishedTasks = 0;
$totalTasks = 0;
$totalTasksProgress = 0;

$qu_tasks_list_sel = "SELECT * FROM  `tasks_list` WHERE (`strategic_plan_id` = '$plan_id')";
$qu_tasks_list_EXE = mysqli_query($KONN, $qu_tasks_list_sel);
if(mysqli_num_rows($qu_tasks_list_EXE)){
	while($tasks_list_REC = mysqli_fetch_assoc($qu_tasks_list_EXE)){
		$task_id = ( int ) $tasks_list_REC['task_id'];
		$status_id = ( int ) $tasks_list_REC['status_id'];
		$totalTasksProgress += ( int ) $tasks_list_REC['task_progress'];

		if( $status_id == 1 ){
			$totalOpenTasks++;
		} else if( $status_id == 2 ){
			$totalProgressTasks++;
		} else if( $status_id == 3 ){
			$totalFinishedTasks++;
		}
		$totalTasks++;
	}
}


?>
		<a href="<?= $DIR_tickets; ?>?stt=2" class="boxKpi">
			<div class="dataName"><?= lang("Open", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalOpenTasks; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=3" class="boxKpi">
			<div class="dataName"><?= lang("Started", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalProgressTasks; ?>">0</div>
		</a>


	</div>
</div>








<div class="row">

	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
			<h1><?= lang("Objectives Internal Distribution", "AAR"); ?></h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="doughnut" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

	<div class="col-2" style="background: #FFF;box-shadow: 1px 2px 5px var(--shadow-color);">
		<div class="overAllViewer">
		<h1><?= lang("Objectives External Distribution", "AAR"); ?></h1>
		</div>
		<div class="chart-container" style="position: relative; height:20em; width:100%;text-align:center;">
			<canvas id="externalDist" width="800" height="400" style="margin:0 auto;"></canvas>
		</div>
	</div>

</div>


<script src="<?= $POINTER; ?>../public/assets/js/chart.umd.min.js"></script>


<script>
	
	
	function drawObjectivesByDept() {
		const ctx = document.getElementById('doughnut');

		const myChart = new Chart(ctx, {
			type: 'bar',
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


							$qu_ss_sel = "SELECT COUNT(`department_id`) FROM  `m_strategic_plans_internal_maps` WHERE ( (`department_id` = $department_id ) AND ( `plan_id` = '$plan_id' ) )";
							$qu_ss_EXE = mysqli_query($KONN, $qu_ss_sel);
							if (mysqli_num_rows($qu_ss_EXE)) {
								$ss_DATA = mysqli_fetch_array($qu_ss_EXE);
								$deptCount = (int) $ss_DATA[0];
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
					label: '<?= lang("Internal_Objectives"); ?>',
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
	
	
	function drawExternalByObjective() {
		const ctx = document.getElementById('externalDist');

		const myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: [
					<?php
					$tots = 1;
					$totsArrS = array();
					$totsArrC = array();

					$qu_employees_list_departments_sel = "SELECT DISTINCT(`external_entity_name`), `record_id` FROM  `m_strategic_plans_external_maps` 
															 WHERE ( ( `plan_id` = '$plan_id' ) ) GROUP BY `external_entity_name`";
					$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
					if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
						while ($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)) {
							$record_id = (int) $employees_list_departments_REC['record_id'];
							$external_entity_name = $employees_list_departments_REC['external_entity_name'];

							$deptCount = 0;
							$qu_ss_sel = "SELECT COUNT(`record_id`) FROM  `m_strategic_plans_external_maps` WHERE ( (`external_entity_name` = '$external_entity_name' ) AND ( `plan_id` = '$plan_id' ) )";
							$qu_ss_EXE = mysqli_query($KONN, $qu_ss_sel);
							if (mysqli_num_rows($qu_ss_EXE)) {
								$ss_DATA = mysqli_fetch_array($qu_ss_EXE);
								$deptCount = (int) $ss_DATA[0];
							}
							if ($deptCount != 0) {
								$totsArrS[$tots] = $record_id;
								$totsArrC[$tots] = $deptCount;
								?>
								'<?= $external_entity_name; ?>',
								<?php
								$tots++;
							}
						}
					}
					?>
				],
				datasets: [{
					label: '<?= lang("External_Objectives"); ?>',
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
</script>
































<script>
	
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

var isLoaded = 0;
	function DrawInsights(){
		countNums();
		if( isLoaded == 0 ){
		drawObjectivesByDept();
		drawExternalByObjective();
		isLoaded = 1;

		}

	}
</script>