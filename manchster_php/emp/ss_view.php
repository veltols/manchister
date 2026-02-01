<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Request_Details");
$pageController = $POINTER . 'update_QS';
$updateSSController = $POINTER . "update_ss_list";
$submitBtn = lang("Save_ticket", "AAR");
$isNewForm = true;


$ss_id = 0;


if (!isset($_GET['ss_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$ss_id = (int) test_inputs($_GET['ss_id']);

if ($ss_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}

$pageId = 55006;
$subPageId = 200;
include("app/assets.php");



$adder_name = '';
$ss_status = '';
$sent_to_name = '';
$sent_to_id = 0;



$qu_QS_sel = "SELECT `ss_list`.*, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_by`,
									CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `sent_to_name`,
									`ss_list_status`.`status_name` AS `ss_status`,
									`ss_list_status`.`status_color` AS `status_color`,

									`ss_list_cats`.`category_name` AS `category_name`

							FROM  `ss_list` 
							INNER JOIN `employees_list` ON `ss_list`.`added_by` = `employees_list`.`employee_id` 
							LEFT JOIN `employees_list` `b` ON `ss_list`.`sent_to_id` = `b`.`employee_id` 
							INNER JOIN `ss_list_status` ON `ss_list`.`status_id` = `ss_list_status`.`status_id` 
							INNER JOIN `ss_list_cats` ON `ss_list`.`category_id` = `ss_list_cats`.`category_id` 
							WHERE ( ( `ss_list`.`ss_id` = $ss_id ) ) LIMIT 1";




$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
$QS_DATA;
$status_id = 0;
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_assoc($qu_QS_EXE);
	$adder_name = "" . $QS_DATA['added_by'];
	$ss_status = "" . $QS_DATA['ss_status'];
	$status_id = (int) $QS_DATA['status_id'];
	$sent_to_id = (int) $QS_DATA['sent_to_id'];
	$sent_to_name = "" . $QS_DATA['sent_to_name'];
	if ($sent_to_name == "") {
		$sent_to_name = lang("Not_Assigned", "AAR");
	}
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
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $ss_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('ss_ref', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ss_ref']; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>


		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ss_status']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['status_color']; ?>" class="fa-solid fa-file"></i>
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
				include('ss_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('ss_view/logs.php');
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
		<h1><?= lang("Update_Request_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="RequestSttForm">




			<!-- ELEMENT START -->
			<div class="col-1" id="log_remark">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<input type="hidden" class="inputer ss_id" data-name="ss_id" data-den="0" data-req="1"
						value="<?= $ss_id; ?>" data-type="hidden">
					<input type="hidden" class="inputer edit-status_id" data-name="status_id" data-den="0" data-req="1"
						value="0" data-type="hidden">

					<textarea type="text" class="inputer ss_remarks" data-name="ss_remarks" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-1" id="ss_result_attachment">
				<div class="formElement">
					<label><?= lang("Attachment", "AAR"); ?></label>
					<input type="file" class="inputer edit-ss_result_attachment" data-name="ss_result_attachment"
						data-den="" data-req="0" data-type="file">
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
