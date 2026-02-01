<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Tickets_List");

$pageDataController = $POINTER . "get_tickets_list";
$addNewTicketController = $POINTER . "add_tickets_list";
$updateTicketController = $POINTER . "update_tickets_list";

$pageId = 550;
$subPageId = 200;
include("app/assets.php");

$thsStt = isset($_GET['stt']) ? (int) test_inputs($_GET['stt']) : 0;
if ($thsStt == 0) {
	//all
	$subPageId = 200;
	$extra_id = 0;
} else if ($thsStt == 1) {
	//open
	$subPageId = 300;
	$extra_id = 1;
} else if ($thsStt == 2) {
	//in progress
	$subPageId = 400;
	$extra_id = 2;
} else if ($thsStt == 3) {
	//Closed
	$subPageId = 500;
	$extra_id = 3;
}


?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Tickets_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('tickets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink hasTooltip" onclick="showModal('newTicketModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("new_ticket"); ?></div>
		</a>
	</div>
</div>






<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Category"); ?></div>
				<div class="th"><?= lang("Added_By"); ?></div>
				<div class="th"><?= lang("Added_Date"); ?></div>
				<div class="th"><?= lang("assigned_to"); ?></div>
				<div class="th"><?= lang("last_updated"); ?></div>
				<div class="th"><?= lang("updated_by"); ?></div>
				<div class="th"><?= lang("Priority"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData"></div>
	</div>
</div>




<script>
	async function bindData(data) {

		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>tickets/view/?ticket_id=' + data[i]["ticket_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			var stt = getInt(data[i]["status_id"]);
			var assigned_to = getInt(data[i]["assigned_to"]);


			btns += '<a onclick="assignTicket(' + data[i]["ticket_id"] + ');" class="tableBtn tableBtnWarning hasTooltip">' +
				'	<i class="fa-regular fa-user"></i>' +
				'	<div class="tooltip"><?= lang("Assign_to_user"); ?></div>' +
				'</a>';

			if (stt == 1) {
				if (assigned_to != 0) {
					//open
					btns += '<a onclick="sttInProgress(' + data[i]["ticket_id"] + ');" class="tableBtn tableBtnInfo hasTooltip">' +
						'	<i class="fa-regular fa-hourglass-half"></i>' +
						'	<div class="tooltip"><?= lang("In_Progress"); ?></div>' +
						'</a>';
					btns += '<a onclick="markDone(' + data[i]["ticket_id"] + ');" class="tableBtn tableBtnInfo hasTooltip">' +
						'	<i class="fa-solid fa-check"></i>' +
						'	<div class="tooltip"><?= lang("Resolve"); ?></div>' +
						'</a>';
				}
			} else if (stt == 2) {
				if (assigned_to != 0) {
					//in progress
					btns += '<a onclick="markDone(' + data[i]["ticket_id"] + ');" class="tableBtn tableBtnInfo hasTooltip">' +
						'	<i class="fa-solid fa-check"></i>' +
						'	<div class="tooltip"><?= lang("Resolve"); ?></div>' +
						'</a>';
				}
			} else if (stt == 3) {
				if (assigned_to != 0) {
					//Resolved
					btns += '<a onclick="reOpenTicket(' + data[i]["ticket_id"] + ');" class="tableBtn tableBtnDanger hasTooltip">' +
						'	<i class="fa-solid fa-folder-open"></i>' +
						'	<div class="tooltip"><?= lang("Reopen"); ?></div>' +
						'</a>';
				}
			}


			//btns= '---';
			var dt = '<div class="tr levelRow" id="ticket-' + data[i]["ticket_id"] + '">' +
				'	<div class="td">' + data[i]["ticket_ref"] + '</div>' +
				'	<div class="td">' + data[i]["category_name"] + '</div>' +
				'	<div class="td">' + data[i]["added_employee"] + '</div>' +
				'	<div class="td">' + data[i]["ticket_added_date"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_to_name"] + '</div>' +
				'	<div class="td">' + data[i]["last_updated"] + '</div>' +
				'	<div class="td">' + data[i]["updated_by"] + '</div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["priority_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["ticket_priority"] + '</span></div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["status_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["ticket_status"] + '</span></div>' +
				'	<div class="td ">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("No_records_found"); ?><br><div class="tblBtnsGroup">' +
				'<a onclick="addNewTicket();" class="tableBtn tableBtnInfo">Add New Ticket</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewTicket() {
		showModal('newTicketModal');
	}
	function assignTicket(tId) {
		$('.ticket_id_reassign').val(tId);
		showModal('reAssignTicket');
	}
	function markDone(tId) {
		$('.ticket_id_done').val(tId);
		$('.ticket_op').val(3);
		showModal('finishTicketModal');
	}
	function sttInProgress(tId) {
		$('.ticket_id_done').val(tId);
		$('#finishTicketModal .modalHeader h1').text('<?= lang("Update_Ticket_Status - In Progress", "AAR"); ?>');
		$('.ticket_op').val(2);
		showModal('finishTicketModal');
	}
	function reOpenTicket(tId) {
		$('.ticket_id_done').val(tId);
		$('#finishTicketModal .modalHeader h1').text('<?= lang("Update_Ticket_Status - Reopen Ticket", "AAR"); ?>');
		$('.ticket_op').val(100);
		showModal('finishTicketModal');
	}
</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
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
						data-req="1" value="0" data-type="hidden">
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
<div class="modal" id="newTicketModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Ticket", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newTicketForm">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("added_by", "AAR"); ?></label>
					<select class="inputer new-added_by" data-name="added_by" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE  ( (`is_deleted` = 0) AND (`is_hidden` = 0)) ORDER BY `employee_id` ASC";
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
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Category", "AAR"); ?></label>
					<select class="inputer" data-name="category_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `category_id`, `category_name` FROM  `support_tickets_list_cats` ORDER BY `category_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$category_id = (int) $SEL_REC['category_id'];
								$category_name = $SEL_REC['category_name'];
								?>
								<option value="<?= $category_id; ?>"><?= $category_name; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Priority", "AAR"); ?></label>
					<select class="inputer" data-name="priority_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `priority_id`, `priority_name` FROM  `sys_list_priorities` ORDER BY `priority_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$priority_id = (int) $SEL_REC['priority_id'];
								$priority_name = $SEL_REC['priority_name'];
								?>
								<option value="<?= $priority_id; ?>"><?= $priority_name; ?></option>

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
					<label><?= lang("ticket_subject", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="ticket_subject" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("ticket_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="ticket_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Attachment", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="ticket_attachment" data-den="" data-req="0"
						data-type="file">
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
						onclick="submitForm('newTicketForm', '<?= $addNewTicketController; ?>');"><?= lang("Create_Ticket", "AAR"); ?></button>
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
						value="0" data-type="hidden">
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
		goToFirstPage();
		window.location.reload();
	}
</script>
