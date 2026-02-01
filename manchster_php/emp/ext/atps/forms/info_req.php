<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Program Registration");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;









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
		<?php

		$form_status = "";

		$est_name = "";
		$est_name_ar = "";
		$iqa_name = "";
		$email_address = "";
		$registration_no = "";
		$registration_expiry = "";
		$delivery_plan = "";
		$org_chart = "";
		$site_plan = "";
		$sed_form = "";
		$atp_category_id = 0;

		$emirate_id = 0;
		$area_name = "";
		$street_name = "";
		$building_name = "";



		$qu_atps_form_init_sel = "SELECT * FROM  `atps_form_init` WHERE `atp_id` = $atp_id";
		$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
		if (mysqli_num_rows($qu_atps_form_init_EXE)) {
			$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);
			$est_name = "" . $atps_form_init_DATA['est_name'];
			$est_name_ar = "" . $atps_form_init_DATA['est_name_ar'];
			$iqa_name = "" . $atps_form_init_DATA['iqa_name'];
			$email_address = "" . $atps_form_init_DATA['email_address'];
			$registration_no = "" . $atps_form_init_DATA['registration_no'];
			$registration_expiry = "" . $atps_form_init_DATA['registration_expiry'];
			$delivery_plan = "" . $atps_form_init_DATA['delivery_plan'];
			$org_chart = "" . $atps_form_init_DATA['org_chart'];
			$site_plan = "" . $atps_form_init_DATA['site_plan'];
			$sed_form = "" . $atps_form_init_DATA['sed_form'];
			$form_status = "" . $atps_form_init_DATA['form_status'];
			$atp_category_id = (int) $atps_form_init_DATA['atp_category_id'];


			$emirate_id = (int) $atps_form_init_DATA['emirate_id'];
			$area_name = $atps_form_init_DATA['area_name'];
			$street_name = $atps_form_init_DATA['street_name'];
			$building_name = $atps_form_init_DATA['building_name'];
		}


		//atps_prog_register_req

		
		
	$qu_atps_prog_register_req_sel = "SELECT * FROM  `atps_prog_register_req` WHERE `atp_id` = $atp_id";
	$qu_atps_prog_register_req_EXE = mysqli_query($KONN, $qu_atps_prog_register_req_sel);
	$atps_prog_register_req_DATA;
	if(mysqli_num_rows($qu_atps_prog_register_req_EXE)){
		$atps_prog_register_req_DATA = mysqli_fetch_assoc($qu_atps_prog_register_req_EXE);
		$form_id = ( int ) $atps_prog_register_req_DATA['form_id'];
		$atp_id = ( int ) $atps_prog_register_req_DATA['atp_id'];
		$added_date = $atps_prog_register_req_DATA['added_date'];
		$submitted_date = $atps_prog_register_req_DATA['submitted_date'];
		$is_submitted = ( int ) $atps_prog_register_req_DATA['is_submitted'];
		$form_status = $atps_prog_register_req_DATA['form_status'];
	}








		
		$pageTitler = lang('Program Registration Form');
		include("atp_view/atp_header.php");
		?>
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Qualifications'); ?></h2>



			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Type"); ?></div>
							<div class="th"><?= lang("Name"); ?></div>
							<div class="th"><?= lang("Category"); ?></div>
							<div class="th"><?= lang("emirates_level"); ?></div>
							<div class="th"><?= lang("Credits"); ?></div>
							<div class="th"><?= lang("mode_of_delivery"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="listData">
						<?php
	$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE `atp_id` = $atp_id";
	$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
	if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
		while($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)){
			$qualification_id = ( int ) $atps_list_qualifications_REC['qualification_id'];
			$qualification_type = $atps_list_qualifications_REC['qualification_type'];
			$qualification_name = $atps_list_qualifications_REC['qualification_name'];
			$qualification_category = $atps_list_qualifications_REC['qualification_category'];
			$emirates_level = $atps_list_qualifications_REC['emirates_level'];
			$qulaification_credits = $atps_list_qualifications_REC['qulaification_credits'];
			$mode_of_delivery = $atps_list_qualifications_REC['mode_of_delivery'];
			$atp_id = ( int ) $atps_list_qualifications_REC['atp_id'];
		?>
		
		<div class="tr levelRow">
			<div class="td"><?=$qualification_type; ?></div>
			<div class="td"><?=$qualification_name; ?></div>
			<div class="td"><?=$qualification_category; ?></div>
			<div class="td" style="text-align:center;"><?=$emirates_level; ?></div>
			<div class="td" style="text-align:center;"><?=$qulaification_credits; ?></div>
			<div class="td" style="text-align:center;"><?=$mode_of_delivery; ?></div>
		</div>
		<?php
		}
	}

						?>
					</div>
				</div>
			</div>


			<div class="formGroup formGroup-100">
				<br><br><br><br><br><br>
			</div>



			
			<h2 class="formPanelTitle"><?= lang('Faculty_Details'); ?></h2>


			
			<div class="dataContainer" id="listData">

				<?php
				$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE `atp_id` = $atp_id";
				$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
				if (mysqli_num_rows($qu_atps_list_faculties_EXE)) {
					while ($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)) {
						$faculty_id = ( int ) $atps_list_faculties_REC['faculty_id'];
						$faculty_name = $atps_list_faculties_REC['faculty_name'];
						$faculty_type_id = ( int ) $atps_list_faculties_REC['faculty_type_id'];
						$educational_qualifications = $atps_list_faculties_REC['educational_qualifications'];
						$years_experience = $atps_list_faculties_REC['years_experience'];
						$certificate_name = $atps_list_faculties_REC['certificate_name'];

						$faculty_type_name = "";
					$qu_atps_list_faculties_types_sel = "SELECT `faculty_type_name` FROM  `atps_list_faculties_types` WHERE `faculty_type_id` = $faculty_type_id";
					$qu_atps_list_faculties_types_EXE = mysqli_query($KONN, $qu_atps_list_faculties_types_sel);
					if(mysqli_num_rows($qu_atps_list_faculties_types_EXE)){
						$atps_list_faculties_types_DATA = mysqli_fetch_assoc($qu_atps_list_faculties_types_EXE);
						$faculty_type_name = $atps_list_faculties_types_DATA['faculty_type_name'];
					}
				
						?>


						<div class="dataElement data-list-view" id="contact-<?= $faculty_id; ?>">
							<div class="dataView col-2">
								<div class="property"><?= lang("faculty_name"); ?></div>
								<div class="value"><?= $faculty_name; ?></div>
							</div>
							<div class="dataView col-2">
								<div class="property"><?= lang("faculty_type"); ?></div>
								<div class="value"><?= $faculty_type_name; ?></div>
							</div>
							<div class="dataView col-1">
								<div class="property"><?= lang("educational_qualifications"); ?></div>
								<div class="value"><?= $educational_qualifications; ?></div>
							</div>
							<div class="dataView col-2">
								<div class="property"><?= lang("years_of_experience"); ?></div>
								<div class="value"><?= $years_experience; ?></div>
							</div>
							<div class="dataView col-2">
								<div class="property"><?= lang("certificate_name"); ?></div>
								<div class="value"><?= $certificate_name; ?></div>
							</div>
						</div>

						<?php
					}
				}

				?>
			</div>
			
			
			
			<div class="formGroup formGroup-100">
				<br><br><br><br><br><br>
			</div>


			
			



			<div class="formGroup formGroup-100">
				<br><br>
			</div>



		</div>





		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">

			<a href="<?= $POINTER; ?>ext/atps/view/?atp_id=<?= $atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>

			<?php
			if ($form_status == 'pending' || $form_status == 'rejected') {
				?>
				<div class="saveBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?= $atp_id; ?>', 1);">
					<i class="fa-solid fa-check"></i>
					<span class="label"><?= lang("Approve & send to EQA"); ?></span>
				</div>
				<div class="cancelBtn actionBtn" id="thsFormBtns"
					onclick="$('#rc_comment').toggle();$('#rejectSubimt').toggle();">
					<i class="fa-solid fa-x"></i>
					<span class="label"><?= lang("Reject"); ?></span>
				</div>
				<div class="revBtn actionBtn" id="thsFormBtns" onclick="showRev(2);">
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
						url: '<?= $POINTER; ?>ext/approve_atp_form',
						dataType: "JSON",
						method: "POST",
						data: { "form": 'info_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
						success: function (RES) {

							var itm = RES[0];
							activeRequest = false;
							end_loader('article');
							makeSiteAlert('suc', '<?= lang("Data Saved"); ?>');
							setTimeout(function () {
								window.location.href = '<?= $POINTER; ?>ext/atps/view/?atp_id=<?= $atp_id; ?>';
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