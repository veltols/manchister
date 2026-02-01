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

			<h1 class="formPanelTitle">
				<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
					style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
				<?= $atpName; ?> -
				<?= lang('Faculty_Details'); ?>
			</h1>

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
						//$faculty_cert = "" . $atps_list_faculties_REC['faculty_cert'];
				
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
			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>

			<?php
			if ($form_status == 'pending' || $form_status == 'rejected') {
				?>
				<div class="saveBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?= $atp_id; ?>', 1);">
					<i class="fa-solid fa-check"></i>
					<span class="label"><?= lang("Approve"); ?></span>
				</div>
				<div class="cancelBtn actionBtn" id="thsFormBtns"
					onclick="$('#rc_comment').toggle();$('#rejectSubimt').toggle();">
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
				<div class="saveBtn actionBtn" id="rejectSubimt" onclick="actionForm('<?= $atp_id; ?>', 0);">
					<i class="fa-solid fa-save"></i>
					<span class="label"><?= lang("Submit"); ?></span>
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
		var commentCheck = true;
		if (ops == 2 || ops == 0) {
			if (rc_comment == '') {
				commentCheck = false;
			}
		}
		if (commentCheck == true) {
			if (aa == true) {
				if (activeRequest == false) {
					activeRequest = true;
					start_loader('article');
					$.ajax({
						url: '<?= $POINTER; ?>approve_atp_form',
						dataType: "JSON",
						method: "POST",
						data: { "form": 'faculty_details', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
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
		} else {
			alert('Comment is missing');
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
	$('#rejectSubimt').hide();
</script>