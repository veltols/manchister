<?php

$page_title = $page_description = $page_keywords = $page_author = lang("RPL_Form");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$request_id = (isset($_GET['request_id'])) ? (int) test_inputs($_GET['request_id']) : 0;





$request_date = "";
$request_added_date = "";
$atp_id = 0;
$rpl_status_id = 0;
$is_hq = 0;
$qu_rpl_requests_sel = "SELECT * FROM  `rpl_requests` WHERE `request_id` = $request_id";
$qu_rpl_requests_EXE = mysqli_query($KONN, $qu_rpl_requests_sel);
if (mysqli_num_rows($qu_rpl_requests_EXE)) {
	$rpl_requests_DATA = mysqli_fetch_assoc($qu_rpl_requests_EXE);
	$request_id = (int) $rpl_requests_DATA['request_id'];
	$request_date = "" . $rpl_requests_DATA['request_date'];
	$request_added_date = "" . $rpl_requests_DATA['added_date'];
	$atp_id = (int) $rpl_requests_DATA['atp_id'];
	$rpl_status_id = (int) $rpl_requests_DATA['rpl_status_id'];
	$is_hq = (int) $rpl_requests_DATA['is_hq'];
}

$atp_name = "";
$qu_atps_list_sel = "SELECT `atp_name`, `atp_name_ar` FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$atp_name = $atps_list_DATA['atp_name'] . ' - ' . $atp_name_ar = $atps_list_DATA['atp_name_ar'];
}


$form_id = 0;
$learner_id = "";
$learner_name = "";
$learner_phone = "";
$lm_name = "";
$lm_designation = "";
$lm_department = "";
$learner_exp_years = 0;
$qualification_id = 0;
$signed_rpl_form = "";
$updated_cv = "";
$train_cert = "";
$lm_letter = "";
$portfolio_sample = "";
$form_added_date = "";

$qu_rpl_requests_013_sel = "SELECT * FROM  `rpl_requests_013` WHERE ((`request_id` = $request_id) AND (`atp_id` = $atp_id))";
$qu_rpl_requests_013_EXE = mysqli_query($KONN, $qu_rpl_requests_013_sel);
if (mysqli_num_rows($qu_rpl_requests_013_EXE)) {
	$rpl_requests_013_DATA = mysqli_fetch_assoc($qu_rpl_requests_013_EXE);
	$form_id = (int) $rpl_requests_013_DATA['form_id'];
	$learner_id = "" . $rpl_requests_013_DATA['learner_id'];
	$learner_name = "" . $rpl_requests_013_DATA['learner_name'];
	$learner_phone = "" . $rpl_requests_013_DATA['learner_phone'];
	$lm_name = "" . $rpl_requests_013_DATA['lm_name'];
	$lm_designation = "" . $rpl_requests_013_DATA['lm_designation'];
	$lm_department = "" . $rpl_requests_013_DATA['lm_department'];
	$learner_exp_years = (int) $rpl_requests_013_DATA['learner_exp_years'];
	$qualification_id = (int) $rpl_requests_013_DATA['qualification_id'];
	$signed_rpl_form = "" . $rpl_requests_013_DATA['signed_rpl_form'];
	$updated_cv = "" . $rpl_requests_013_DATA['updated_cv'];
	$train_cert = "" . $rpl_requests_013_DATA['train_cert'];
	$lm_letter = "" . $rpl_requests_013_DATA['lm_letter'];
	$portfolio_sample = "" . $rpl_requests_013_DATA['portfolio_sample'];
	$form_added_date = "" . $rpl_requests_013_DATA['added_date'];
}


$rpl_status_name = "";
$rpl_status_name_ar = "";
$qu_rpl_requests_status_sel = "SELECT * FROM  `rpl_requests_status` WHERE `rpl_status_id` = $rpl_status_id";
$qu_rpl_requests_status_EXE = mysqli_query($KONN, $qu_rpl_requests_status_sel);
if (mysqli_num_rows($qu_rpl_requests_status_EXE)) {
	$rpl_requests_status_DATA = mysqli_fetch_assoc($qu_rpl_requests_status_EXE);
	$rpl_status_name = "" . $rpl_requests_status_DATA['rpl_status_name'];
}


