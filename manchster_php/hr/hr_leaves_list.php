<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Leaves_List");



//hr_leaves
$pageDataController = $POINTER . "get_hr_leaves_list";
$addNewController = $POINTER . 'add_hr_leaves_list';
$updateController = $POINTER . 'update_hr_leaves_list';



$pageId = 550;
$subPageId = 200;
include("app/assets.php");
?>




<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= $page_title; ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('requests_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="addNewLeave();">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("Add_New"); ?></div>
		</a>
	</div>
</div>




<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Employee_name"); ?></div>
				<div class="th"><?= lang("Leave_Type"); ?></div>
				<div class="th"><?= lang("submission_date"); ?></div>
				<div class="th"><?= lang("Leave_Start"); ?></div>
				<div class="th"><?= lang("Leave_End"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Remarks"); ?></div>
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

			var stt = getInt(data[i]["leave_status_id"]);





			btns += '<a onclick="getData(' + data[i]["leave_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("View"); ?></div>' +
				'</a>';

			if (stt == 1) {
				btns += '<a onclick="changeLeaveStt(' + data[i]["leave_id"] + ');" class="dataActionBtn tableBtnInfo hasTooltip">' +
					'	<i class="fa-solid fa-user"></i>' +
					'	<div class="tooltip"><?= lang("Send_back_to_user"); ?></div>' +
					'</a>';

				btns += '<a onclick="changeLeaveStt(' + data[i]["leave_id"] + ');" class="dataActionBtn tableBtnWarning hasTooltip">' +
					'	<i class="fa-solid fa-check"></i>' +
					'	<div class="tooltip"><?= lang("Send_for_approval"); ?></div>' +
					'</a>';
			}




			var btns = '';
			btns = '<div class="extraMenu extraMenuHidden" id="em-' + i + '">';
			btns += '	<div class="mnuItemBtn" onclick="showSmallMnu(' + i + ');"><i class="fa-solid fa-ellipsis-vertical"></i></div>';
			btns += '	<div class="mnuItems">';
			btns += '		<a onclick="getData(' + data[i]["leave_id"] + ');"><?= lang("View"); ?></a>';
			//------------------------------------
			if (stt == 1) {
				btns += '		<a onclick="changeLeaveStt(' + data[i]["leave_id"] + ', 200' + ');"><?= lang("Send_back_to_user"); ?></a>';
				btns += '		<a onclick="changeLeaveStt(' + data[i]["leave_id"] + ', 100' + ');"><?= lang("Send_for_approval"); ?></a>';
			}
			//btns += '		<a href="<?= $POINTER; ?>leads/view/?lead_id=' + data[i]["leave_id"] + '&v=det"><?= lang("Details"); ?></a>';
			//----------------------------------------------------------------------------
			btns += '	</div>';
			btns += '</div>';





			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["leave_id"] + '">' +
				'	<div class="td row-leave_id">' + data[i]["leave_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-leave_type">' + data[i]["leave_type"] + '</div>' +
				'	<div class="td row-submission_date">' + data[i]["submission_date"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["start_date"] + '</div>' +
				'	<div class="td row-end_date">' + data[i]["end_date"] + '</div>' +
				'	<div class="td row-leave_status">' + data[i]["leave_status"] + '</div>' +
				'	<div class="td row-leave_remarks">' + data[i]["leave_remarks"] + '</div>' +
				'	<div class="td">' +
				btns +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
		}

	}
	function getData(leave_id) {

		if (leave_id != 0) {
			//get data from server
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $pageDataController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "leave_id": leave_id },
					success: function (RES) {
						var itm = RES[0];
						activeRequest = false;
						end_loader('article');
						if (itm['success'] == true) {
							bindViewData(itm['data']);
						}
					},
					error: function () {
						activeRequest = false;
						end_loader('article');
						alert("Err-327657");
					}
				});
			}
		}

	}

	function bindViewData(response) {
		hideAllAmMnus();
		//data[i]["leave_id"]
		resetForm('AddNewForm');
		$('.edit-submission_date').val(response[0].submission_date);
		$('.edit-start_date').val(response[0].start_date);
		$('.edit-end_date').val(response[0].end_date);
		$('.edit-leave_type_id').val(response[0].leave_type_id);
		$('.edit-total_days').val(response[0].total_days);
		$('.edit-leave_remarks').val(response[0].leave_remarks);
		$('.edit-employee_id').val(response[0].employee_id);
		$('.edit-leave_status_id').val(response[0].leave_status_id);
		$('.edit-leave_remarks').val(response[0].leave_remarks);
		$('.edit-leave_attachment').attr('href', '<?= $POINTER; ?>../uploads/' + response[0].leave_attachment);

		Ncalc_defEdt();
		$('#ViewModal .modalHeader h1').text('View Leave Details');

		showModal('ViewModal');
	}
	function changeLeaveStt(leave_id, nwStt) {
		//data[i]["leave_id"]
		hideAllAmMnus();
		resetForm('changeStatusForm');
		$('.edit-leave_id').val(leave_id);
		$('.edit-leave_status_id').val(nwStt);

		showModal('changeStatusModal');
	}

	function addNewLeave() {
		hideAllAmMnus();
		$('#AddNewModal .modalHeader h1').text('Add New Leave');
		resetForm('AddNewForm');
		$('.new-leave_id').val(1);
		$('.new-submission_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewLeaveBtn').show();
		showModal('AddNewModal');
	}
</script>





<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>

