<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$main_id = (isset($_GET['main_id'])) ? (int) test_inputs($_GET['main_id']) : 0;


$added_date = "";
$submitted_date = "";
$is_submitted = 0;
$form_status = "";
$qu_atps_faculty_details_sel = "SELECT * FROM  `atps_faculty_details` WHERE `atp_id` = $atp_id";
$qu_atps_faculty_details_EXE = mysqli_query($KONN, $qu_atps_faculty_details_sel);
if (mysqli_num_rows($qu_atps_faculty_details_EXE)) {
	$atps_faculty_details_DATA = mysqli_fetch_assoc($qu_atps_faculty_details_EXE);
	$added_date = $atps_faculty_details_DATA['added_date'];
	$submitted_date = $atps_faculty_details_DATA['submitted_date'];
	$is_submitted = (int) $atps_faculty_details_DATA['is_submitted'];
	$form_status = $atps_faculty_details_DATA['form_status'];
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
			<h2 class="formPanelTitle"><?= lang('Prospective ATP Evidence against the ATPQS'); ?></h2>

			<?php

			if ($main_id == 0) {
				?>

				<div class="mainStandards">
					<?php
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
							} else if ($score > 35 && $score <= 70) {
								$kpiRes = 'warning';
							} else if ($score > 70 && $score <= 100) {
								$kpiRes = 'success';
							} else {
								$kpiRes = 'danger';
							}




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
					?>

				</div>
				<?php
			}
			?>






		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->












	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">



			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>
		</div>

	</div>





	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->



<script>
	function actionForm(atpId, ops) {
		var aa = confirm("Are you sure?");
		if (aa == true) {
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $POINTER; ?>approve_atp_form',
					dataType: "JSON",
					method: "POST",
					data: { "form": 'faculty_details', 'atp_id': atpId, 'ops': ops },
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




</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>