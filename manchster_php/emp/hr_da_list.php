<?php
$page_title = $page_description = $page_keywords = $page_author = lang("DA_List");




$pageDataController = $POINTER . "get_hr_da_list";
$addNewController = $POINTER . 'add_hr_da_list';
$updateController = $POINTER . 'update_hr_da_list';



$pageId = 55006;
$subPageId = 400;
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
		<a class="pageLink" onclick="addNewDA();">
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
				<div class="th"><?= lang("added_date"); ?></div>
				<div class="th"><?= lang("warning"); ?></div>
				<div class="th"><?= lang("type"); ?></div>
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

			var stt = getInt(data[i]["da_status_id"]);


			btns += '<a onclick="getData(' + data[i]["da_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("View"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["da_id"] + '">' +
				'	<div class="td row-da_id">' + data[i]["da_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
				'	<div class="td row-da_warning">' + data[i]["da_warning"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["da_type"] + '</div>' +
				'	<div class="td row-end_date">' + data[i]["da_status"] + '</div>' +
				'	<div class="td row-leave_remarks">' + data[i]["da_remark"] + '</div>' +
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
	function getData(da_id) {

		if (da_id != 0) {
			//get data from server
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $pageDataController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "da_id": da_id },
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
		//data[i]["da_id"]
		resetForm('AddNewForm');

		$('.new-da_id').val(response[0].da_id);
		$('.new-da_warning_id').val(response[0].da_warning_id);
		$('.new-da_remark').val(response[0].da_remark);
		$('.new-da_type_id').val(response[0].da_type_id);
		$('.new-employee_id').val(response[0].employee_id);
		$('.new-da_status_id').val(response[0].da_status_id);
		$('.new-added_by').val(response[0].added_by);
		$('.new-added_date').val(response[0].added_date);


		$('#AddNewModal .modalHeader h1').text('View DA Details');
		$('#addNewDABtn').hide();

		showModal('AddNewModal');
	}


	function addNewDA() {
		$('#AddNewModal .modalHeader h1').text('Add New DA');
		resetForm('AddNewForm');
		$('.new-da_id').val(1);
		$('.new-added_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewDABtn').show();
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
<div class="modal modal-lg" id="AddNewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="AddNewForm">

			<input type="hidden" class="inputer new-da_id" data-name="da_id" data-den="" data-req="1" value="0"
				data-type="hidden">

			<input type="hidden" class="inputer new-employee_id" data-name="employee_id" data-den="" data-req="1"
				value="<?= $USER_ID; ?>" data-type="hidden">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Added_date", "AAR"); ?></label>
					<input type="text" class="inputer new-added_date" data-name="added_date" data-den="" data-req="0"
						value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Action By Employee", "AAR"); ?></label>
					<select class="inputer new-da_type_id" data-name="da_type_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `da_type_id`,`da_type_code`, `da_type_text` FROM  `hr_disp_actions_types` ORDER BY `da_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$da_type_id = (int) $SEL_REC['da_type_id'];
								$da_type_code = $SEL_REC['da_type_code'];
								$da_type_text = $SEL_REC['da_type_text'];
								?>
								<option value="<?= $da_type_id; ?>"><?= $da_type_code; ?> - <?= $da_type_text; ?></option>
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
					<label><?= lang("Warning", "AAR"); ?></label>
					<select class="inputer new-da_warning_id" data-name="da_warning_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `da_warning_id`,`da_warning_name` FROM  `hr_disp_actions_warnings` ORDER BY `da_warning_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$da_warning_id = (int) $SEL_REC['da_warning_id'];
								$da_warning_name = $SEL_REC['da_warning_name'];
								?>
								<option value="<?= $da_warning_id; ?>"><?= $da_warning_name; ?></option>
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
					<textarea type="text" class="inputer new-da_remark" data-name="da_remark" data-den="" data-req="1"
						data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewDABtn">
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
