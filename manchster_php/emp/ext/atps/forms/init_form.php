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

		?>

		<?php
		$pageTitler = lang('Initial Registration Form');
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



			<div class="formGroup formGroup-100">
				<label><?= lang("Sector"); ?></label>
				<select readonly disabled data-name="industry_id" data-type="int" data-den="0" data-req="1"
					class="inputer need_data atp_category_id" data-from="<?= $POINTER; ?>get_select_data"
					data-dest="atps_list_industries" data-initial="<?= $atp_category_id; ?>"></select>

			</div>

			<div class="formGroup formGroup-100">
				<br><br>
			</div>

			
			<h2 class="formPanelTitle"><?= lang('Address_Details'); ?></h2>


			<div class="formGroup formGroup-50">
				<label><?= lang("emirate"); ?></label>
				<select data-name="emirate_id" data-type="int" data-den="0" data-req="1" class="inputer need_data"
					data-from="<?= $POINTER; ?>get_select_data" data-dest="sys_countries_cities"
					data-initial="<?= $emirate_id; ?>" readonly disabled></select>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Area"); ?></label>

				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'area_name',
					'dataDen' => '',
					'dataValue' => $area_name,
					'dataClass' => 'inputer area_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("street"); ?></label>

				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'street_name',
					'dataDen' => '',
					'dataValue' => $street_name,
					'dataClass' => 'inputer street_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Building_NO"); ?></label>

				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'building_name',
					'dataDen' => '',
					'dataValue' => $building_name,
					'dataClass' => 'inputer building_name',
					'isDisabled' => 1,
					'isReadOnly' => 1,
					'isRequired' => '1'
				];
				echo build_input(...$props);
				?>
			</div>
			
			


			<div class="formGroup formGroup-100">
				<br><br>
			</div>


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


			


			<h2 class="formPanelTitle"><?= lang('Targeted_Qualifications'); ?></h2>


			<div class="dataContainer" id="listData">

				<?php
				$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE `atp_id` = $atp_id";
				$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
				if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
					while ($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)) {
						$qualification_id = ( int ) $atps_list_qualifications_REC['qualification_id'];
						$qualification_type = $atps_list_qualifications_REC['qualification_type'];
						$qualification_name = $atps_list_qualifications_REC['qualification_name'];
						$qualification_category = $atps_list_qualifications_REC['qualification_category'];
						$emirates_level = $atps_list_qualifications_REC['emirates_level'];
						$qulaification_credits = $atps_list_qualifications_REC['qulaification_credits'];
						$mode_of_delivery = $atps_list_qualifications_REC['mode_of_delivery'];
						?>


						<div class="dataElement data-list-view" id="contact-<?= $qualification_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("qualification_name"); ?></div>
								<div class="value"><?= $qualification_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("qualification_type"); ?></div>
								<div class="value"><?= $qualification_type; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("qualification_category"); ?></div>
								<div class="value"><?= $qualification_category; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("emirates_level"); ?></div>
								<div class="value"><?= $emirates_level; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("qulaification_credits"); ?></div>
								<div class="value"><?= $qulaification_credits; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("mode_of_delivery"); ?></div>
								<div class="value"><?= $mode_of_delivery; ?></div>
							</div>
						</div>

						<?php
					}
				}

				?>
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






			


			<h2 class="formPanelTitle"><?= lang('Statistics / Estimation of expected number of learner and graduates '); ?></h2>
			<h4 class="formPanelTitle"><?= lang('Learners registered in Vocational Qualifications (if Applicable):  '); ?></h4>

				
			<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Qualification"); ?></div>
							<div class="th"><?= date('Y') - 4; ?></div>
							<div class="th"><?= date('Y') - 3; ?></div>
							<div class="th"><?= date('Y') - 2; ?></div>
							<div class="th"><?= date('Y') - 1; ?></div>
						</div>
					</div>
					<div class="tableBody" id="listData">
						<?php
						
							$qu_atps_learners_statistics_sel = "SELECT * FROM  `atps_learners_statistics` WHERE ((`statistic_type` = 'registered') AND (`atp_id` = $atp_id))";
							$qu_atps_learners_statistics_EXE = mysqli_query($KONN, $qu_atps_learners_statistics_sel);
							if(mysqli_num_rows($qu_atps_learners_statistics_EXE)){
								while($atps_learners_statistics_REC = mysqli_fetch_assoc($qu_atps_learners_statistics_EXE)){
									$statistic_id = ( int ) $atps_learners_statistics_REC['statistic_id'];
									$qualification_id = ( int ) $atps_learners_statistics_REC['qualification_id'];
									$y1_value = ( int ) $atps_learners_statistics_REC['y1_value'];
									$y2_value = ( int ) $atps_learners_statistics_REC['y2_value'];
									$y3_value = ( int ) $atps_learners_statistics_REC['y3_value'];
									$y4_value = ( int ) $atps_learners_statistics_REC['y4_value'];
									$statistic_type = $atps_learners_statistics_REC['statistic_type'];

									$qualification_name = "";
								$qu_atps_list_qualifications_sel = "SELECT `qualification_name` FROM  `atps_list_qualifications` WHERE `qualification_id` = $qualification_id";
								$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
								if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
									$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
									$qualification_name = $atps_list_qualifications_DATA['qualification_name'];
								}
								?>
								
								<div class="tr levelRow">
									<div class="td" style="text-align:center;"><?=$qualification_name; ?></div>
									<div class="td" style="text-align:center;"><?=$y1_value; ?></div>
									<div class="td" style="text-align:center;"><?=$y2_value; ?></div>
									<div class="td" style="text-align:center;"><?=$y3_value; ?></div>
									<div class="td" style="text-align:center;"><?=$y4_value; ?></div>
								</div>
								<?php
								}
							}
						?>
					</div>
				</div>





				<h2 class="formPanelTitle"><br></h2>
				
			<h4 class="formPanelTitle"><?= lang('Awarded Certificates in vocational Qualifications (if Applicable):'); ?></h4>

				
