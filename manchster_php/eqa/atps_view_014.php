<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_008';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$tab_id = (isset($_GET['tab_id'])) ? (int) test_inputs($_GET['tab_id']) : 0;


$atp_ref = "";
$atp_name = "";
$atp_name_ar = "";
$contact_name = "";
$atp_email = "";
$atp_phone = "";
$emirate_id = 0;
$iqc_noc = "";
$accreditation_type = 0;
$atp_type_id = 0;
$added_by = 0;
$added_date = "";
$status_id = 0;
$is_draft = 0;
$stage_no = 0;
$phase_id = 0;
$is_phase_ok = 0;
$todo_id = 0;
$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);

if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$atp_id = (int) $atps_list_DATA['atp_id'];
	$atp_ref = $atps_list_DATA['atp_ref'];
	$atp_name = $atps_list_DATA['atp_name'];
	$atp_name_ar = $atps_list_DATA['atp_name_ar'];
	$contact_name = $atps_list_DATA['contact_name'];
	$atp_email = $atps_list_DATA['atp_email'];
	$atp_phone = $atps_list_DATA['atp_phone'];
	$emirate_id = (int) $atps_list_DATA['emirate_id'];
	$iqc_noc = $atps_list_DATA['iqc_noc'];
	$accreditation_type = (int) $atps_list_DATA['accreditation_type'];
	$atp_type_id = (int) $atps_list_DATA['atp_type_id'];
	$added_by = (int) $atps_list_DATA['added_by'];
	$added_date = $atps_list_DATA['added_date'];

}








