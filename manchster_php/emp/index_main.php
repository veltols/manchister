<?php
include('index_nav.php');
?>

<?php
$totalAnnouncs = 0;
$qu_hr_documents_sel = "SELECT * FROM  `hr_documents` WHERE `document_type_id` = 3 ORDER BY `document_id` DESC LIMIT 5";
$qu_hr_documents_EXE = mysqli_query($KONN, $qu_hr_documents_sel);
if (mysqli_num_rows($qu_hr_documents_EXE)) {
	?>
	<div class="announcer">

		<div class="btnner" onclick="goAnnLeft();">
			<i class="fa-solid fa-chevron-left"></i>
		</div>
		<?php
		$idder = 0;
		while ($hr_documents_REC = mysqli_fetch_assoc($qu_hr_documents_EXE)) {
			$idder++;
			$totalAnnouncs++;
			$titler = $hr_documents_REC['document_title'];
			$descer = $hr_documents_REC['document_description'];
			$datter = $hr_documents_REC['added_date'];
			$thsClass = '';
			if ($idder > 1) {
				$thsClass = 'annoucerBodyHidden';
			}
			?>
			<div id="ann-<?= $idder; ?>" class="annoucerBody <?= $thsClass; ?>">
				<h1><?= $titler; ?></h1>
				<p class="datter">on <?= $datter; ?></p>
				<p class="annouceDesc">
					<?= nl2br($descer); ?>
				</p>
			</div>
			<?php
		}


		?>

		<div class="btnner" onclick="goAnnRight();">
			<i class="fa-solid fa-chevron-right"></i>
		</div>

	</div>

	<?php
}
?>

<script>
	var maxAnn = <?= $totalAnnouncs; ?>;
	var minAnn = 1;
	var curAnn = 1;
	function goAnnRight() {
		var nwAnn = curAnn - 1;
		if (nwAnn < minAnn) {
			nwAnn = maxAnn;
		}
		$('#ann-' + curAnn).addClass('annoucerBodyHidden');
		$('#ann-' + nwAnn).removeClass('annoucerBodyHidden');
		curAnn = nwAnn;
	}
	function goAnnLeft() {
		var nwAnn = curAnn + 1;
		if (nwAnn > maxAnn) {
			nwAnn = minAnn;
		}
		$('#ann-' + curAnn).addClass('annoucerBodyHidden');
		$('#ann-' + nwAnn).removeClass('annoucerBodyHidden');
		curAnn = nwAnn;
	}
</script>







<!-- ------------------------------- IT DASHBOARD START ------------------------------------ -->


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
		<?= lang("My_Tickets", ""); ?>
	</div>
	<div class="kpiBoxes">

	
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("Total_tickets", "AAR"); ?> <i class="fa-solid fa-file"></i></div>
			<div class="dataValue count" data-stop="<?= $totalTickets; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("Unassigned_tickets", "AAR"); ?> <i class="fa-solid fa-folder"></i></div>
			<div class="dataValue count" data-stop="<?= $totalUnassigned; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=2" class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("in_progress", "AAR"); ?> <i class="fa-solid fa-list"></i></div>
			<div class="dataValue count" data-stop="<?= $totalProgress; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=3" class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("resolved", "AAR"); ?> <i class="fa-solid fa-check"></i></div>
			<div class="dataValue count" data-stop="<?= $totalDone; ?>">0</div>
		</a>


	</div>
</div>


<!-- ------------------------------- IT DASHBOARD END ------------------------------------ -->



<br><br>