<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Qualification"); ?></div>
				<div class="th"><?= date('Y') - 3; ?></div>
				<div class="th"><?= date('Y') - 2; ?></div>
				<div class="th"><?= date('Y') - 1; ?></div>
				<div class="th"><?= date('Y'); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData">
			<?php
			
				$qu_atps_learners_statistics_sel = "SELECT * FROM  `atps_learners_statistics` WHERE ((`statistic_type` = 'awarded') AND (`atp_id` = $atp_id))";
				$qu_atps_learners_statistics_EXE = mysqli_query($KONN, $qu_atps_learners_statistics_sel);
				if(mysqli_num_rows($qu_atps_learners_statistics_EXE)){
					while($atps_learners_statistics_REC = mysqli_fetch_assoc($qu_atps_learners_statistics_EXE)){
						$statistic_id = ( int ) $atps_learners_statistics_REC['statistic_id'];
						$qualification_id = ( int ) $atps_learners_statistics_REC['qualification_id'];
						$y1_value = ( int ) $atps_learners_statistics_REC['y1_value'];
						$y2_value = ( int ) $atps_learners_statistics_REC['y2_value'];
						$y3_value = ( int ) $atps_learners_statistics_REC['y3_value'];
						$y4_value = ( int ) $atps_learners_statistics_REC['y4_value'];
						$statistic_type = $atps_learners_statistics_REC['statistic_type'];

						$qualification_name = "";
					$qu_atps_list_qualifications_sel = "SELECT `qualification_name` FROM  `atps_list_qualifications` WHERE `qualification_id` = $qualification_id";
					$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
					if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
						$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
						$qualification_name = $atps_list_qualifications_DATA['qualification_name'];
					}
					?>
					
					<div class="tr levelRow">
						<div class="td" style="text-align:center;"><?=$qualification_name; ?></div>
						<div class="td" style="text-align:center;"><?=$y1_value; ?></div>
						<div class="td" style="text-align:center;"><?=$y2_value; ?></div>
						<div class="td" style="text-align:center;"><?=$y3_value; ?></div>
						<div class="td" style="text-align:center;"><?=$y4_value; ?></div>
					</div>
					<?php
					}
				}
			?>
		</div>
	</div>
	
	<h2 class="formPanelTitle"><br></h2>

	<h2 class="formPanelTitle"><?= lang('Electronics systems and platforms'); ?></h2>

	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("platform_name"); ?></div>
				<div class="th"><?= lang("platform_purpose"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData">
			<?php
				$qu_atps_electronic_systems_sel = "SELECT * FROM  `atps_electronic_systems` WHERE `atp_id` = $atp_id";
				$qu_atps_electronic_systems_EXE = mysqli_query($KONN, $qu_atps_electronic_systems_sel);
				if(mysqli_num_rows($qu_atps_electronic_systems_EXE)){
					while($atps_electronic_systems_REC = mysqli_fetch_assoc($qu_atps_electronic_systems_EXE)){
						$platform_id = ( int ) $atps_electronic_systems_REC['platform_id'];
						$platform_name = $atps_electronic_systems_REC['platform_name'];
						$platform_purpose = $atps_electronic_systems_REC['platform_purpose'];
						$atp_id = ( int ) $atps_electronic_systems_REC['atp_id'];
					?>
					<div class="tr levelRow">
						<div class="td" style="text-align:center;"><?=$platform_name; ?></div>
						<div class="td" style="text-align:center;"><?=$platform_purpose; ?></div>
					</div>
					<?php
					}
				}
			?>
		</div>
	</div>

	<h2 class="formPanelTitle"><br><br></h2>


	<h2 class="formPanelTitle"><?= lang('Attachments'); ?></h2>

	<div class="formGroup formGroup-50">
				<label><?= lang("delivery_plan"); ?></label>

				<?php
				if ($delivery_plan != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $delivery_plan; ?>" target="_blank">
						<button>View File</button>
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
				<label><?= lang("organizational_chart"); ?></label>

				<?php
				if ($org_chart != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $org_chart; ?>" target="_blank">
						<button>View File</button>
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
				<label><?= lang("site_plan"); ?></label>

				<?php
				if ($site_plan != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $site_plan; ?>" target="_blank">
						<button>View File</button>
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
				<label><?= lang("sed_form"); ?></label>

				<?php
				if ($sed_form != '') {
					?>
					<a href="../<?= $POINTER; ?>uploads/<?= $sed_form; ?>" target="_blank">
						<button>View File</button>
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
					<span class="label"><?= lang("Approve"); ?></span>
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
						data: { "form": 'init_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
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