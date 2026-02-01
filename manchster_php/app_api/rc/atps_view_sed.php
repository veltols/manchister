<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;

$atp_logo = "no-img.png";
$atpName = "no-img.png";
$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$atpName = $atps_list_DATA['atp_name'];
	$atp_logo = $atps_list_DATA['atp_logo'];
}


$added_date = "";
$submitted_date = "";
$is_submitted = 0;
$form_status = "";
$qu_atps_sed_form_sel = "SELECT * FROM  `atps_sed_form` WHERE `atp_id` = $atp_id";
$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
	$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);
	$added_date = $atps_sed_form_DATA['added_date'];
	$submitted_date = $atps_sed_form_DATA['submitted_date'];
	$is_submitted = (int) $atps_sed_form_DATA['is_submitted'];
	$form_status = $atps_sed_form_DATA['form_status'];
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





		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



		<!-- START OF FORM PANEL -->

		<div class="formPanel">

			<h1 class="formPanelTitle">
				<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
					style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
				<?= $atpName; ?> -
				<?= lang('SED'); ?>
			</h1>
		</div>
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Prospective ATP Evidence against the ATPQS'); ?></h2>


			<div class="mainStandards">
				<?php

				$totPoints = 0;

				$main_title = "";
				$qu_quality_standards_mains_sel = "SELECT * FROM  `quality_standards_mains` ORDER BY `main_id` ASC";
				$qu_quality_standards_mains_EXE = mysqli_query($KONN, $qu_quality_standards_mains_sel);
				if (mysqli_num_rows($qu_quality_standards_mains_EXE)) {
					while ($quality_standards_mains_REC = mysqli_fetch_assoc($qu_quality_standards_mains_EXE)) {
						$main_id = (int) $quality_standards_mains_REC['main_id'];
						$main_title = "" . $quality_standards_mains_REC['main_title'];
						$main_icon = "" . $quality_standards_mains_REC['main_icon'];
						$main_estimate = "" . $quality_standards_mains_REC['main_estimate'];

						$mainTotal = 0;
						//get total
						$qu_quality_standards_sel = "SELECT * FROM  `quality_standards` WHERE `main_id` = $main_id";
						$qu_quality_standards_EXE = mysqli_query($KONN, $qu_quality_standards_sel);
						if (mysqli_num_rows($qu_quality_standards_EXE)) {
							while ($quality_standards_REC = mysqli_fetch_assoc($qu_quality_standards_EXE)) {
								$qs_id = (int) $quality_standards_REC['qs_id'];

								$qu_quality_standards_cats_sel = "SELECT COUNT(`cat_id`) FROM  `quality_standards_cats` WHERE `qs_id` = $qs_id";
								$qu_quality_standards_cats_EXE = mysqli_query($KONN, $qu_quality_standards_cats_sel);
								$quality_standards_cats_DATA;
								if (mysqli_num_rows($qu_quality_standards_cats_EXE)) {
									$quality_standards_cats_DATA = mysqli_fetch_array($qu_quality_standards_cats_EXE);
									$thsTot = (int) $quality_standards_cats_DATA[0];
									$mainTotal = $mainTotal + $thsTot;
								}



							}
						}

						$totFilled = 0;
						//get current filled
						$qu_atp_compliance_sel = "SELECT COUNT(`record_id`) FROM  `atp_compliance` WHERE ((`main_id` = $main_id) AND (`atp_id` = $atp_id))";
						$qu_atp_compliance_EXE = mysqli_query($KONN, $qu_atp_compliance_sel);
						if (mysqli_num_rows($qu_atp_compliance_EXE)) {
							$atp_compliance_DATA = mysqli_fetch_array($qu_atp_compliance_EXE);
							$totFilled = (int) $atp_compliance_DATA[0];
						}

						$progress = ceil(($totFilled / $mainTotal) * 100);

						$totOk = 0;
						$score = 0;
						//get ok filled
						$qu_atp_compliance_sel = "SELECT COUNT(`record_id`) FROM  `atp_compliance` WHERE ((`main_id` = $main_id) AND (`answer` = 1) AND (`atp_id` = $atp_id))";
						$qu_atp_compliance_EXE = mysqli_query($KONN, $qu_atp_compliance_sel);
						if (mysqli_num_rows($qu_atp_compliance_EXE)) {
							$atp_compliance_DATA = mysqli_fetch_array($qu_atp_compliance_EXE);
							$totOk = (int) $atp_compliance_DATA[0];
						}
						if ($totFilled != 0) {
							$score = ceil(($totOk / $totFilled) * 100);
						}

						$kpiRes = 'danger';

						if ($score > 0 && $score <= 35) {
							$kpiRes = 'danger';
						} else if ($score > 35 && $score <= 80) {
							$kpiRes = 'warning';
						} else if ($score > 80 && $score <= 100) {
							$kpiRes = 'success';
						} else {
							$kpiRes = 'danger';
						}


						$totPoints = $score + $totPoints;

						?>


						<div class="mainView">
							<i class="fa-solid <?= $main_icon; ?>"></i>
							<h1><?= $main_title; ?></h1>


							<div class="boxRes" style="background-color: var(--<?= $kpiRes; ?>);">
								<span style="color: var(--on-<?= $kpiRes; ?>);"><?= $score; ?> %</span>
							</div>
							<!--a class="fillBtn" href="<?= $POINTER; ?>atps/view/compliance/?main_id=<?= $main_id; ?>&atp_id=<?= $atp_id; ?>"><?= lang("View", "AAR"); ?></a-->
						</div>
						<?php
					}
				}

				$AvgScore = $totPoints / 5;

				if ($AvgScore > 0 && $AvgScore <= 35) {
					$kpiRes = 'danger';
				} else if ($AvgScore > 35 && $AvgScore <= 80) {
					$kpiRes = 'warning';
				} else if ($AvgScore > 80 && $AvgScore <= 100) {
					$kpiRes = 'success';
				} else {
					$kpiRes = 'danger';
				}
				?>
				<div class="mainView">
					<i class="fa-solid fa-folder"></i>
					<h1><?= lang("Total Score"); ?></h1>

					<div class="boxRes" style="background-color: var(--<?= $kpiRes; ?>);">
						<span style="color: var(--on-<?= $kpiRes; ?>);"><?= $AvgScore; ?> %</span>
					</div>
					<!--a class="fillBtn" href="<?= $POINTER; ?>atps/view/compliance/?main_id=<?= $main_id; ?>&atp_id=<?= $atp_id; ?>"><?= lang("View", "AAR"); ?></a-->
				</div>

			</div>






		</div>
		<!-- END OF FORM PANEL -->
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



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


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Organization_Profile'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->
			<?php
			$sed_1 = "";
			$sed_2 = "";
			$sed_3 = "";
			$sed_4 = "";
			$sed_5 = "";
			$sed_6 = "";
			$sed_7 = "";
			$sed_8 = "";
			$sed_9 = "";
			$sed_10 = "";
			$sed_11 = "";
			$sed_12 = "";
			$sed_13 = "";
			$sed_14 = "";
			$sed_15 = "";

			$qu_atps_sed_form_sel = "SELECT * FROM  `atps_sed_form` WHERE `atp_id` = $atp_id";
			$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
			$atps_sed_form_DATA;
			if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
				$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);
				$sed_1 = "" . $atps_sed_form_DATA['sed_1'];
				$sed_2 = "" . $atps_sed_form_DATA['sed_2'];
				$sed_3 = "" . $atps_sed_form_DATA['sed_3'];

				$sed_4 = "" . $atps_sed_form_DATA['sed_4'];
				$sed_5 = "" . $atps_sed_form_DATA['sed_5'];
				$sed_6 = "" . $atps_sed_form_DATA['sed_6'];
				$sed_7 = "" . $atps_sed_form_DATA['sed_7'];
				$sed_8 = "" . $atps_sed_form_DATA['sed_8'];
				$sed_9 = "" . $atps_sed_form_DATA['sed_9'];
				$sed_10 = "" . $atps_sed_form_DATA['sed_10'];
				$sed_11 = "" . $atps_sed_form_DATA['sed_11'];
				$sed_12 = "" . $atps_sed_form_DATA['sed_12'];
				$sed_13 = "" . $atps_sed_form_DATA['sed_13'];
				$sed_14 = "" . $atps_sed_form_DATA['sed_14'];
				$sed_15 = "" . $atps_sed_form_DATA['sed_15'];

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

			<div class="formGroup formGroup-100"><br>
				<hr><br>
			</div>







			<div class="formGroup formGroup-100">
				<label><?= lang("Overview"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_4',
					'dataDen' => '',
					'dataValue' => $sed_4,
					'dataClass' => 'inputer sed_4',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>








			<div class="formGroup formGroup-100">
				<label><?= lang("Background Information"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_6',
					'dataDen' => '',
					'dataValue' => $sed_6,
					'dataClass' => 'inputer sed_6',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>











			<div class="formGroup formGroup-100">
				<label><?= lang("Methodology for Self-Evaluation"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_8',
					'dataDen' => '',
					'dataValue' => $sed_8,
					'dataClass' => 'inputer sed_8',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>




			<div class="formGroup formGroup-100">
				<label><?= lang("Overall Aims & Objectives of the Subject Specialist Provision"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_10',
					'dataDen' => '',
					'dataValue' => $sed_10,
					'dataClass' => 'inputer sed_10',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>





			<div class="formGroup formGroup-100">
				<label><?= lang("Overview of Curriculum Delivery"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_12',
					'dataDen' => '',
					'dataValue' => $sed_12,
					'dataClass' => 'inputer sed_12',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>




			<div class="formGroup formGroup-100">
				<label><?= lang("Future Development Plans"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_14',
					'dataDen' => '',
					'dataValue' => nl2br($sed_14),
					'dataClass' => 'inputer sed_14',
					'isDisabled' => 1,
					'isReadOnly' => 1,
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










		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Quality Improvement Plan'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<div class="formGroup formGroup-100">

				<table>
					<thead>
						<tr>
							<th style="width:40%;">Category</th>
							<th style="width:20%;">Action</th>
							<th>Priority</th>
							<th>Impact</th>
							<th>Ownership</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qu_atp_compliance_sel = "SELECT * FROM  `atp_compliance` WHERE ((`answer` = 3 ) AND (`atp_id` = $atp_id))";
						$qu_atp_compliance_EXE = mysqli_query($KONN, $qu_atp_compliance_sel);
						if (mysqli_num_rows($qu_atp_compliance_EXE)) {
							while ($atp_compliance_REC = mysqli_fetch_assoc($qu_atp_compliance_EXE)) {
								$record_id = (int) $atp_compliance_REC['record_id'];
								$added_date = $atp_compliance_REC['added_date'];
								$main_id = (int) $atp_compliance_REC['main_id'];
								$qs_id = (int) $atp_compliance_REC['qs_id'];
								$cat_id = (int) $atp_compliance_REC['cat_id'];
								$answer = (int) $atp_compliance_REC['answer'];
								$evidence = $atp_compliance_REC['evidence'];




								$qip_action = "" . $atp_compliance_REC['qip_action'];
								$qip_priority = "" . $atp_compliance_REC['qip_priority'];
								$qip_impact = "" . $atp_compliance_REC['qip_impact'];
								$qip_ownership = "" . $atp_compliance_REC['qip_ownership'];

								$qs_id = 0;
								$cat_ref = "";
								$cat_description = "";
								$is_main = 0;
								$qu_quality_standards_cats_sel = "SELECT * FROM  `quality_standards_cats` WHERE `cat_id` = $cat_id";
								$qu_quality_standards_cats_EXE = mysqli_query($KONN, $qu_quality_standards_cats_sel);
								if (mysqli_num_rows($qu_quality_standards_cats_EXE)) {
									$quality_standards_cats_DATA = mysqli_fetch_assoc($qu_quality_standards_cats_EXE);
									$qs_id = (int) $quality_standards_cats_DATA['qs_id'];
									$cat_ref = $quality_standards_cats_DATA['cat_ref'];
									$cat_description = $quality_standards_cats_DATA['cat_description'];
									$is_main = (int) $quality_standards_cats_DATA['is_main'];
								}


								?>

								<tr>
									<td>
										<p style="text-align: justify;"><strong><?= $cat_ref; ?> -
											</strong><?= $cat_description; ?></p>
									</td>
									<td><?= $qip_action; ?></td>
									<td><?= $qip_priority; ?></td>
									<td><?= $qip_impact; ?></td>
									<td><?= $qip_ownership; ?></td>
								</tr>

								<tr>
									<td colspan="4"><br></td>
								</tr>

								<?php
							}
						}

						?>





					</tbody>
				</table>

			</div>





			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->














	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">

			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>
			<?php

			if ($is_submitted != 0) {
				if ($form_status == 'pending' || $form_status == 'rejected') {
					?>
					<div class="saveBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?= $atp_id; ?>', 1);">
						<i class="fa-solid fa-check"></i>
						<span class="label"><?= lang("Approve"); ?></span>
					</div>
					<div class="cancelBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?= $atp_id; ?>', 0);">
						<i class="fa-solid fa-x"></i>
						<span class="label"><?= lang("Reject"); ?></span>
					</div>
					<div class="revBtn actionBtn" id="thsFormBtns" onclick="showRev();">
						<i class="fa-solid fa-refresh"></i>
						<span class="label"><?= lang("Amend"); ?></span>
					</div>
					<br>
					<br>
					<textarea name="comments" id="rc_comment" style="width:100%;padding:0.5em;margin:1% auto;display:block;"
						rows="8" placeholder="Insert Comment"></textarea>
					<div class="saveBtn actionBtn" id="revSubimt" onclick="actionForm('<?= $atp_id; ?>', 2);">
						<i class="fa-solid fa-save"></i>
						<span class="label"><?= lang("Submit"); ?></span>
					</div>
					<?php
				}
			} else {
				echo '<center>Submission is pending</center>';
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
	$('#rc_comment').hide();
	$('#revSubimt').hide();
</script>