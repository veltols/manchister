<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Attendance_List");



//hr_attendance
$pageDataController = $POINTER . "get_hr_attendance_list";
$addNewController = $POINTER . 'add_hr_attendance_list';
$updateController = $POINTER . 'update_hr_attendance_list';



$pageId = 55006;
$subPageId = 500;
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
	<?php
	/*	
	<a class="pageLink" onclick="addNewAttendance();">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("Add_New"); ?></div>
		</a>
		*/
		?>
	</div>
</div>




<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Employee_name"); ?></div>
				<div class="th"><?= lang("checkin_date"); ?></div>
				<div class="th"><?= lang("checkin_time"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
				<div class="th"><?= lang("added_date"); ?></div>
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


			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["attendance_id"] + '">' +
				'	<div class="td row-attendance_id">' + data[i]["attendance_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-checkin_date">' + data[i]["checkin_date"] + '</div>' +
				'	<div class="td row-checkin_time">' + data[i]["checkin_time"] + '</div>' +
				'	<div class="td row-attendance_remarks">' + data[i]["attendance_remarks"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
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


	function addNewAttendance() {
		$('#AddNewModal .modalHeader h1').text('Add New Attendance');
		resetForm('AddNewForm');
		$('.new-attendance_id').val(1);
		$('.new-added_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewAttendanceBtn').show();
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

			<input type="hidden" class="inputer new-attendance_id" data-name="attendance_id" data-den="" data-req="1"
				value="0" data-type="hidden">


			<input type="hidden" class="inputer new-employee_id" data-name="employee_id" data-den="" data-req="1"
				value="<?= $USER_ID; ?>" data-type="hidden">


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("added_date", "AAR"); ?></label>
					<input type="text" class="inputer new-added_date" data-name="added_date" data-den="" data-req="0"
						value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("checkin_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date new-checkin_date" data-name="checkin_date" data-den=""
						data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("checkin_time", "AAR"); ?></label>

					<select class="inputer new-checkin_time" data-name="checkin_time" data-den="0" data-req="1"
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
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer new-attendance_remarks" data-name="attendance_remarks"
						data-den="" data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewAttendanceBtn">
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
