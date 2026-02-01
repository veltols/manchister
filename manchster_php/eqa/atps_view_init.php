<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
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
		$trade_license = "";
		$noc_letter = "";
		$com_let = "";
		$legal_stt = "";
		$sector_id = 0;

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
			$trade_license = "" . $atps_form_init_DATA['trade_license'];
			$noc_letter = "" . $atps_form_init_DATA['noc_letter'];
			$com_let = "" . $atps_form_init_DATA['com_let'];
			$legal_stt = "" . $atps_form_init_DATA['legal_stt'];
			$form_status = "" . $atps_form_init_DATA['form_status'];
			$sector_id = (int) $atps_form_init_DATA['sector_id'];
		}

		?>

		<?php
		include("atp_view/atp_header.php");
		?>
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Basic_information'); ?></h2>


			<div class="formGroup formGroup-50">
				<label><?= lang("Establishment_Name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'est_name',
					'dataDen' => '',
					'dataValue' => $est_name,
					'dataClass' => 'inputer est_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Establishment_Name"); ?> (AR)</label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'est_name_ar',
					'dataDen' => '',
					'dataValue' => $est_name_ar,
					'dataClass' => 'inputer est_name_ar',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1',
					'dir' => 'rtl'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("IQA Lead name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'iqa_name',
					'dataDen' => '',
					'dataValue' => $iqa_name,
					'dataClass' => 'inputer iqa_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("email_address"); ?></label>
				<?php
				$props = [
					'dataType' => 'email',
					'dataName' => 'email_address',
					'dataDen' => '',
					'dataValue' => $email_address,
					'dataClass' => 'inputer email_address',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>



			<?php
			/*
																													 <div class="formGroup formGroup-50">
																														 <label><?=lang("Registration_Expiry_date"); ?></label>
																														 <?php
																												 $props = [
																													 'dataType' => 'date',
																													 'dataName' => 'registration_expiry',
																													 'dataDen' => '',
																													 'dataValue' => $registration_expiry,
																													 'dataClass' => 'inputer registration_expiry',
																													 'isDisabled' => 1,
																													 'isReadOnly' => 1, 
																													 'isRequired' => '0'
																												 ];
																												 echo build_input( ...$props );
																											 ?>
																													 </div>
																											 */
			?>

			<div class="formGroup formGroup-100">
				<label><?= lang("Sector"); ?></label>
				<select readonly disabled data-name="industry_id" data-type="int" data-den="0" data-req="1"
					class="inputer need_data sector_id" data-from="<?= $POINTER; ?>get_select_data"
					data-dest="atps_list_industries" data-initial="<?= $sector_id; ?>"></select>

			</div>

			<div class="formGroup formGroup-100">
				<br>
				<hr>
				<br>
			</div>


			<div class="formGroup formGroup-50">
				<label><?= lang("Trade_license"); ?></label>

				<?php
				if ($trade_license != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $trade_license; ?>" target="_blank">
						<button>View Trade license Letter</button>
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
				<label><?= lang("NOC"); ?></label>

				<?php
				if ($noc_letter != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $noc_letter; ?>" target="_blank">
						<button>View NOC Letter</button>
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
				<label><?= lang("Commitment_letter"); ?></label>

				<?php
				if ($com_let != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $com_let; ?>" target="_blank">
						<button>View Letter</button>
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
				<label><?= lang("Legal_status"); ?></label>

				<?php
				if ($legal_stt != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $legal_stt; ?>" target="_blank">
						<button>View Legal Status</button>
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



		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Contacts_list'); ?></h2>


			<div class="dataContainer" id="listData">

				<?php
				$qu_atps_list_contacts_sel = "SELECT * FROM  `atps_list_contacts` WHERE `atp_id` = $atp_id";
				$qu_atps_list_contacts_EXE = mysqli_query($KONN, $qu_atps_list_contacts_sel);
				if (mysqli_num_rows($qu_atps_list_contacts_EXE)) {
					while ($atps_list_contacts_REC = mysqli_fetch_assoc($qu_atps_list_contacts_EXE)) {
						$contact_id = (int) $atps_list_contacts_REC['contact_id'];
						$contact_name = $atps_list_contacts_REC['contact_name'];
						$contact_phone = $atps_list_contacts_REC['contact_phone'];
						$contact_email = $atps_list_contacts_REC['contact_email'];
						$contact_designation = $atps_list_contacts_REC['contact_designation'];
						$atp_id = (int) $atps_list_contacts_REC['atp_id'];
						?>


						<div class="dataElement data-list-view" id="contact-<?= $contact_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("Name"); ?></div>
								<div class="value"><?= $contact_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("phone"); ?></div>
								<div class="value"><?= $contact_phone; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Email"); ?></div>
								<div class="value"><?= $contact_email; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Designation"); ?></div>
								<div class="value"><?= $contact_designation; ?></div>
							</div>
						</div>

						<?php
					}
				}

				?>
			</div>


			<div class="formGroup formGroup-100">
				<br>
				<br>
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
						data: { "form": 'init_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
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