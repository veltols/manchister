<?php
$page_title = $page_description = $page_keywords = $page_author = lang("ExitInterview_List");



//hr_exit_interviews
$pageDataController = $POINTER . "get_hr_exit_interviews_list";
$addNewController = $POINTER . 'add_hr_exit_interviews_list';
$updateController = $POINTER . 'update_hr_exit_interviews_list';



$pageId = 550;
$subPageId = 600;
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
		<a class="pageLink" onclick="addNewExitInterview();">
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
				<div class="th"><?= lang("Designation"); ?></div>
				<div class="th"><?= lang("interview_date"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
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
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["interview_id"] + '">' +
				'	<div class="td row-interview_id">' + data[i]["interview_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-checkin_date">' + data[i]["designation_name"] + '</div>' +
				'	<div class="td row-checkin_time">' + data[i]["interview_date"] + '</div>' +
				'	<div class="td row-interview_remarks">' + data[i]["interview_remarks"] + '</div>' +
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


	function addNewExitInterview() {
		$('#AddNewModal .modalHeader h1').text('Add New Exit Interview');
		//resetForm('AddNewForm');
		$('.new-interview_id').val(1);
		$('.new-interview_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewExitInterviewBtn').show();
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

			<input type="hidden" class="inputer new-interview_id" data-name="interview_id" data-den="" data-req="1"
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
					<label><?= lang("interview_date", "AAR"); ?></label>
					<input type="text" class="inputer new-interview_date" data-name="interview_date" data-den=""
						data-req="0" value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->




			<?php
			$qu_hr_exit_interviews_questions_sel = "SELECT * FROM  `hr_exit_interviews_questions` ORDER BY `question_id` ASC";
			$qu_hr_exit_interviews_questions_EXE = mysqli_query($KONN, $qu_hr_exit_interviews_questions_sel);
			if (mysqli_num_rows($qu_hr_exit_interviews_questions_EXE)) {
				while ($hr_exit_interviews_questions_REC = mysqli_fetch_assoc($qu_hr_exit_interviews_questions_EXE)) {
					$question_id = (int) $hr_exit_interviews_questions_REC['question_id'];
					$question_text = $hr_exit_interviews_questions_REC['question_text'];
					$question_text_ar = $hr_exit_interviews_questions_REC['question_text_ar'];
					?>

					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= $question_text; ?></label>
							<input type="hidden" class="inputer new-question_ids" data-name="question_ids[]" data-den=""
								data-req="1" value="<?= $question_id; ?>" data-type="hidden">
							<textarea type="text" class="inputer new-question_id" data-name="answer_texts[]" data-den=""
								data-req="1" data-type="text" rows="3"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->
					<?php
				}
			}

			?>





			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer new-interview_remarks" data-name="interview_remarks"
						data-den="" data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewExitInterviewBtn">
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
