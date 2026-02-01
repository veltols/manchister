<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_007';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$tab_id = (isset($_GET['tab_id'])) ? (int) test_inputs($_GET['tab_id']) : 0;


$added_date = "";
$submitted_date = "";
$is_submitted = 0;
$form_status = "";
$qu_atps_sed_form_sel = "SELECT * FROM  `atps_sed_form` WHERE `atp_id` = $atp_id";
$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
	$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);
	$added_date = "" . $atps_sed_form_DATA['added_date'];
	$submitted_date = "" . $atps_sed_form_DATA['submitted_date'];
	$is_submitted = (int) $atps_sed_form_DATA['is_submitted'];
	$form_status = "" . $atps_sed_form_DATA['form_status'];
}


$pageId = 500;
$asideIsHidden = true;
include("app/assets.php");
?>


<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->



	<!-- START OF FORMER COL -->
	<div class="former">


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Evidence Collection Log'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
			<?php
			$sed_1 = "";
			$sed_2 = "";
			$sed_3 = "";
			$submitted_date = "";

			$qu_atps_sed_form_sel = "SELECT `sed_1`, `sed_2`, `sed_3`, `submitted_date` FROM  `atps_sed_form` WHERE `atp_id` = $atp_id";
			$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
			$atps_sed_form_DATA;
			if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
				$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);
				$sed_1 = "" . $atps_sed_form_DATA['sed_1'];
				$sed_2 = "" . $atps_sed_form_DATA['sed_2'];
				$sed_3 = "" . $atps_sed_form_DATA['sed_3'];
				$submitted_date = "" . $atps_sed_form_DATA['submitted_date'];

			}

			?>
			<div class="formGroup formGroup-50">
				<label><?= lang("Author of SED"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_1',
					'dataDen' => '',
					'dataValue' => $sed_1,
					'dataClass' => 'inputer sed_1',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-50">
				<label><?= lang("Role"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_2',
					'dataDen' => '',
					'dataValue' => $sed_2,
					'dataClass' => 'inputer sed_2',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-50">
				<label><?= lang("Date of last College Internal Audit"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'sed_3',
					'dataDen' => '',
					'dataValue' => $sed_3,
					'dataClass' => 'inputer sed_3 has_date',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-50">
				<label><?= lang("submitted_date"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'submitted_date',
					'dataDen' => '',
					'dataValue' => $submitted_date,
					'dataClass' => 'inputer submitted_date has_date',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>

			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Form_Details'); ?></h2>
			<div class="mainStandards">

				<div class="mainView">
					<i class="fa-solid fa-briefcase"></i>
					<h1>Areas to Review</h1>
					<a class="fillBtn" onclick="showModal('areasToReviewModal');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-people-roof"></i>
					<h1>Staff Interview</h1>
					<a class="fillBtn" onclick="showModal('staffInterview');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-user"></i>
					<h1>Lead IQA Interview</h1>
					<a class="fillBtn" onclick="showModal('leadIqaInterview');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-chalkboard-user"></i>
					<h1>Trainers & Assessors Interview</h1>
					<a class="fillBtn" onclick="showModal('trainersInterview');"><?= lang("View", "AAR"); ?></a>
				</div>
			</div>
		</div>
		<!-- END OF FORM PANEL -->



		<?php
		$props = [
			'dataType' => 'hidden',
			'dataName' => 'atp_id',
			'dataDen' => '0',
			'dataValue' => $atp_id,
			'dataClass' => 'inputer',
			'isDisabled' => 0,
			'isReadOnly' => 0,
			'isRequired' => '1'
		];
		echo build_input(...$props);
		?>

		<script>
			var rowsCount = 100;

			function remRow(idd) {
				$('#row-' + idd).remove();
				rowsCount++;
			}

			function addRow_0(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="record_ids[]" value="' + recId + '" class="inputer" data-name="areas[]" data-req="1" data-type="int" data-den="">' +

					'<td><textarea style="width:100%;" rows="8" class="inputer" data-name="a1s[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
					'<td>' +
					'	<div class="formGroup formGroup-100">' +
					'		<select class="inputer"  data-name="a2s[]" data-req="1" data-type="text" data-den="100">' +
					'			<option value="100" selected><?= lang("Please_Select"); ?></option>' +
					'			<option value="1"><?= lang("YES"); ?></option>' +
					'			<option value="0"><?= lang("NO"); ?></option>' +
					'		</select>' +
					'	</div>' +
					'</td>' +
					'<td><textarea style="width:100%;" rows="8" class="inputer" data-name="a3s[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#areasBody').append(nwTr);
				rowsCount++;
				initForms();
			}

			function addRow_staff(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="interview_ids[]" value="' + recId + '" class="inputer" data-name="interview_ids[]" data-req="1" data-type="int" data-den="">' +


					'<td>' +
					'	<div class="formGroup formGroup-100">' +
					'		<input type="text" class="inputer" data-name="staff_names[]" data-req="1" data-type="text" data-den="">' +
					'	</div>' +
					'</td>' +
					'<td>' +
					'	<div class="formGroup formGroup-100">' +
					'		<input type="text" class="inputer" data-name="staff_roles[]" data-req="1" data-type="text" data-den="">' +
					'	</div>' +
					'</td>' +

					'<td><textarea style="width:100%;" rows="8" class="inputer" data-name="eqa_comments[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#staffBody').append(nwTr);
				rowsCount++;
				initForms();
			}
			function addRow_iqa(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="iqa_ids[]" value="' + recId + '" class="inputer" data-name="iqa_ids[]" data-req="1" data-type="int" data-den="">' +

					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="iqa_questions[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="iqa_answers[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#iqaBody').append(nwTr);
				rowsCount++;
				initForms();
			}

			function addRow_train(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="train_ids[]" value="' + recId + '" class="inputer" data-name="train_ids[]" data-req="1" data-type="int" data-den="">' +

					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_answers[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#trainBody').append(nwTr);
				rowsCount++;
				initForms();
			}

		</script>

	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">

			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=<?= $tab_id; ?>" class="backBtn actionBtn"
				id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>
			<?php
			if (1 == 1) {
				/*
													?>
													<div class="saveBtn actionBtn" onclick="saveFormChanges('<?= $pageController; ?>');">
														<i class="fa-solid fa-save"></i>
														<span class="label"><?= lang("Save"); ?></span>
													</div>
													<?php
													*/
			}
			?>


		</div>

	</div>





	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->



<script>
	function actionForm(atpId, ops) {
		var aa = confirm("Are you sure?");
		var rc_comment = $('#rc_comment').val();
		if (aa == true) {
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $POINTER; ?>approve_atp_form',
					dataType: "JSON",
					method: "POST",
					data: { "form": 'sed_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
					success: function (RES) {

						var itm = RES[0];
						activeRequest = false;
						end_loader('article');
						makeSiteAlert('suc', '<?= lang("Data Saved"); ?>');
						setTimeout(function () {
							window.location.href = '<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>';
						}, 1000);
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


	function showRev() {
		$('#rc_comment').toggle();
		$('#revSubimt').toggle();
	}




</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
include("../public/app/footer_modal.php");
?>




<?php
$thsModal = 'areasToReviewModal';
$thsModalTitle = lang("Areas_To_Review", "AAR");
?>
<div id="<?= $thsModal; ?>" class="modal">
	<div class="modalData">
		<div class="modalHeader">
			<h1><span id="modalAction"><?= $thsModalTitle; ?></span></h1>
		</div>
		<div class="modalBody">

			<form class="modalForm row" id="edit<?= $thsModal; ?>Form" id-modal="<?= $thsModal; ?>"
				contoller="<?= $POINTER . 'add_leads_notes'; ?>">
				<input name="atp_id" value="<?= $atp_id; ?>" type="hidden" class="data-input" req="1" denier="">
				<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
				<table border="1" style="width: 100%;">
					<thead>
						<tr>

							<th style="width:45%;">Evidence</th>
							<th style="width:20%;">Met Criteria</th>
							<th style="width:30%;">Explanation</th>
							<th style="width:30%;"></th>
						</tr>
					</thead>
					<tbody id="areasBody">
						<?php
						$rowsCountAreas = 1500;
						$qu_eqa_007_areas_sel = "SELECT * FROM  `eqa_007_areas` WHERE `atp_id` = $atp_id ORDER BY `record_id` ASC";
						$qu_eqa_007_areas_EXE = mysqli_query($KONN, $qu_eqa_007_areas_sel);
						if (mysqli_num_rows($qu_eqa_007_areas_EXE)) {
							while ($eqa_007_areas_REC = mysqli_fetch_assoc($qu_eqa_007_areas_EXE)) {
								$record_id = (int) $eqa_007_areas_REC['record_id'];
								$a1 = $eqa_007_areas_REC['a1'];
								$a2 = $eqa_007_areas_REC['a2'];
								$a3 = $eqa_007_areas_REC['a3'];
								$added_by = (int) $eqa_007_areas_REC['added_by'];
								$added_date = $eqa_007_areas_REC['added_date'];

								$rowsCountAreas++;
								?>
								<tr id="row-<?= $rowsCountAreas; ?>">
									<input type="hidden" name="areas[]" value="<?= $record_id; ?>" class="inputer"
										data-name="areas[]" data-req="1" data-type="int" data-den="">

									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="a1s[]" data-req="1"
											data-type="text" data-den=""><?= $a1; ?></textarea></td>
									<td>
										<div class="formGroup formGroup-100">
											<select class="inputer a2-<?= $record_id; ?>" data-name="a2s[]" data-req="1"
												data-type="text" data-den="100">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>
											</select>
										</div>
										<script>
											$('.a2-<?= $record_id; ?>').val(<?= $a2; ?>);
										</script>
									</td>
									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="a3s[]" data-req="1"
											data-type="text" data-den=""><?= $a3; ?></textarea></td>


									<td><i onclick="remRow(<?= $rowsCountAreas; ?>)"
											style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>

								</tr>

								<?php
							}
						}

						?>
					</tbody>
					<tbody>
						<tr>
							<td colspan="3"><button onclick="addRow_0(0);" type="button"
									style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
									Row</button></td>
						</tr>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button class="actionBtn" onclick="saveFacultyForm();"
						type="button"><?= lang("Save", "حفظ"); ?></button>
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>


<?php
$thsModal = 'staffInterview';
$thsModalTitle = lang("Interview_With_The_Following_Staff", "AAR");
?>
<div id="<?= $thsModal; ?>" class="modal">
	<div class="modalData">
		<div class="modalHeader">
			<h1><span id="modalAction"><?= $thsModalTitle; ?></span></h1>
		</div>
		<div class="modalBody">

			<form class="modalForm row" id="edit<?= $thsModal; ?>Form" id-modal="<?= $thsModal; ?>"
				contoller="<?= $POINTER . 'add_leads_notes'; ?>">
				<input name="atp_id" value="<?= $atp_id; ?>" type="hidden" class="data-input" req="1" denier="">

				<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



				<table border="1" style="width: 100%;">

					<thead>
						<tr>

							<th style="width:45%;">Name</th>
							<th style="width:20%;">Role</th>
							<th style="width:30%;">Comment</th>
							<th style="width:30%;"></th>
						</tr>
					</thead>
					<tbody id="staffBody">

						<?php
						$rowsCountStaff = 2000;
						$qu_eqa_007_interview_sel = "SELECT * FROM  `eqa_007_interview` WHERE ((`atp_id` = $atp_id) AND (`interview_type` = 'staff')) ORDER BY `interview_id` ASC";
						$qu_eqa_007_interview_EXE = mysqli_query($KONN, $qu_eqa_007_interview_sel);
						if (mysqli_num_rows($qu_eqa_007_interview_EXE)) {
							while ($eqa_007_interview_REC = mysqli_fetch_assoc($qu_eqa_007_interview_EXE)) {
								$interview_id = (int) $eqa_007_interview_REC['interview_id'];
								$staff_name = $eqa_007_interview_REC['staff_name'];
								$staff_role = $eqa_007_interview_REC['staff_role'];
								$eqa_comment = $eqa_007_interview_REC['eqa_comment'];
								$added_by = (int) $eqa_007_interview_REC['added_by'];
								$added_date = $eqa_007_interview_REC['added_date'];

								$rowsCountStaff++;
								?>
								<tr id="row-<?= $rowsCountStaff; ?>">
									<input type="hidden" name="interview_ids[]" value="<?= $interview_id; ?>" class="inputer"
										data-name="interview_ids[]" data-req="1" data-type="int" data-den="">

									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="staff_names[]"
											data-req="1" data-type="text" data-den=""><?= $staff_name; ?></textarea></td>

									<td><input type="text" value="<?= $staff_role; ?>" style="width:100%;" rows="8"
											class="inputer" data-name="staff_roles[]" data-req="1"
											data-value="<?= $staff_name; ?>" data-type="text" data-den=""></td>


									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="eqa_comments[]"
											data-req="1" data-type="text" data-den=""><?= $eqa_comment; ?></textarea></td>


									<td><i onclick="remRow(<?= $rowsCountStaff; ?>)"
											style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>

								</tr>

								<?php
							}
						}

						?>
					</tbody>
					<tbody>
						<tr>
							<td colspan="3"><button onclick="addRow_staff(0);" type="button"
									style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
									Row</button></td>
						</tr>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button class="actionBtn" onclick="saveFacultyForm();"
						type="button"><?= lang("Save", "حفظ"); ?></button>
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'leadIqaInterview';
$thsModalTitle = lang("Lead_IQA_Interview", "AAR");
?>
<div id="<?= $thsModal; ?>" class="modal">
	<div class="modalData">
		<div class="modalHeader">
			<h1><span id="modalAction"><?= $thsModalTitle; ?></span></h1>
		</div>
		<div class="modalBody">

			<form class="modalForm row" id="edit<?= $thsModal; ?>Form" id-modal="<?= $thsModal; ?>"
				contoller="<?= $POINTER . 'add_leads_notes'; ?>">
				<input name="atp_id" value="<?= $atp_id; ?>" type="hidden" class="data-input" req="1" denier="">

				<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
				<table border="1" style="width: 100%;">

					<thead>
						<tr>

							<th style="width:45%;">Question</th>
							<th style="width:30%;">Answer</th>
							<th style="width:30%;"></th>
						</tr>
					</thead>
					<tbody id="iqaBody">
						<?php
						$rowsCountIQA = 3000;
						$qu_eqa_007_interview_sel = "SELECT * FROM  `eqa_007_interview` WHERE ((`atp_id` = $atp_id) AND (`interview_type` = 'iqa')) ORDER BY `interview_id` ASC";
						$qu_eqa_007_interview_EXE = mysqli_query($KONN, $qu_eqa_007_interview_sel);
						if (mysqli_num_rows($qu_eqa_007_interview_EXE)) {
							while ($eqa_007_interview_REC = mysqli_fetch_assoc($qu_eqa_007_interview_EXE)) {
								$iqa_id = (int) $eqa_007_interview_REC['interview_id'];
								$question = "" . $eqa_007_interview_REC['question'];
								$answer = "" . $eqa_007_interview_REC['answer'];

								$rowsCountIQA++;
								?>
								<tr id="row-<?= $rowsCountIQA; ?>">
									<input type="hidden" name="iqa_ids[]" value="<?= $iqa_id; ?>" class="inputer"
										data-name="iqa_ids[]" data-req="1" data-type="int" data-den="">

									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="iqa_questions[]"
											data-req="1" data-type="text" data-den=""><?= $question; ?></textarea></td>


									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="iqa_answers[]"
											data-req="1" data-type="text" data-den=""><?= $answer; ?></textarea></td>


									<td><i onclick="remRow(<?= $rowsCountIQA; ?>)"
											style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>

								</tr>

								<?php
							}
						}

						?>
					</tbody>
					<tbody>
						<tr>
							<td colspan="3"><button onclick="addRow_iqa(0);" type="button"
									style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
									Row</button></td>
						</tr>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button class="actionBtn" onclick="saveFacultyForm();"
						type="button"><?= lang("Save", "حفظ"); ?></button>
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'trainersInterview';
$thsModalTitle = lang("Trainers_and_Asessors_Interview", "AAR");
?>
<div id="<?= $thsModal; ?>" class="modal">
	<div class="modalData">
		<div class="modalHeader">
			<h1><span id="modalAction"><?= $thsModalTitle; ?></span></h1>
		</div>
		<div class="modalBody">

			<form class="modalForm row" id="edit<?= $thsModal; ?>Form" id-modal="<?= $thsModal; ?>"
				contoller="<?= $POINTER . 'add_leads_notes'; ?>">
				<input name="atp_id" value="<?= $atp_id; ?>" type="hidden" class="data-input" req="1" denier="">

				<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
				<table border="1" style="width: 100%;">

					<thead>
						<tr>

							<th style="width:45%;">Question</th>
							<th style="width:30%;">Answer</th>
							<th style="width:30%;"></th>
						</tr>
					</thead>
					<tbody id="trainBody">
						<?php
						$rowsCountTrain = 4000;
						$qu_eqa_007_interview_sel = "SELECT * FROM  `eqa_007_interview` WHERE ((`atp_id` = $atp_id) AND (`interview_type` = 'train')) ORDER BY `interview_id` ASC";
						$qu_eqa_007_interview_EXE = mysqli_query($KONN, $qu_eqa_007_interview_sel);
						if (mysqli_num_rows($qu_eqa_007_interview_EXE)) {
							while ($eqa_007_interview_REC = mysqli_fetch_assoc($qu_eqa_007_interview_EXE)) {
								$train_id = (int) $eqa_007_interview_REC['interview_id'];
								$question = "" . $eqa_007_interview_REC['question'];
								$answer = "" . $eqa_007_interview_REC['answer'];

								$rowsCountTrain++;
								?>
								<tr id="row-<?= $rowsCountTrain; ?>">
									<input type="hidden" name="train_ids[]" value="<?= $train_id; ?>" class="inputer"
										data-name="train_ids[]" data-req="1" data-type="int" data-den="">

									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="train_questions[]"
											data-req="1" data-type="text" data-den=""><?= $question; ?></textarea></td>


									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="train_answers[]"
											data-req="1" data-type="text" data-den=""><?= $answer; ?></textarea></td>


									<td><i onclick="remRow(<?= $rowsCountTrain; ?>)"
											style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>

								</tr>

								<?php
							}
						}

						?>
					</tbody>
					<tbody>
						<tr>
							<td colspan="3"><button onclick="addRow_train(0);" type="button"
									style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
									Row</button></td>
						</tr>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button class="actionBtn" onclick="saveFacultyForm();"
						type="button"><?= lang("Save", "حفظ"); ?></button>
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>







































<script>
	<?php
	if ($rowsCountAreas == 1500) {
		?>
		addRow_0(0);
		<?php
	}
	?>


	<?php
	if ($rowsCountStaff == 2000) {
		?>
		addRow_staff(0);
		<?php
	}
	?>


	<?php
	if ($rowsCountIQA == 3000) {
		?>
		addRow_iqa(0);
		<?php
	}
	?>


	<?php
	if ($rowsCountTrain == 4000) {
		?>
		addRow_train(0);
		<?php
	}
	?>

</script>