<!-- ------------------------------- ASSETS DASHBOARD START ------------------------------------ -->



						<?php

						$qu_z_assets_list_sel = "SELECT 
														`z_assets_list`.`asset_id`,  
														`z_assets_list`.`asset_ref`,  
														`z_assets_list`.`asset_name`,  
														`z_assets_list`.`asset_sku`,  
														`z_assets_list`.`assigned_by`,  
														`z_assets_list`.`assigned_date`,  
														CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_employee`

														FROM `z_assets_list` 
														INNER JOIN `employees_list` `a` ON `z_assets_list`.`assigned_by` = `a`.`employee_id` 
														ORDER BY `z_assets_list`.`asset_id` ASC LIMIT 5";
						$qu_z_assets_list_EXE = mysqli_query($KONN, $qu_z_assets_list_sel);
						if (mysqli_num_rows($qu_z_assets_list_EXE)) {
							?>


		<div class="overAllViewer">
			<h1><?= lang("My_Assets", ""); ?></h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("REF"); ?></div>
							<div class="th"><?= lang("Name"); ?></div>
							<div class="th"><?= lang("Assigned_By"); ?></div>
							<div class="th"><?= lang("Assigned_Date"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="">
							<?php
							while ($z_assets_list_REC = mysqli_fetch_assoc($qu_z_assets_list_EXE)) {
								$idder = (int) $z_assets_list_REC['asset_id'];
								$asset_ref = $z_assets_list_REC['asset_ref'];
								$asset_name = $z_assets_list_REC['asset_name'];
								$asset_sku = $z_assets_list_REC['asset_sku'];
								$assigned_date = $z_assets_list_REC['assigned_date'];
								$thsAddedBy = "" . $z_assets_list_REC['added_employee'];
								?>

								<div class="tr levelRow" id="ticket-<?= $idder; ?>">
									<div class="td"><?= $asset_ref; ?></div>
									<div class="td"><?= $asset_name; ?></div>
									<div class="td"><?= $thsAddedBy; ?></div>
									<div class="td"><?= $assigned_date; ?></div>
								</div>
								<?php
							}
							
							?>

					</div>
				</div>
			</div>

		</div>
		
							<?php
						}

						?>




<!-- ------------------------------- ASSETS DASHBOARD END ------------------------------------ -->




<br><br>


<!-- ------------------------------- HR DASHBOARD START ------------------------------------ -->


<?php
$totalLeaveBalance = 0;
$RemainingLeaveBalance = 0;
$totalHrRequests = 0;
$totalPending = 0;


?>



<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("HR", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("Total_Leaves_Balance", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalLeaveBalance; ?>">0</div>
		</a>
		<a class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("Remaining_Leaves", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $RemainingLeaveBalance; ?>">0</div>
		</a>
		<a class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("total_HR_Requests", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalHrRequests; ?>">0</div>
		</a>
		<a class="boxKpi boxKpiX4">
			<div class="dataName"><?= lang("Pending_Approval", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalPending; ?>">0</div>
		</a>
		
	</div>
</div>



						<?php

						$qu_hr_documents_sel = "SELECT * FROM  `hr_documents` WHERE `document_type_id` = 3 ORDER BY `document_id` DESC LIMIT 5";
						$qu_hr_documents_EXE = mysqli_query($KONN, $qu_hr_documents_sel);
						if (mysqli_num_rows($qu_hr_documents_EXE)) {
							?>

<div class="row">
	
	<div class="col-1" >
		<div class="overAllViewer">
			<h1><?= lang("Latest_Annoucements", ""); ?></h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Title"); ?></div>
							<div class="th"><?= lang("description"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="">
							<?php
							while ($hr_documents_REC = mysqli_fetch_assoc($qu_hr_documents_EXE)) {
								$document_id = (int) $hr_documents_REC['document_id'];
								$document_title = $hr_documents_REC['document_title'];
								$document_description = $hr_documents_REC['document_description'];
								$document_attachment = $hr_documents_REC['document_attachment'];
								$document_type_id = (int) $hr_documents_REC['document_type_id'];
								?>

								<div class="tr levelRow" id="ticket-<?= $document_id; ?>">
									<div class="td"><?= $document_title; ?></div>
									<div class="td"><?= $document_description; ?></div>
								</div>
								<?php
							}
							?>
							</div>
						</div>
					</div>
		
				</div>
			</div>
			
		</div>
							<?php
						}

						?>

<!-- ------------------------------- HR DASHBOARD END ------------------------------------ -->

<br><br>
		<div class="overAllViewer">
			<h1><?= lang("Latest_Tasks", ""); ?></h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Title"); ?></div>
							<div class="th"><?= lang("description"); ?></div>
							<div class="th"><?= lang("Priority"); ?></div>
							<div class="th"><?= lang("Status"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="">
						<?php
						/*
						$tableName = 'tasks_list';
						$qu_tasks_list_sel = "SELECT `$tableName`.*, 
																CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `assigned_by`,
																`sys_list_status`.`status_name` AS `task_status`,
																`sys_list_status`.`status_color` AS `status_color`,

																`sys_list_priorities`.`priority_name` AS `task_priority`,
																`sys_list_priorities`.`priority_color` AS `priority_color` 
														FROM  `$tableName` 
														INNER JOIN `employees_list` ON `$tableName`.`assigned_by` = `employees_list`.`employee_id` 
														INNER JOIN `sys_list_status` ON `$tableName`.`status_id` = `sys_list_status`.`status_id` 
														INNER JOIN `sys_list_priorities` ON `$tableName`.`theme_id` = `sys_list_priorities`.`theme_id` 
														WHERE ( (`assigned_to`= $USER_ID) ) ORDER BY `task_id` DESC LIMIT 5";
						$qu_tasks_list_EXE = mysqli_query($KONN, $qu_tasks_list_sel);
						if (mysqli_num_rows($qu_tasks_list_EXE)) {
							while ($tasks_list_REC = mysqli_fetch_assoc($qu_tasks_list_EXE)) {
								$idder = (int) $tasks_list_REC['task_id'];
								$task_title = $tasks_list_REC['task_title'];
								$task_description = $tasks_list_REC['task_description'];
								$task_priority = $tasks_list_REC['task_priority'];
								$task_status = $tasks_list_REC['task_status'];
								?>

								<div class="tr levelRow" id="ticket-<?= $idder; ?>">
									<div class="td"><?= $task_title; ?></div>
									<div class="td"><?= $task_description; ?></div>
									<div class="td"><?= $task_priority; ?></div>
									<div class="td"><?= $task_status; ?></div>
								</div>
								<?php
							}
						}
*/
						?>
					</div>
				</div>
			</div>

		</div>
		



<?php
include("app/footer.php");
?>

<script src="<?= $POINTER; ?>../public/assets/js/chart.umd.min.js"></script>
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
	countNums();
</script>