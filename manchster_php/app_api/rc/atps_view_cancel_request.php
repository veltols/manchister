<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Cancel_Requst");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$cancel_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['cancel_id']) : 0;




$submission_date = "";
$approved_date = "";
$cancellation_reason = 0;
$cancellation_text = "";
$cancel_status = "";

$qu_atps_list_cancel_sel = "SELECT * FROM  `atps_list_cancel` WHERE ((`atp_id` = $atp_id) AND (`cancel_id` = $cancel_id))";
$qu_atps_list_cancel_EXE = mysqli_query($KONN, $qu_atps_list_cancel_sel);
if (mysqli_num_rows($qu_atps_list_cancel_EXE)) {
	$atps_list_cancel_DATA = mysqli_fetch_assoc($qu_atps_list_cancel_EXE);
	$cancel_id = (int) $atps_list_cancel_DATA['cancel_id'];
	$submission_date = "" . $atps_list_cancel_DATA['submission_date'];
	$approved_date = "" . $atps_list_cancel_DATA['approved_date'];
	$cancellation_reason = (int) $atps_list_cancel_DATA['cancellation_reason'];
	$cancellation_text = "" . $atps_list_cancel_DATA['cancellation_text'];
	$cancel_status = "" . $atps_list_cancel_DATA['cancel_status'];
	$atp_id = (int) $atps_list_cancel_DATA['atp_id'];
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
				<?= lang('Cancellation Request Form'); ?>
			</h1>




			<div class="formGroup formGroup-100">
				<label><?= lang("Reason for cancellation"); ?></label>
				<select data-name="cancellation_reason" data-type="text" data-den="0" data-req="1"
					class="inputer cancellation_reason" data-from="<?= $POINTER; ?>get_yes_no_select" disabled readonly>
					<option value="0"><?= lang("Please_Select"); ?></option>
					<option value="1"><?= lang("No benefits found"); ?></option>
					<option value="2"><?= lang("Out of business"); ?></option>
					<option value="3"><?= lang("Can't keep up with the requirements"); ?></option>
					<option value="4"><?= lang("Prefer not to say"); ?></option>
				</select>
			</div>
			<script>
				$('.cancellation_reason').val('<?= $cancellation_reason; ?>');
			</script>

			<div class="formGroup formGroup-100">
				<label><?= lang("Please write down any extra details for your cancellation request"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'cancellation_text',
					'dataDen' => '',
					'dataValue' => $cancellation_text,
					'dataClass' => 'inputer cancellation_text',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>






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
			if ($cancel_status == 'pending' || $cancel_status == 'rejected') {
				?>
				<div class="saveBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?= $atp_id; ?>', 1);">
					<i class="fa-solid fa-check"></i>
					<span class="label"><?= lang("Send to GM"); ?></span>
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