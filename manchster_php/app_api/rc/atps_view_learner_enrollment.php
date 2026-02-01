<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Learners_Enrollment");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$le_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['le_id']) : 0;




$submission_date = "";
$start_date = "";
$end_date = "";
$learners_no = "";
$cohort = "";
$qualification_id = 0;
$atp_id = 0;
$qu_atps_list_le_sel = "SELECT * FROM  `atps_list_le` WHERE `le_id` = $le_id";
$qu_atps_list_le_EXE = mysqli_query($KONN, $qu_atps_list_le_sel);
$atps_list_le_DATA;
if (mysqli_num_rows($qu_atps_list_le_EXE)) {
	$atps_list_le_DATA = mysqli_fetch_assoc($qu_atps_list_le_EXE);
	$le_id = (int) $atps_list_le_DATA['le_id'];
	$submission_date = $atps_list_le_DATA['submission_date'];
	$start_date = $atps_list_le_DATA['start_date'];
	$end_date = $atps_list_le_DATA['end_date'];
	$learners_no = $atps_list_le_DATA['learners_no'];
	$cohort = $atps_list_le_DATA['cohort'];
	$qualification_id = (int) $atps_list_le_DATA['qualification_id'];
	$atp_id = (int) $atps_list_le_DATA['atp_id'];
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
		<?php


		$atp_logo = "no-img.png";
		$atpName = "no-img.png";
		$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
		$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
		if (mysqli_num_rows($qu_atps_list_EXE)) {
			$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
			$atpName = $atps_list_DATA['atp_name'];
			$atp_logo = $atps_list_DATA['atp_logo'];
		}


		?>



		<div class="formPanel">
			<!-- START OF FORM PANEL -->

			<h1 class="formPanelTitle">
				<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
					style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
				<?= $atpName; ?> -
				<?= lang('Learner Enrollment Request'); ?>
			</h1>



			<div class="formGroup formGroup-50">
				<label><?= lang("start_date"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'start_date',
					'dataDen' => '',
					'dataValue' => $start_date,
					'dataClass' => 'inputer has_future_date start_date',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>
			<div class="formGroup formGroup-50">
				<label><?= lang("end_date"); ?></label>
				<?php
				$props = [
					'dataType' => 'date',
					'dataName' => 'end_date',
					'dataDen' => '',
					'dataValue' => $end_date,
					'dataClass' => 'inputer has_future_date end_date',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("learners_no"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'learners_no',
					'dataDen' => '',
					'dataValue' => $learners_no,
					'dataClass' => 'inputer learners_no',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("cohort"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'cohort',
					'dataDen' => '',
					'dataValue' => $cohort,
					'dataClass' => 'inputer cohort',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>


			<div class="formGroup formGroup-100">
				<label><?= lang("Qualification"); ?></label>
				<select data-name="qualification_id" data-type="text" data-den="0" data-req="1"
					class="inputer qualification_id" data-from="<?= $POINTER; ?>qualification_id" disabled readonly>
					<option value="0"><?= lang("Please_Select"); ?></option>
					<?php
					$qu_atps_list_qualifications_sel = "SELECT `qualification_id`, `qualification_name` FROM  `atps_list_qualifications` WHERE `atp_id` = $USER_ID";
					$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
					if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
						while ($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)) {
							$qId = (int) $atps_list_qualifications_REC['qualification_id'];
							$qualification_name = $atps_list_qualifications_REC['qualification_name'];
							?>
							<option value="<?= $qId; ?>"><?= $qualification_name; ?></option>

							<?php
						}
					}

					?>
				</select>
			</div>

			<script>
				$('.qualification_id').val('<?= $qualification_id; ?>');
			</script>






			<!-- END OF FORM PANEL -->
		</div>





	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">

			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>

			<?php
			$cancel_status = 'pending';
			if ($cancel_status == 'pending' || $cancel_status == 'rejected') {
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
						url: '<?= $POINTER; ?>process_atp_cancellation',
						dataType: "JSON",
						method: "POST",
						data: { "form": 'cancel_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
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