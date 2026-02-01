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



		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('IQC External Quality Assurance Visit Planner'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->
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
			<h2 class="formPanelTitle"><?= lang('Visit_Details'); ?></h2>
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
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Type of Visit"); ?></label>
				<select data-name="visit_type" data-type="text" data-den="100" data-req="1" class="inputer visit_type"
					data-from="<?= $POINTER; ?>get_yes_no_select">
					<option value="100" selected><?= lang("Please_Select"); ?></option>
					<option value="1">Accreditation/Reaccreditation</option>
					<option value="11">Accreditation/Reaccreditation Approval</option>
					<option value="111">Qualification External Verification (QEV)</option>
					<option value="1111">Other</option>
				</select>
			</div>
			<script>
				$('.visit_type').val(<?= $visit_type; ?>);
			</script>

			<div class="formGroup formGroup-50">
				<label><?= lang("Activity"); ?></label>
				<select data-name="activity" data-type="text" data-den="100" data-req="1" class="inputer activity"
					data-from="<?= $POINTER; ?>get_yes_no_select">
					<option value="100" selected><?= lang("Please_Select"); ?></option>
					<option value="1">Site Visit</option>
					<option value="11">Remote</option>
				</select>
			</div>
			<script>
				$('.activity').val(<?= $activity; ?>);
			</script>

			<div class="formGroup formGroup-50">
				<label><?= lang("Visit_Length"); ?> ( Hours )</label>
				<select data-name="visit_length" data-type="text" data-den="100" data-req="1"
					class="inputer visit_length" data-from="<?= $POINTER; ?>get_yes_no_select">
					<option value="100" selected><?= lang("Please_Select"); ?></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
				</select>
			</div>

			<script>
				$('.visit_length').val(<?= $visit_length; ?>);
			</script>
			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->








		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Visit_Plan'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->


			<div class="formGroup formGroup-100">
				<label><?= lang("Scope_Of_Visit"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => $visit_scope,
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>

			<div class="formGroup formGroup-100">
				<label><?= lang("Agenda"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_agenda',
					'dataDen' => '',
					'dataValue' => $visit_agenda,
					'dataClass' => 'inputer visit_agenda',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>

			<div class="formGroup formGroup-100">
				<label><?= lang("Additional Details"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_comment',
					'dataDen' => '',
					'dataValue' => $visit_comment,
					'dataClass' => 'inputer visit_comment',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>

			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->




		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Qualification(s) Offered'); ?></h2>


			<div class="dataContainer" id="listData">

				<?php

				$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE `atp_id` = $atp_id";
				$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
				if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
					while ($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)) {
						$qualification_id = (int) $atps_list_qualifications_REC['qualification_id'];
						$qualification_code = "" . $atps_list_qualifications_REC['qualification_code'];
						$qualification_name = "" . $atps_list_qualifications_REC['qualification_name'];
						$qualification_provider = "" . $atps_list_qualifications_REC['qualification_provider'];
						$qualification_type = "" . $atps_list_qualifications_REC['qualification_type'];
						$learners_entry_no = (int) $atps_list_qualifications_REC['learners_entry_no'];
						$academic_session = "" . $atps_list_qualifications_REC['academic_session'];

						?>


						<div class="dataElement data-list-view" id="q-<?= $qualification_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("Name"); ?></div>
								<div class="value"><?= $qualification_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Provider"); ?></div>
								<div class="value"><?= $qualification_provider; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Type"); ?></div>
								<div class="value"><?= $qualification_type; ?></div>
							</div>
						</div>

						<?php
					}
				}

				?>
			</div>

		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->


















		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Faculty_Details'); ?></h2>
			<div class="dataContainer" id="listData">

				<?php


				$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE `atp_id` = $atp_id";
				$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
				if (mysqli_num_rows($qu_atps_list_faculties_EXE)) {
					while ($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)) {
						$faculty_id = (int) $atps_list_faculties_REC['faculty_id'];
						$faculty_name = "" . $atps_list_faculties_REC['faculty_name'];
						$faculty_type = "" . $atps_list_faculties_REC['faculty_type'];
						$faculty_spec = "" . $atps_list_faculties_REC['faculty_spec'];
						$faculty_cv = "" . $atps_list_faculties_REC['faculty_cv'];
						$faculty_cert = "";

						$thsType = '';
						$dbType = (int) $atps_list_faculties_REC['faculty_type'];
						if ($dbType == 1) {
							$thsType = 'IQA';
						} else if ($dbType == 2) {
							$thsType = 'IQA_Lead';
						} else if ($dbType == 3) {
							$thsType = 'Assessor';
						} else if ($dbType == 4) {
							$thsType = 'Trainer';
						} else if ($dbType == 5) {
							$thsType = 'Teacher';
						}

						?>


						<div class="dataElement data-list-view" id="f-<?= $faculty_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("Name"); ?></div>
								<div class="value"><?= $faculty_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Type"); ?></div>
								<div class="value"><?= $thsType; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Specialization"); ?></div>
								<div class="value"><?= $faculty_spec; ?></div>
							</div>
							<div class="dataView col-2">
								<div class="property"><?= lang("CV"); ?></div>
								<?php
								if ($faculty_cv != '') {
									?>
									<div class="value"><a href="../<?= $POINTER; ?>uploads/<?= $faculty_cv; ?>" target="_blank">View
											CV</a></div>
									<?php
								} else {
									?>
									<div class="value">NA</div>
									<?php
								}
								?>
							</div>
							<div class="dataView col-2">
								<div class="property"><?= lang("Certificates"); ?></div>
								<?php
								if ($faculty_cert != '') {
									?>
									<div class="value"><a href="../<?= $POINTER; ?>uploads/<?= $faculty_cert; ?>"
											target="_blank">View
											Certifications</a></div>
									<?php
								} else {
									?>
									<div class="value">NA</div>
									<?php
								}
								?>
							</div>
						</div>

						<?php
					}
				}

				?>
			</div>
		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->





































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
?>

<script>


</script>