<!-- Modals START -->
<div class="modal" id="changeStatusModal">
	<div class="modalHeader">
		<h1><?= lang("Update_Details", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="changeStatusForm">


			<input type="hidden" class="inputer edit-leave_id" data-name="leave_id" data-den="" data-req="1" value="0"
				data-type="hidden">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Leave_Status", "AAR"); ?></label>
					<select class="inputer edit-leave_status_id" data-name="leave_status_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<option value="100"><?= lang(data: "Send_For_Approval", trans: "AAR"); ?></option>
						<option value="200"><?= lang(data: "Send_For_User", trans: "AAR"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer edit-log_remark" data-name="log_remark" data-den=""
						data-req="1" data-type="text" rows="3"></textarea>
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
						onclick="submitForm('changeStatusForm', '<?= $updateController; ?>');"><?= lang("Update", "AAR"); ?></button>
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
<div class="modal modal-lg" id="ViewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="EditForm">


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Employee", "AAR"); ?></label>
					<select class="inputer edit-employee_id" data-name="employee_id" data-den="0" data-req="1"
						data-type="text" readonly disabled>
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
					<label><?= lang("submission_date", "AAR"); ?></label>
					<input type="text" class="inputer edit-submission_date" data-name="submission_date" data-den=""
						data-req="0" value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_date", "AAR"); ?></label>
					<input type="text" class="inputer  edit-start_date" data-name="start_date" data-den="" data-req="1"
						data-type="date" readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_date", "AAR"); ?></label>
					<input type="text" class="inputer  edit-end_date" data-name="end_date" data-den="" data-req="1"
						data-type="date" readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Total_Days", "AAR"); ?></label>
					<input class="frmData" type="text" id="edit-total_days" name="total_days" req="1" den=""
						alerter="<?= lang("Please_Check_total_days ", "AAR"); ?>" readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<script>
				function Ncalc_defEdt() {
					var start = new Date($('.edit-start_date').val());
					var end = new Date($('.edit-end_date').val());

					if (start > end) {
						var temp = start;
						start = end;
						end = temp;
					}


					var count = 0;
					var current = new Date(start);

					// Loop through each day
					while (current <= end) {
						var day = current.getDay(); // 0 = Sunday, 6 = Saturday
						if (day !== 0 && day !== 6) {
							count++;
						}
						current.setDate(current.getDate() + 1);
					}

					if (!isNaN(count)) {
						$('#edit-total_days').val(count);
					}
				}

			</script>



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Leave_Type", "AAR"); ?></label>
					<select class="inputer edit-leave_type_id" data-name="leave_type_id" data-den="0" data-req="1"
						data-type="text" readonly disabled>
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `leave_type_id`, `leave_type_name` FROM  `hr_employees_leave_types` ORDER BY `leave_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$leave_type_id = (int) $SEL_REC['leave_type_id'];
								$leave_type_name = $SEL_REC['leave_type_name'];
								?>
								<option value="<?= $leave_type_id; ?>"><?= $leave_type_name; ?></option>
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
				<div class="formElement"><br><br></div>
			</div>




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Attachment", "AAR"); ?></label>
					<a class="edit-leave_attachment" target="_blank">
						<button class="cancelBtn" type="button"><?= lang("View Attachment", "AAR"); ?></button>
					</a>
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer edit-leave_remarks" data-name="leave_remarks" data-den=""
						data-req="1" data-type="text" rows="3" readonly disabled></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>



		</div>
	</div>
</div>
<!-- Modals END -->




<!-- Modals START -->
<div class="modal modal-lg" id="AddNewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="AddNewForm">

			<input type="hidden" class="inputer new-leave_id" data-name="leave_id" data-den="" data-req="1" value="0"
				data-type="hidden">
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Employee", "AAR"); ?></label>
					<select class="inputer new-employee_id" data-name="employee_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
					<label><?= lang("submission_date", "AAR"); ?></label>
					<input type="text" class="inputer new-submission_date" data-name="submission_date" data-den=""
						data-req="0" value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date new-start_date" data-name="start_date" data-den=""
						data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date new-end_date" data-name="end_date" data-den=""
						data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Total_Days", "AAR"); ?></label>
					<input class="frmData" type="text" id="new-total_days" name="total_days" req="1" den=""
						alerter="<?= lang("Please_Check_total_days ", "AAR"); ?>" readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<script>
				function Ncalc_def() {
					var start = new Date($('.new-start_date').val());
					var end = new Date($('.new-end_date').val());

					if (start > end) {
						var temp = start;
						start = end;
						end = temp;
					}


					var count = 0;
					var current = new Date(start);

					// Loop through each day
					while (current <= end) {
						var day = current.getDay(); // 0 = Sunday, 6 = Saturday
						if (day !== 0 && day !== 6) {
							count++;
						}
						current.setDate(current.getDate() + 1);
					}

					if (!isNaN(count)) {
						$('#new-total_days').val(count);
					}
				}


				$('.new-start_date').on('change', function () {
					Ncalc_def();
				});
				$('.new-end_date').on('change', function () {
					Ncalc_def();
				});

			</script>



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Leave_Type", "AAR"); ?></label>
					<select class="inputer new-leave_type_id" data-name="leave_type_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `leave_type_id`, `leave_type_name` FROM  `hr_employees_leave_types` ORDER BY `leave_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$leave_type_id = (int) $SEL_REC['leave_type_id'];
								$leave_type_name = $SEL_REC['leave_type_name'];
								?>
								<option value="<?= $leave_type_id; ?>"><?= $leave_type_name; ?></option>
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
					<label><?= lang("Attachment", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="leave_attachment" data-den="" data-req="0"
						data-type="file">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer new-leave_remarks" data-name="leave_remarks" data-den=""
						data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewLeaveBtn">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('AddNewForm', '<?= $addNewController; ?>');"><?= lang("Create", "AAR"); ?></button>
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
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