$qualification_name = "";
$qu_atps_list_qualifications_sel = "SELECT `qualification_code`, `qualification_name` FROM  `atps_list_qualifications` WHERE `qualification_id` = $qualification_id";
$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
	$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
	$qualification_name = "" . $atps_list_qualifications_DATA['qualification_code'] . ' - ' . $atps_list_qualifications_DATA['qualification_name'];
}






$pageId = 500;
$asideIsHidden = true;
include("app/assets.php");
?>


<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->


	<!-- START OF FORMER COL -->
	<div class="former" style="width: 45% !important;">

		<a href="<?= $POINTER; ?>qm_rpl/list/" class="backBtn actionBtn" id="thsFormBtns">
			<i class="fa-solid fa-arrow-left"></i>
			<span class="label"><?= lang("Back"); ?></span>
		</a>


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Qualification_Mapping'); ?></h2>
			<table style="width: 100%;" border="1">
				<thead>
					<tr>
						<td rowspan="2">code</td>
						<td rowspan="2">Module</td>
						<td colspan="4">Evidence Collected</td>
						<td rowspan="2">Competency Proven</td>
						<td rowspan="2">Credits</td>
						<td rowspan="2">Comment</td>
					</tr>
					<tr>
						<td>Questions</td>
						<td>Practice</td>
						<td>3rd Party</td>
						<td>Others</td>
					</tr>
				</thead>
				<tbody>
					<!--tr>
						<td>qualification code</td>
						<td>qualification level</td>
						<td>ec1</td>
						<td>ec2</td>
						<td>ec3</td>
						<td>YES - NO</td>
					</tr-->
					<tr>
						<td colspan="8">Core Units</td>
					</tr>
					<?php
					for ($i = 1; $i <= 5; $i++) {
						?>
						<tr>
							<td><?= $i; ?></td>
							<td>Module 0<?= $i; ?></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td>
								<select name="a1" id="aa">
									<option value="0" selected>Please Select</option>
									<option value="1">YES</option>
									<option value="2">NO</option>
								</select>
							</td>
							<td>
								<input style="width:4em;" type="number" min="0" step="0.1" value="1">
							</td>
							<td><br></td>
						</tr>
						<?php
					}
					//-------------------------------------------
					?>
					<tr>
						<td colspan="8">Information Collection Stream</td>
					</tr>
					<?php
					for ($i = 1; $i <= 5; $i++) {
						?>
						<tr>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td>
								<select name="a1" id="aa">
									<option value="0" selected>Please Select</option>
									<option value="1">YES</option>
									<option value="2">NO</option>
								</select>
							</td>
							<td>
								<input style="width:4em;" type="number" min="0" step="0.1" value="1">
							</td>
							<td><br></td>
						</tr>
						<?php
					}
					//-------------------------------------------
					?>
					<tr>
						<td colspan="8">Optional Units</td>
					</tr>
					<?php
					for ($i = 1; $i <= 5; $i++) {
						?>
						<tr>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td><br></td>
							<td>
								<select name="a1" id="aa">
									<option value="0" selected>Please Select</option>
									<option value="1">YES</option>
									<option value="2">NO</option>
								</select>
							</td>
							<td>
								<input style="width:4em;" type="number" min="0" step="0.1" value="1">
							</td>
							<td><br></td>
						</tr>
						<?php
					}
					//-------------------------------------------
					?>

				</tbody>
			</table>
		</div>

	</div>



	<!-- START OF FORMER COL -->
	<div class="former" style="width: 45% !important;">


		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Request_information'); ?></h2>


			<div class="formGroup formGroup-30">
				<label><?= lang("atp_name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'atp_name',
					'dataDen' => '',
					'dataValue' => $atp_name,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-30">
				<label><?= lang("requested_on"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'request_date',
					'dataDen' => '',
					'dataValue' => $request_date,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-30">
				<label><?= lang("request_status"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'rpl_status_name',
					'dataDen' => '',
					'dataValue' => $rpl_status_name,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>




		</div>
		<!-- END OF FORM PANEL -->


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Form_information'); ?></h2>


			<div class="formGroup formGroup-50">
				<label><?= lang("learner_id"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'learner_id',
					'dataDen' => '',
					'dataValue' => $learner_id,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-50">
				<label><?= lang("learner_name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'learner_name',
					'dataDen' => '',
					'dataValue' => $learner_name,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("direct_learner_phone_number"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'learner_phone',
					'dataDen' => '',
					'dataValue' => $learner_phone,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("NO. of years working in this Role"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'learner_exp_years',
					'dataDen' => '',
					'dataValue' => $learner_exp_years,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-30">
				<label><?= lang("Name_of_line_Manager"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'lm_name',
					'dataDen' => '',
					'dataValue' => $lm_name,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-30">
				<label><?= lang("Designation"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'lm_designation',
					'dataDen' => '',
					'dataValue' => $lm_designation,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-30">
				<label><?= lang("Division/Department/Section"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'lm_department',
					'dataDen' => '',
					'dataValue' => $lm_department,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-30">
				<label><?= lang("qualification_name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'qualification_name',
					'dataDen' => '',
					'dataValue' => $qualification_name,
					'dataClass' => 'inputer sss',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>






		</div>
		<!-- END OF FORM PANEL -->


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Attachments'); ?></h2>
			<div class="formGroup formGroup-50">
				<label><?= lang("signed_RPL_form"); ?></label>

				<?php
				if ($signed_rpl_form != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $signed_rpl_form; ?>" target="_blank">
						<button style="padding: 0.5em 1em;">View</button>
					</a>
					<?php
				} else {
					?>
					<label><?= lang("Not_Available"); ?></label>
					<?php
				}
				?>

			</div>


			<div class="formGroup formGroup-50">
				<label><?= lang("updated_cv"); ?></label>

				<?php
				if ($updated_cv != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $updated_cv; ?>" target="_blank">
						<button style="padding: 0.5em 1em;">View</button>
					</a>
					<?php
				} else {
					?>
					<label><?= lang("Not_Available"); ?></label>
					<?php
				}
				?>

			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Training certificate and transcripts"); ?></label>

				<?php
				if ($train_cert != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $train_cert; ?>" target="_blank">
						<button style="padding: 0.5em 1em;">View</button>
					</a>
					<?php
				} else {
					?>
					<label><?= lang("Not_Available"); ?></label>
					<?php
				}
				?>

			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Work expereince letter from line manager"); ?></label>

				<?php
				if ($lm_letter != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $lm_letter; ?>" target="_blank">
						<button style="padding: 0.5em 1em;">View</button>
					</a>
					<?php
				} else {
					?>
					<label><?= lang("Not_Available"); ?></label>
					<?php
				}
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Samples of work/portfolio"); ?></label>
				<?php
				if ($portfolio_sample != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $portfolio_sample; ?>" target="_blank">
						<button style="padding: 0.5em 1em;">View</button>
					</a>
					<?php
				} else {
					?>
					<label><?= lang("Not_Available"); ?></label>
					<?php
				}
				?>
			</div>




		</div>
		<!-- END OF FORM PANEL -->








		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
	</div>
	<!-- END OF FORMER COL -->








	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->




<script>
	function actionForm(request_id, ops) {
		var aa = confirm("Are you sure?");
		var iqc_comment = $('#iqc_comment').val();
		var commentCheck = true;
		if (ops == 2 || ops == 0) {
			if (iqc_comment == '') {
				commentCheck = false;
			}
		}
		if (commentCheck == true) {
			if (aa == true) {
				if (activeRequest == false) {
					activeRequest = true;
					start_loader('article');
					$.ajax({
						url: '<?= $POINTER; ?>rpl_form_ops',
						dataType: "JSON",
						method: "POST",
						data: { 'request_id': request_id, 'ops': ops, 'iqc_comment': iqc_comment },
						success: function (RES) {

							var itm = RES[0];
							activeRequest = false;
							end_loader('article');
							makeSiteAlert('suc', '<?= lang("Data Saved"); ?>');
							setTimeout(function () {
								window.location.href = '<?= $POINTER; ?>rpl/list/';
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
		$('#iqc_comment').toggle();
		$('#revSubimt').toggle();
	}

</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>
<script>
	$('#iqc_comment').hide();
	$('#revSubimt').hide();
	$('#rejectSubimt').hide();
</script>