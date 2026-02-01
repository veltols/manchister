<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_006';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$tab_id = (isset($_GET['tab_id'])) ? (int) test_inputs($_GET['tab_id']) : 0;


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

			<h1 class="formPanelTitle">
				<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
					style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
				<?= $atpName; ?> -
				<?= lang('Internal Remote Activity Feedback Form'); ?>
			</h1>
			<div><br><br></div>

			<h2 class="formPanelTitle"><?= lang('General Information'); ?></h2>
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

			<!-- END OF FORM PANEL -->





			<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
			<!-- START OF FORM PANEL -->

			<div style="width: 100%;"><br><br><br></div>
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






			<!-- END OF FORM PANEL -->
			<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->





			<!-- START OF FORM PANEL -->
			<div style="width: 100%;"><br><br><br></div>
			<h2 class="formPanelTitle"><?= lang('Desktop_review'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->


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

			<table border="1" style="width: 100%;">

				<thead>
					<tr>
						<th style="width:20%;">Evidence</th>
						<th style="width:20%;">SED Feedback</th>
						<th style="width:10%;">Comment</th>
						<th style="width:5%;">Evidence</th>
						<th style="width:30%;">EQA Response</th>
						<th style="width:10%;">---</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qu_atp_compliance_sel = "SELECT * FROM  `atp_compliance` WHERE ((`atp_id` = $atp_id))";
					$qu_atp_compliance_EXE = mysqli_query($KONN, $qu_atp_compliance_sel);
					if (mysqli_num_rows($qu_atp_compliance_EXE)) {
						$no = 0;
						while ($atp_compliance_REC = mysqli_fetch_assoc($qu_atp_compliance_EXE)) {
							$no++;
							$record_id = (int) $atp_compliance_REC['record_id'];
							$added_date = $atp_compliance_REC['added_date'];
							$main_id = (int) $atp_compliance_REC['main_id'];
							$qs_id = (int) $atp_compliance_REC['qs_id'];
							$cat_id = (int) $atp_compliance_REC['cat_id'];
							$answer = (int) $atp_compliance_REC['answer'];
							$evidence = "" . $atp_compliance_REC['evidence'];
							$cat_comment = "" . $atp_compliance_REC['cat_comment'];
							$eqa_criteria = "" . $atp_compliance_REC['eqa_criteria'];
							$eqa_feedback = "" . $atp_compliance_REC['eqa_feedback'];




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

							$answerTxt = "Not Applicable";
							switch ($answer) {
								case 1:
									$answerTxt = "Applicable";
									break;
								case 2:
									$answerTxt = "Not Applicable";
									break;
								case 3:
									$answerTxt = "Included in QIP";
									break;
							}
							?>
							<input type="hidden" name="record_ids[]" class="inputer" value="<?= $record_id; ?>"
								data-name="record_ids[]" data-req="1" data-type="int" data-den="">

							<tr>
								<td><?= $cat_ref; ?> - </strong><?= $cat_description; ?></td>
								<td><?= $answerTxt; ?></td>
								<td><?= $cat_comment; ?></td>
								<td>
									<?php
									if ($evidence != '') {
										?>
										<a href="../<?= $POINTER; ?>uploads/<?= $evidence; ?>" target="_blank"
											style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">View
											Evidence</a>
										<?php
									} else {
										?>
										Not Available
										<?php
									}
									?>
								</td>
								<td>

									<div class="formGroup formGroup-100">
										<select class="inputer ec-<?= $record_id; ?>" data-name="eqa_criterias[]" data-req="1"
											data-type="text" data-den="100">
											<option value="100" selected><?= lang("Please_Select"); ?></option>
											<option value="1"><?= lang("YES"); ?></option>
											<option value="0"><?= lang("NO"); ?></option>
										</select>
									</div>
									<script>
										$('.ec-<?= $record_id; ?>').val(<?= $eqa_criteria; ?>);
									</script>

									<textarea style="width:100%;" rows="8" class="inputer ef-<?= $record_id; ?>"
										data-name="eqa_feedbacks[]" data-req="1" data-type="text"
										data-den=""><?= $eqa_feedback; ?></textarea>
								</td>
								<td>
									<a onclick="save006Entry(<?= $record_id; ?>);"
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

		</div>
		<!-- END OF FORM PANEL -->




	</div>
	<!-- END OF FORMER COL -->



	<script>
		function save006Entry(record_id) {
			var eqa_criteria = $('.ec-' + record_id).val();
			var eqa_feedback = $('.ef-' + record_id).val();

			$('.ec-' + record_id).removeClass('inputHasError');
			$('.ef-' + record_id).removeClass('inputHasError');

			if (eqa_criteria != 100) {
				if (eqa_feedback != '') {

					if (activeRequest == false) {
						activeRequest = true;
						start_loader('article');
						$.ajax({
							url: '<?= $pageController; ?>',
							dataType: "JSON",
							method: "POST",
							data: { "record_ids[]": record_id, 'atp_id': <?= $atp_id; ?>, 'eqa_criterias[]': eqa_criteria, 'eqa_feedbacks[]': eqa_feedback },
							success: function (RES) {

								activeRequest = false;
								end_loader('article');
								makeSiteAlert('suc', '<?= lang("Data Saved"); ?>');

							},
							error: function () {
								activeRequest = false;
								end_loader('article');
								alert("Err-327657");
							}
						});
					}
				} else {
					$('.ef-' + record_id).addClass('inputHasError');
				}
			} else {
				$('.ec-' + record_id).addClass('inputHasError');
			}
		}
	</script>





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
					<i class="fa-solid fa-save"></i>
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
	$('#rc_comment').hide();
	$('#revSubimt').hide();
</script>