<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_info_request';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$request_id = (isset($_GET['request_id'])) ? (int) test_inputs($_GET['request_id']) : 0;



$atp_logo = "no-img.png";
$atpName = "no-img.png";
$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$atpName = $atps_list_DATA['atp_name'];
	$atp_logo = $atps_list_DATA['atp_logo'];
}






$request_date = "";
$response_date = "";
$added_by = 0;
$added_date = "";
$request_status = "";

$request_department = 2; //EQA

$qu_atps_info_request_sel = "SELECT * FROM  `atps_info_request` WHERE ((`atp_id` = $atp_id) AND (`request_id` = $request_id) AND (`request_department` = $request_department))";
$qu_atps_info_request_EXE = mysqli_query($KONN, $qu_atps_info_request_sel);

if (mysqli_num_rows($qu_atps_info_request_EXE)) {
	$atps_info_request_DATA = mysqli_fetch_assoc($qu_atps_info_request_EXE);
	$request_id = (int) $atps_info_request_DATA['request_id'];
	$request_date = $atps_info_request_DATA['request_date'];
	$response_date = $atps_info_request_DATA['response_date'];
	$request_department = (int) $atps_info_request_DATA['request_department'];
	$added_by = (int) $atps_info_request_DATA['added_by'];
	$added_date = $atps_info_request_DATA['added_date'];
	$request_status = $atps_info_request_DATA['request_status'];
	$atp_id = (int) $atps_info_request_DATA['atp_id'];
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

		<div class="formPanel">

			<h1 class="formPanelTitle">
				<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
					style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
				<?= $atpName; ?> -
				<?= lang('Information Request No.0') . $request_id; ?>
			</h1>



			<table border="1" style="width: 100%;">

				<thead>
					<tr>
						<th style="width:5%;">NO.</th>
						<th style="width:45%;">Requested Evidence</th>
						<th style="width:15%;">Comment</th>
						<th style="width:15%;">Evidence</th>
					</tr>
				</thead>
				<tbody id="trainBody">
					<?php
					//$qu_atps_info_request_evs_sel = "SELECT * FROM  `atps_info_request_evs` WHERE ((`atp_id` = $atp_id) AND (`request_id` = $request_id) AND (`request_department` = $request_department))";
					$qu_atps_info_request_evs_evs_sel = "SELECT * FROM  `atps_info_request_evs` WHERE ( (`request_id` = $request_id) )";
					$qu_atps_info_request_evs_evs_EXE = mysqli_query($KONN, $qu_atps_info_request_evs_evs_sel);
					if (mysqli_num_rows($qu_atps_info_request_evs_evs_EXE)) {
						$no = 0;
						while ($atps_info_request_evs_evs_REC = mysqli_fetch_assoc($qu_atps_info_request_evs_evs_EXE)) {
							$no++;
							$evidence_id = (int) $atps_info_request_evs_evs_REC['evidence_id'];
							$required_evidence = $atps_info_request_evs_evs_REC['required_evidence'];
							$answer = $atps_info_request_evs_evs_REC['answer'];
							$required_attachment = $atps_info_request_evs_evs_REC['required_attachment'];
							?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= $required_evidence; ?></td>
								<td><?= $answer; ?></td>
								<td>
									<?php
									if ($required_attachment != '') {
										?>
										<a href="../<?= $POINTER; ?>uploads/<?= $required_attachment; ?>" target="_blank"
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









	<div class="describer">
		<div class="formActionBtns">

			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=100" class="backBtn actionBtn"
				id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>

		</div>

	</div>





	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->





<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>