$eqa008_id = 0;
$visit_date = "";
$visit_type = "";
$activity = "";
$visit_length = 0;
$visit_scope = "";
$visit_agenda = "";
$visit_comment = "";
$qu_eqa_008_sel = "SELECT * FROM  `eqa_008` WHERE `atp_id` = $atp_id";
$qu_eqa_008_EXE = mysqli_query($KONN, $qu_eqa_008_sel);
if (mysqli_num_rows($qu_eqa_008_EXE)) {
	$eqa_008_DATA = mysqli_fetch_assoc($qu_eqa_008_EXE);
	$eqa008_id = (int) $eqa_008_DATA['eqa008_id'];
	$visit_date = "" . $eqa_008_DATA['visit_date'];
	$visit_type = "" . $eqa_008_DATA['visit_type'];
	$activity = "" . $eqa_008_DATA['activity'];
	$visit_length = (int) $eqa_008_DATA['visit_length'];
	$visit_scope = "" . $eqa_008_DATA['visit_scope'];
	$visit_agenda = "" . $eqa_008_DATA['visit_agenda'];
	$visit_comment = "" . $eqa_008_DATA['visit_comment'];
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


		<?php
		$props = [
			'dataType' => 'hidden',
			'dataName' => 'eqa008_id',
			'dataDen' => '',
			'dataValue' => $eqa008_id,
			'dataClass' => 'inputer',
			'isDisabled' => 0,
			'isReadOnly' => 0,
			'isRequired' => '1'
		];
		echo build_input(...$props);
		?>





		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Internal-ATP Approval Site Inspection Checklist'); ?></h2>

			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
			<?php
			$sed_1 = "";
			$sed_3 = "";

			$qu_atps_sed_form_sel = "SELECT `sed_1`, `sed_3` FROM  `atps_sed_form` WHERE `atp_id` = $atp_id";
			$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
			$atps_sed_form_DATA;
			if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
				$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);
				$sed_1 = $atps_sed_form_DATA['sed_1'];
				$sed_3 = $atps_sed_form_DATA['sed_3'];

			}

			?>
			<div class="formGroup formGroup-50">
				<label><?= lang("Institue_Name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'atp_name',
					'dataDen' => '',
					'dataValue' => $atp_name,
					'dataClass' => 'inputer atp_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>
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
				<label><?= lang("Date of Visit"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'visit_date',
					'dataDen' => '',
					'dataValue' => $visit_date,
					'dataClass' => 'inputer visit_date has_date',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>


			<div class="formGroup formGroup-50">
				<label><?= lang("contact_name"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'contact_name',
					'dataDen' => '',
					'dataValue' => $contact_name,
					'dataClass' => 'inputer contact_name ',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1',
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
			<h2 class="formPanelTitle"><?= lang('Site Inspection Checklist'); ?></h2>
			<div class="mainStandards">

				<div class="mainView">
					<i class="fa-solid fa-person-shelter"></i>
					<h1>Premises Safety</h1>
					<a class="fillBtn" onclick="showModal('Premises_Safety');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-briefcase"></i>
					<h1>Learners Reception</h1>
					<a class="fillBtn" onclick="showModal('Learners_Reception');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-user"></i>
					<h1>Learners Facilities</h1>
					<a class="fillBtn" onclick="showModal('Learners_Facilities');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-hand-holding-droplet"></i>
					<h1>Health and Hygiene</h1>
					<a class="fillBtn" onclick="showModal('Health_and_Hygiene');"><?= lang("View", "AAR"); ?></a>
				</div>

				<div class="mainView">
					<i class="fa-solid fa-screwdriver-wrench"></i>
					<h1>Equipment</h1>
					<a class="fillBtn" onclick="showModal('Equipment');"><?= lang("View", "AAR"); ?></a>
				</div>
			</div>
		</div>
		<!-- END OF FORM PANEL -->







		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Visit Questions'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<table border="1" style="width: 100%;">

				<thead>
					<tr>

						<th style="width:50%;">Question</th>
						<th style="width:45%;">Answer</th>
						<th style="width:30%;"></th>
					</tr>
				</thead>
				<tbody id="staffBody">

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
		</div>
		<!-- END OF FORM PANEL -->


		<script>
			var rowsCount = 100;

			function remRow(idd) {
				$('#row-' + idd).remove();
				rowsCount++;
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


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#staffBody').append(nwTr);
				rowsCount++;
				initForms();
			}


			addRow_staff(0);
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
				?>
				<div class="saveBtn actionBtn" onclick="saveFormChanges('<?= $pageController; ?>');">
					<i class="fa-solid fa-arrow-right"></i>
					<span class="label"><?= lang("Save"); ?></span>
				</div>
				<?php
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
$thsModal = 'Premises_Safety';
$thsModalTitle = lang("Premises_Safety", "AAR");
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
							<th style="width:50%;">A - Premises Safety</th>
							<th>Criteria</th>
							<th>---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_014_temp_sel = "SELECT * FROM  `014_temp` WHERE `record_type` = 'A'";
						$qu_014_temp_EXE = mysqli_query($KONN, $qu_014_temp_sel);
						if (mysqli_num_rows($qu_014_temp_EXE)) {
							while ($s014_temp_REC = mysqli_fetch_assoc($qu_014_temp_EXE)) {
								$record_id = (int) $s014_temp_REC['record_id'];
								$record_data = $s014_temp_REC['record_data'];
								?>

								<tr>
									<td><?= $record_data; ?></td>

									<td>
										<div class="formGroup formGroup-100">
											<select data-name="eqa_criterias[]" data-type="text" data-den="100" data-req="1"
												class="inputer eqa_criteria">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>

											</select>
										</div>
										<div class="formGroup formGroup-100">
											<?php
											$props = [
												'dataType' => 'text',
												'dataName' => 'eqa_feedbacks[]',
												'dataDen' => '',
												'dataValue' => '',
												'dataClass' => 'inputer',
												'extraAttributes' => 'rows="8"',
												'isDisabled' => 0,
												'isReadOnly' => 0,
												'isRequired' => '1'
											];
											echo build_textarea(...$props);
											?>
										</div>
									</td>
									<td>
										<a onclick="save006Entry();"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
									</td>
								</tr>
								<?php
							}
						}

						?>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'Learners_Reception';
$thsModalTitle = lang("Learners_Reception", "AAR");
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
							<th style="width:50%;">B - Learners Reception Area</th>
							<th>Criteria</th>
							<th>---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_014_temp_sel = "SELECT * FROM  `014_temp` WHERE `record_type` = 'B'";
						$qu_014_temp_EXE = mysqli_query($KONN, $qu_014_temp_sel);
						if (mysqli_num_rows($qu_014_temp_EXE)) {
							while ($s014_temp_REC = mysqli_fetch_assoc($qu_014_temp_EXE)) {
								$record_id = (int) $s014_temp_REC['record_id'];
								$record_data = $s014_temp_REC['record_data'];
								?>

								<tr>
									<td><?= $record_data; ?></td>

									<td>
										<div class="formGroup formGroup-100">
											<select data-name="eqa_criterias[]" data-type="text" data-den="100" data-req="1"
												class="inputer eqa_criteria">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>

											</select>
										</div>
										<div class="formGroup formGroup-100">
											<?php
											$props = [
												'dataType' => 'text',
												'dataName' => 'eqa_feedbacks[]',
												'dataDen' => '',
												'dataValue' => '',
												'dataClass' => 'inputer',
												'extraAttributes' => 'rows="8"',
												'isDisabled' => 0,
												'isReadOnly' => 0,
												'isRequired' => '1'
											];
											echo build_textarea(...$props);
											?>
										</div>
									</td>
									<td>
										<a onclick="save006Entry();"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
									</td>
								</tr>
								<?php
							}
						}

						?>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'Learners_Facilities';
$thsModalTitle = lang("Learners_Facilities", "AAR");
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
							<th style="width:50%;">C - Training Rooms & Learner Facilities</th>
							<th>Criteria</th>
							<th>---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_014_temp_sel = "SELECT * FROM  `014_temp` WHERE `record_type` = 'C'";
						$qu_014_temp_EXE = mysqli_query($KONN, $qu_014_temp_sel);
						if (mysqli_num_rows($qu_014_temp_EXE)) {
							while ($s014_temp_REC = mysqli_fetch_assoc($qu_014_temp_EXE)) {
								$record_id = (int) $s014_temp_REC['record_id'];
								$record_data = $s014_temp_REC['record_data'];
								?>

								<tr>
									<td><?= $record_data; ?></td>

									<td>
										<div class="formGroup formGroup-100">
											<select data-name="eqa_criterias[]" data-type="text" data-den="100" data-req="1"
												class="inputer eqa_criteria">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>

											</select>
										</div>
										<div class="formGroup formGroup-100">
											<?php
											$props = [
												'dataType' => 'text',
												'dataName' => 'eqa_feedbacks[]',
												'dataDen' => '',
												'dataValue' => '',
												'dataClass' => 'inputer',
												'extraAttributes' => 'rows="8"',
												'isDisabled' => 0,
												'isReadOnly' => 0,
												'isRequired' => '1'
											];
											echo build_textarea(...$props);
											?>
										</div>
									</td>
									<td>
										<a onclick="save006Entry();"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
									</td>
								</tr>
								<?php
							}
						}

						?>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'Health_and_Hygiene';
$thsModalTitle = lang("Health_and_Hygiene", "AAR");
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
							<th style="width:50%;">D - Health & Hygiene</th>
							<th>Criteria</th>
							<th>---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_014_temp_sel = "SELECT * FROM  `014_temp` WHERE `record_type` = 'D'";
						$qu_014_temp_EXE = mysqli_query($KONN, $qu_014_temp_sel);
						if (mysqli_num_rows($qu_014_temp_EXE)) {
							while ($s014_temp_REC = mysqli_fetch_assoc($qu_014_temp_EXE)) {
								$record_id = (int) $s014_temp_REC['record_id'];
								$record_data = $s014_temp_REC['record_data'];
								?>

								<tr>
									<td><?= $record_data; ?></td>

									<td>
										<div class="formGroup formGroup-100">
											<select data-name="eqa_criterias[]" data-type="text" data-den="100" data-req="1"
												class="inputer eqa_criteria">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>

											</select>
										</div>
										<div class="formGroup formGroup-100">
											<?php
											$props = [
												'dataType' => 'text',
												'dataName' => 'eqa_feedbacks[]',
												'dataDen' => '',
												'dataValue' => '',
												'dataClass' => 'inputer',
												'extraAttributes' => 'rows="8"',
												'isDisabled' => 0,
												'isReadOnly' => 0,
												'isRequired' => '1'
											];
											echo build_textarea(...$props);
											?>
										</div>
									</td>
									<td>
										<a onclick="save006Entry();"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
									</td>
								</tr>
								<?php
							}
						}

						?>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>

<?php
$thsModal = 'Equipment';
$thsModalTitle = lang("Equipment", "AAR");
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
							<th style="width:50%;">E - Equipment </th>
							<th>Criteria</th>
							<th>---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_014_temp_sel = "SELECT * FROM  `014_temp` WHERE `record_type` = 'E'";
						$qu_014_temp_EXE = mysqli_query($KONN, $qu_014_temp_sel);
						if (mysqli_num_rows($qu_014_temp_EXE)) {
							while ($s014_temp_REC = mysqli_fetch_assoc($qu_014_temp_EXE)) {
								$record_id = (int) $s014_temp_REC['record_id'];
								$record_data = $s014_temp_REC['record_data'];
								?>

								<tr>
									<td><?= $record_data; ?></td>

									<td>
										<div class="formGroup formGroup-100">
											<select data-name="eqa_criterias[]" data-type="text" data-den="100" data-req="1"
												class="inputer eqa_criteria">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1"><?= lang("YES"); ?></option>
												<option value="0"><?= lang("NO"); ?></option>

											</select>
										</div>
										<div class="formGroup formGroup-100">
											<?php
											$props = [
												'dataType' => 'text',
												'dataName' => 'eqa_feedbacks[]',
												'dataDen' => '',
												'dataValue' => '',
												'dataClass' => 'inputer',
												'extraAttributes' => 'rows="8"',
												'isDisabled' => 0,
												'isReadOnly' => 0,
												'isRequired' => '1'
											];
											echo build_textarea(...$props);
											?>
										</div>
									</td>
									<td>
										<a onclick="save006Entry();"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
									</td>
								</tr>
								<?php
							}
						}

						?>
					</tbody>
				</table>
				<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

				<div class="zero"></div>
			</form>
			<div class="modalAction">
				<div class="modalActionBtns">
					<button type="button" onclick="closeModal();"><?= lang("Close", "إلغاء"); ?></button>
				</div>
				<div class="modalActionResults form-alerts"></div>
			</div>
		</div>
	</div>
</div>