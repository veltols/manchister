<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Request_Details");
$pageController = $POINTER . 'update_QS';
$updateSSController = $POINTER . "update_communications_list";
$submitBtn = lang("Save_ticket", "AAR");
$isNewForm = true;


$communication_id = 0;


if (!isset($_GET['communication_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$communication_id = (int) test_inputs($_GET['communication_id']);

if ($communication_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}

$pageId = 55006;
$subPageId = 600;
include("app/assets.php");



$qu_QS_sel = "SELECT `m_communications_list`.*, 
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_employee`,
									`m_communications_list_status`.`communication_status_name` AS `communication_status`,
									`m_communications_list_status`.`status_color` AS `status_color`,

									`m_communications_list_types`.`communication_type_name` AS `communication_type_name`
									
							FROM  `m_communications_list`
							INNER JOIN `employees_list` `a` ON `m_communications_list`.`requested_by` = `a`.`employee_id` 
							INNER JOIN `m_communications_list_status` ON `m_communications_list`.`communication_status_id` = `m_communications_list_status`.`communication_status_id` 
							INNER JOIN `m_communications_list_types` ON `m_communications_list`.`communication_type_id` = `m_communications_list_types`.`communication_type_id`
							WHERE ( ( `m_communications_list`.`communication_id` = $communication_id ) ) LIMIT 1";



$status_id = 0;
$communication_type_id = 0;
$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
$QS_DATA;
$communication_status_id = 0;
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_assoc($qu_QS_EXE);
	$status_id = (int) $QS_DATA['communication_status_id'];
	$communication_type_id = (int) $QS_DATA['communication_type_id'];
}



$lastUpdated = '---';
$updated_by = '---';

$qu_tickets_list_logs_sel = "SELECT `sys_logs`.`log_date`,
									`sys_logs`.`logged_by`, 
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `logged_by`
									FROM  `sys_logs` 
									INNER JOIN `employees_list` `a` ON `sys_logs`.`logged_by` = `a`.`employee_id` 
									WHERE ((`related_id` = " . $QS_DATA['communication_id'] . ") AND ( `related_table` = 'm_communications_list' ) ) ORDER BY `log_id` DESC LIMIT 1 ";
$qu_tickets_list_logs_EXE = mysqli_query($KONN, $qu_tickets_list_logs_sel);
if (mysqli_num_rows($qu_tickets_list_logs_EXE)) {
	$tickets_list_logs_DATA = mysqli_fetch_assoc($qu_tickets_list_logs_EXE);
	$lastUpdated = $tickets_list_logs_DATA['log_date'];
	$updated_by = $tickets_list_logs_DATA['logged_by'];
}



?>


<div class="pageHeader">
	<div class="pageNav">
		<?php
		$subPageId = 1502;
		include('tickets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">

	</div>
</div>


<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer" style="padding-bottom: 1em;">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('REF', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['communication_code']; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['communication_status']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['status_color']; ?>" class="fa-solid fa-file"></i>
			</div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Last_Updated', 'ARR'); ?></div>
			<div class="number"><?= $lastUpdated; ?></div>
			<div class="icon"><i class="fa-solid fa-calendar"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Updated_By', 'ARR'); ?></div>
			<div class="number"><?= $updated_by; ?></div>
			<div class="icon"><i class="fa-solid fa-user"></i></div>
		</div>
	</div>




</div>



<div class="tabContainer">
	<div class="tabHeaders">
		<div class="tabTitle" extra-action="doNothing"><?= lang('Request_Details', 'ARR'); ?></div>
		<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
	</div>
	<div class="tabBody">
		<div class="tabContent">
			<?php
			include('communications_view/details.php');
			?>
		</div>
		<div class="tabContent">
			<?php
			include('communications_view/logs.php');
			?>
		</div>
	</div>
</div>



<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>



<!-- Modals START -->
<div class="modal" id="UpdateSttModal">
	<div class="modalHeader">
		<h1><?= lang("Approve_Request_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="RequestSttForm">




			<!-- ELEMENT START -->
			<div class="col-1" id="log_remark">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<input type="hidden" class="inputer communication_id" data-name="communication_id" data-den="0"
						data-req="1" value="<?= $communication_id; ?>" data-type="hidden">

					<input type="hidden" class="inputer edit-communication_status_id"
						data-name="communication_status_id" data-den="0" data-req="1" value="0" data-type="hidden">

					<textarea type="text" class="inputer log_remarks" data-name="log_remarks" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('RequestSttForm', '<?= $updateSSController; ?>');"><?= lang("Update_Request", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->










<script>
	function afterFormSubmission() {
		closeModal();
		window.location.reload();
	}
</script>
