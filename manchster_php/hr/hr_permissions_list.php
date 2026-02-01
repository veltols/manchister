<?php
$page_title = $page_description = $page_keywords = $page_author = lang("permissions_List");



//hr_permissions
$pageDataController = $POINTER . "get_hr_permissions_list";
$addNewController = $POINTER . 'add_hr_permissions_list';
$updateController = $POINTER . 'update_hr_permissions_list';



$pageId = 550;
$subPageId = 300;
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
		<a class="pageLink" onclick="addNewPermission();">
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
				<div class="th"><?= lang("submission_date"); ?></div>
				<div class="th"><?= lang("Permission_date"); ?></div>
				<div class="th"><?= lang("Permission_Start"); ?></div>
				<div class="th"><?= lang("Permission_End"); ?></div>
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
			var stt = getInt(data[i]["permission_status_id"]);

			btns += '<a onclick="getData(' + data[i]["permission_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("View"); ?></div>' +
				'</a>';
			if (stt == 1) {
				btns += '<a onclick="changePermissionStt(' + data[i]["permission_id"] + ');" class="dataActionBtn tableBtnWarning hasTooltip">' +
					'	<i class="fa-regular fa-file"></i>' +
					'	<div class="tooltip"><?= lang("Update"); ?></div>' +
					'</a>';
			}

			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["permission_id"] + '">' +
				'	<div class="td row-permission_id">' + data[i]["permission_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-submission_date">' + data[i]["submission_date"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["start_date"] + '</div>' +
				'	<div class="td row-start_time">' + data[i]["start_time"] + '</div>' +
				'	<div class="td row-end_time">' + data[i]["end_time"] + '</div>' +
				'	<div class="td row-permission_status">' + data[i]["permission_status"] + '</div>' +
				'	<div class="td row-permission_remarks">' + data[i]["permission_remarks"] + '</div>' +
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
	function getData(permission_id) {

		if (permission_id != 0) {
			//get data from server
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $pageDataController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "permission_id": permission_id },
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
		//data[i]["permission_id"]
		resetForm('AddNewForm');
		$('.new-permission_id').val(response[0].permission_id);
		$('.new-submission_date').val(response[0].submission_date);
		$('.new-start_date').val(response[0].start_date);
		$('.new-start_time').val(response[0].start_time);
		$('.new-end_time').val(response[0].end_time);
		$('.new-total_days').val(response[0].total_days);
		$('.new-permission_remarks').val(response[0].permission_remarks);
		$('.new-employee_id').val(response[0].employee_id);
		$('.new-permission_status_id').val(response[0].permission_status_id);

		Ncalc_def();
		$('#AddNewModal .modalHeader h1').text('View Permission Details');
		$('#addNewPermissionBtn').hide();

		showModal('AddNewModal');
	}
	function changePermissionStt(permission_id) {
		//data[i]["permission_id"]
		resetForm('changeStatusForm');
		$('.edit-permission_id').val(permission_id);

		showModal('changeStatusModal');
	}

	function addNewPermission() {
		$('#AddNewModal .modalHeader h1').text('Add New Permission');
		resetForm('AddNewForm');
		$('.new-permission_id').val(1);
		$('.new-submission_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewPermissionBtn').show();
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
		<h1><?= lang("View_Details", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="changeStatusForm">


			<input type="hidden" class="inputer edit-permission_id" data-name="permission_id" data-den="" data-req="1"
				value="0" data-type="hidden">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Permission_Status", "AAR"); ?></label>
					<select class="inputer new-permission_status_id" data-name="permission_status_id" data-den="0"
						data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `permission_status_id`, `permission_status_name` FROM  `hr_employees_permissions_status` ORDER BY `permission_status_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$idd = (int) $SEL_REC['permission_status_id'];
								$nnn = $SEL_REC['permission_status_name'];
								?>
								<option value="<?= $idd; ?>"><?= $nnn; ?></option>
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
<div class="modal modal-lg" id="AddNewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="AddNewForm">

			<input type="hidden" class="inputer new-permission_id" data-name="permission_id" data-den="" data-req="1"
				value="0" data-type="hidden">
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
					<label><?= lang("total_hours", "AAR"); ?></label>
					<input type="text" class="inputer new-total_hours" data-name="total_hours" data-den="" data-req="1"
						data-type="date" readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_time", "AAR"); ?></label>

					<select class="inputer new-start_time" data-name="start_time" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected>--- Please Select---</option>
						<?php
						for ($i = 0; $i <= 24; $i++) {
							$Hour = $i . ':00';
							if ($i < 10) {
								$Hour = '0' . $i . ':00';
							}

							?>
							<option value="<?= $Hour; ?>:00"><?= $Hour; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_time", "AAR"); ?></label>

					<select class="inputer new-end_time" data-name="end_time" data-den="0" data-req="1"
						data-type="text">

						<option value="0" selected>--- Please Select---</option>
						<?php
						for ($i = 0; $i <= 24; $i++) {
							$Hour = $i . ':00';
							if ($i < 10) {
								$Hour = '0' . $i . ':00';
							}
							?>
							<option value="<?= $Hour; ?>:00"><?= $Hour; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->



			<script>
				function Ncalc_def() {
					var a1 = '' + $('.new-start_time').val();
					var a2 = '' + $('.new-end_time').val();
					if (a1 != '' && a2 != '') {

						var time1 = a1.split(':');
						var time2 = a2.split(':');

						var hours1 = parseInt(time1[0], 10),
							hours2 = parseInt(time2[0], 10),
							mins1 = parseInt(time1[1], 10),
							mins2 = parseInt(time2[1], 10);
						var hours = hours2 - hours1, mins = 0;
						if (hours <= 0) hours = 24 + hours;
						if (mins2 >= mins1) {
							mins = mins2 - mins1;
						}
						else {
							mins = (mins2 + 60) - mins1;
							hours--;
						}
						mins = mins / 60; // take percentage in 60
						hours += mins;
						hours = hours.toFixed(0);
						if (isNaN(hours)) {
							hours = 0;
						}
						$('.new-total_hours').val(hours);
					}
				}


				$('.new-start_time').on('change', function () {
					Ncalc_def();
				});
				$('.new-end_time').on('change', function () {
					Ncalc_def();
				});

			</script>






			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer new-permission_remarks" data-name="permission_remarks"
						data-den="" data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewPermissionBtn">
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
