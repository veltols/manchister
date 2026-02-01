<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Ticket_Details");
$pageController = $POINTER . 'update_QS';
$submitBtn = lang("Save_ticket", "AAR");
$isNewForm = true;


$updateTicketController = $POINTER . "update_tickets_list";
$ticket_id = 0;


if (!isset($_GET['ticket_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$ticket_id = (int) test_inputs($_GET['ticket_id']);

if ($ticket_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}

$pageId = 550;
$subPageId = 200;
include("app/assets.php");



$adder_name = '';
$ticket_status = '';
$assigned_to = 0;



$qu_QS_sel = "SELECT `support_tickets_list`.*, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_by`,
									`support_tickets_list_status`.`status_name` AS `ticket_status`,
									`support_tickets_list_status`.`status_color` AS `status_color`,

									`support_tickets_list_cats`.`category_name` AS `category_name`,

									`sys_list_priorities`.`priority_name` AS `ticket_priority`,
									`sys_list_priorities`.`priority_color` AS `priority_color` 
							FROM  `support_tickets_list` 
							LEFT JOIN `employees_list` ON `support_tickets_list`.`added_by` = `employees_list`.`employee_id` 
							LEFT JOIN `support_tickets_list_status` ON `support_tickets_list`.`status_id` = `support_tickets_list_status`.`status_id` 
							LEFT JOIN `support_tickets_list_cats` ON `support_tickets_list`.`category_id` = `support_tickets_list_cats`.`category_id` 
							LEFT JOIN `sys_list_priorities` ON `support_tickets_list`.`priority_id` = `sys_list_priorities`.`priority_id` 
							WHERE ( ( `support_tickets_list`.`ticket_id` = $ticket_id ) ) LIMIT 1";




$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
$QS_DATA;
$status_id = 0;
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_assoc($qu_QS_EXE);
	$adder_name = "" . $QS_DATA['added_by'];
	$ticket_status = "" . $QS_DATA['ticket_status'];
	$status_id = (int) $QS_DATA['status_id'];
	$assigned_to = (int) $QS_DATA['assigned_to'];
}







?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Tickets_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		$subPageId = 1500;
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
			<div class="number"><?= $ticket_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('ticket_ref', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_ref']; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Prioirty', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_priority']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['priority_color']; ?>" class="fa-solid fa-list-ol"></i>
			</div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_status']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['status_color']; ?>" class="fa-solid fa-file"></i>
			</div>
		</div>




	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Ticket_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('tickets_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('tickets_view/logs.php');
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



	function assignTicket() {
		showModal('reAssignTicket');
	}
	function markDone() {
		$('.ticket_op').val(3);
		showModal('finishTicketModal');
	}
	function sttInProgress() {
		$('#finishTicketModal .modalHeader h1').text('<?= lang("Update_Ticket_Status - In Progress", "AAR"); ?>');
		$('.ticket_op').val(2);
		showModal('finishTicketModal');
	}
	function reOpenTicket() {
		$('#finishTicketModal .modalHeader h1').text('<?= lang("Update_Ticket_Status - Reopen Ticket", "AAR"); ?>');
		$('.ticket_op').val(100);
		showModal('finishTicketModal');
	}
</script>


<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>



<!-- Modals START -->
<div class="modal" id="reAssignTicket">
	<div class="modalHeader">
		<h1><?= lang("Assign_Ticket", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="reassignTicketForm">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Assign_to", "AAR"); ?></label>
					<input type="hidden" class="inputer ticket_id_reassign" data-name="ticket_id" data-den="0"
						data-req="1" value="<?= $ticket_id; ?>" data-type="hidden">
					<input type="hidden" class="inputer" data-name="op" data-den="0" data-req="1" value="200"
						data-type="hidden">
					<select class="inputer" data-name="assigned_to" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`department_id` = 4) ) ORDER BY `employee_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$emp_id = (int) $SEL_REC['employee_id'];
								$namer = $SEL_REC['namer'];
								?>
								<option value="<?= $emp_id; ?>"><?= $namer; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer ticket_remarks" data-name="ticket_remarks" data-den=""
						data-req="1" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('reassignTicketForm', '<?= $updateTicketController; ?>');"><?= lang("Assign_Ticket", "AAR"); ?></button>
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

<!-- Modals START -->
<div class="modal" id="finishTicketModal">
	<div class="modalHeader">
		<h1><?= lang("Update_Ticket_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="finishTicketForm">




			<!-- ELEMENT START -->
			<div class="col-1" id="ticket_remarks">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<input type="hidden" class="inputer ticket_id_done" data-name="ticket_id" data-den="0" data-req="1"
						value="<?= $ticket_id; ?>" data-type="hidden">
					<input type="hidden" class="inputer ticket_op" data-name="op" data-den="0" data-req="1" value="1"
						data-type="hidden">
					<textarea type="text" class="inputer ticket_remarks" data-name="ticket_remarks" data-den=""
						data-req="1" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('finishTicketForm', '<?= $updateTicketController; ?>');"><?= lang("Update_Ticket", "AAR"); ?></button>
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
