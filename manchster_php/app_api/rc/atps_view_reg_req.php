<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'add_init_data';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;




$added_date = "";
$submitted_date = "";
$is_submitted = 0;
$form_status = "";
$qu_atps_prog_register_req_sel = "SELECT * FROM  `atps_prog_register_req` WHERE `atp_id` = $atp_id";
$qu_atps_prog_register_req_EXE = mysqli_query($KONN, $qu_atps_prog_register_req_sel);
if (mysqli_num_rows($qu_atps_prog_register_req_EXE)) {
	$atps_prog_register_req_DATA = mysqli_fetch_assoc($qu_atps_prog_register_req_EXE);
	$form_id = (int) $atps_prog_register_req_DATA['form_id'];
	$atp_id = (int) $atps_prog_register_req_DATA['atp_id'];
	$added_date = $atps_prog_register_req_DATA['added_date'];
	$submitted_date = $atps_prog_register_req_DATA['submitted_date'];
	$is_submitted = (int) $atps_prog_register_req_DATA['is_submitted'];
	$form_status = $atps_prog_register_req_DATA['form_status'];
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
		include("atp_view/atp_header.php");
		?>





		<!-- START OF FORM PANEL -->
		<!--div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Prospective ATP Information '); ?></h2>

			<div class="dataContainer" id="listData">
				Error : wrong mime type
			</div>



		</div-->
		<!-- END OF FORM PANEL -->


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('location_details'); ?></h2>

			<div class="dataContainer" id="listData">

				<?php

				$qu_atps_list_locations_sel = "SELECT * FROM  `atps_list_locations` WHERE `atp_id` = $atp_id";
				$qu_atps_list_locations_EXE = mysqli_query($KONN, $qu_atps_list_locations_sel);
				if (mysqli_num_rows($qu_atps_list_locations_EXE)) {
					while ($atps_list_locations_REC = mysqli_fetch_assoc($qu_atps_list_locations_EXE)) {
						$location_id = (int) $atps_list_locations_REC['location_id'];
						$location_name = "" . $atps_list_locations_REC['location_name'];
						$classrooms_count = "" . $atps_list_locations_REC['classrooms_count'];
						$floor_map = "" . $atps_list_locations_REC['floor_map'];
						?>


						<div class="dataElement data-list-view" id="location-<?= $location_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("Name"); ?></div>
								<div class="value"><?= $location_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Classroom"); ?></div>
								<div class="value"><?= $classrooms_count; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Floor_Map"); ?></div>
								<?php
								if ($floor_map != '') {
									?>
									<a href="../<?= $POINTER; ?>uploads/<?= $floor_map; ?>" target="_blank">
										<button>View</button>
									</a>
									<?php
								} else {
									?>
									<div class="value"><?= lang("Not_Available"); ?></div>
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
		<!-- END OF FORM PANEL -->







		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Qualifications'); ?></h2>
			<div class="dataContainer" id="listData">

				<?php

				$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE `atp_id` = $atp_id";
				$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
				if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
					while ($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)) {
						$qualification_id = (int) $atps_list_qualifications_REC['qualification_id'];
						$qualification_code = "" . $atps_list_qualifications_REC['qualification_code'];
						$qualification_name = "" . $atps_list_qualifications_REC['qualification_name'];
						$qualification_provider = "" . $atps_list_qualifications_REC['qualification_provider'];
						$qualification_type = "" . $atps_list_qualifications_REC['qualification_type'];
						$learners_entry_no = (int) $atps_list_qualifications_REC['learners_entry_no'];
						$academic_session = "" . $atps_list_qualifications_REC['academic_session'];

						?>


						<div class="dataElement data-list-view" id="q-<?= $qualification_id; ?>">
							<div class="dataView col-3">
								<div class="property"><?= lang("Name"); ?></div>
								<div class="value"><?= $qualification_name; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Provider"); ?></div>
								<div class="value"><?= $qualification_provider; ?></div>
							</div>
							<div class="dataView col-3">
								<div class="property"><?= lang("Type"); ?></div>
								<div class="value"><?= $qualification_type; ?></div>
							</div>
						</div>

						<?php
					}
				}

				?>
			</div>
		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
















		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Learner_Enrolled'); ?></h2>

			<?php
			$le1 = 0;
			$le2 = 0;
			$le3 = 0;
			$le4 = 0;
			$le5 = 0;
			$le6 = 0;
			$le7 = 0;
			$le8 = 0;
			$le9 = 0;
			$qu_atps_learner_enrolled_sel = "SELECT * FROM  `atps_learner_enrolled` WHERE `atp_id` = $atp_id";
			$qu_atps_learner_enrolled_EXE = mysqli_query($KONN, $qu_atps_learner_enrolled_sel);
			if (mysqli_num_rows($qu_atps_learner_enrolled_EXE)) {
				$atps_learner_enrolled_DATA = mysqli_fetch_assoc($qu_atps_learner_enrolled_EXE);
				$record_id = (int) $atps_learner_enrolled_DATA['record_id'];
				$le1 = (int) $atps_learner_enrolled_DATA['le1'];
				$le2 = (int) $atps_learner_enrolled_DATA['le2'];
				$le3 = (int) $atps_learner_enrolled_DATA['le3'];
				$le4 = (int) $atps_learner_enrolled_DATA['le4'];
				$le5 = (int) $atps_learner_enrolled_DATA['le5'];
				$le6 = (int) $atps_learner_enrolled_DATA['le6'];
				$le7 = (int) $atps_learner_enrolled_DATA['le7'];
				$le8 = (int) $atps_learner_enrolled_DATA['le8'];
				$le9 = (int) $atps_learner_enrolled_DATA['le9'];
			}

			?>


			<div class="formGroup formGroup-100">
				<label><?= lang("Past learners summary"); ?></label>


				<table>
					<thead>
						<tr>
							<th style="width:40%;"></th>
							<th><?= date('Y') - 2; ?></th>
							<th><?= date('Y') - 1; ?></th>
							<th><?= date('Y'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?= lang("No. of learners enrolled over the past 3 years"); ?></td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le1',
									'dataDen' => '',
									'dataValue' => $le1,
									'dataClass' => 'inputer le1',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le2',
									'dataDen' => '',
									'dataValue' => $le2,
									'dataClass' => 'inputer le2',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le3',
									'dataDen' => '',
									'dataValue' => $le3,
									'dataClass' => 'inputer le3',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
						</tr>
						<tr>
							<td colspan="4"><br></td>
						</tr>
						<tr>
							<td><?= lang("No. of learners who obtained certificates over the past 3 years"); ?></td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le4',
									'dataDen' => '',
									'dataValue' => $le4,
									'dataClass' => 'inputer le4',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le5',
									'dataDen' => '',
									'dataValue' => $le5,
									'dataClass' => 'inputer le5',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le6',
									'dataDen' => '',
									'dataValue' => $le6,
									'dataClass' => 'inputer le6',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<div class="formGroup formGroup-100">
				<br>
				<hr>
				<br>
			</div>

			<div class="formGroup formGroup-100">
				<label><?= lang("Enrollment projection for the next 3 years"); ?></label>

				<table>
					<thead>
						<tr>
							<th style="width:40%;"></th>
							<th><?= date('Y') + 1; ?></th>
							<th><?= date('Y') + 2; ?></th>
							<th><?= date('Y') + 3; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?= lang("No. of expected learners"); ?></td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le7',
									'dataDen' => '',
									'dataValue' => $le7,
									'dataClass' => 'inputer le7',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le8',
									'dataDen' => '',
									'dataValue' => $le8,
									'dataClass' => 'inputer le8',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
							<td>
								<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'le9',
									'dataDen' => '',
									'dataValue' => $le9,
									'dataClass' => 'inputer le9',
									'isDisabled' => 1,
									'isReadOnly' => 1,
									'isRequired' => '0',
									'dir' => 'ltr'
								];
								echo build_input(...$props);
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>


		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->




		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Electronic Systems and Platforms'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



			<div class="formGroup formGroup-100">
				<label><?= lang("Please list any electronic systems/platforms that you use in registering learners and/or delivering qualifications."); ?></label>

				<table border="1">
					<thead>
						<tr>
							<th style="width:40%;">Electronic System or Platform</th>
							<th>Purpose</th>
							<th style="width:3%;">---</th>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($m = 1; $m <= 1; $m++) {
							?>
							<tr>
								<td>
									<?php
									$props = [
										'dataType' => 'text',
										'dataName' => 'dddd1',
										'dataDen' => '',
										'dataValue' => '',
										'dataClass' => 'inputer dddd1',
										'isDisabled' => 1,
										'isReadOnly' => 1,
										'isRequired' => '1',
										'dir' => 'ltr'
									];
									echo build_input(...$props);
									?>
								</td>
								<td>
									<?php
									$props = [
										'dataType' => 'text',
										'dataName' => 'dddd2',
										'dataDen' => '',
										'dataValue' => '',
										'dataClass' => 'inputer dddd2',
										'isDisabled' => 1,
										'isReadOnly' => 1,
										'isRequired' => '1',
										'dir' => 'ltr'
									];
									echo build_input(...$props);
									?>
								</td>
							</tr>

							<?php
						}
						?>
					</tbody>
				</table>
			</div>










			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->









		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Faculty_Details'); ?></h2>

			<!-- ------------------------------------------------------------------------------------------------------------ -->
			<div class="formGroup formGroup-100">
				<label for="">List of Trainers</label>
			</div>
			<div class="dataContainer">
				<?php
				$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE ((`faculty_type` IN ( 4, 5 ) ) AND (`atp_id` = $atp_id))";
				$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
				if (mysqli_num_rows($qu_atps_list_faculties_EXE)) {
					while ($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)) {
						$faculty_id = (int) $atps_list_faculties_REC['faculty_id'];
						$faculty_name = $atps_list_faculties_REC['faculty_name'];
						$faculty_type = $atps_list_faculties_REC['faculty_type'];
						$faculty_spec = $atps_list_faculties_REC['faculty_spec'];
						//$faculty_cv   = $atps_list_faculties_REC['faculty_cv'];
				
						$thsType = 'Trainer';


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
						</div>

						<?php
					}
				}

				?>
			</div>
			<!-- ------------------------------------------------------------------------------------------------------------ -->

			<!-- ------------------------------------------------------------------------------------------------------------ -->
			<div class="formGroup formGroup-100">
				<label for="">List of Assessors</label>
			</div>
			<div class="dataContainer">
				<?php
				$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE ((`faculty_type` = '3') AND (`atp_id` = $atp_id))";
				$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
				if (mysqli_num_rows($qu_atps_list_faculties_EXE)) {
					while ($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)) {
						$faculty_id = (int) $atps_list_faculties_REC['faculty_id'];
						$faculty_name = $atps_list_faculties_REC['faculty_name'];
						$faculty_type = $atps_list_faculties_REC['faculty_type'];
						$faculty_spec = $atps_list_faculties_REC['faculty_spec'];
						//$faculty_cv   = $atps_list_faculties_REC['faculty_cv'];
				
						$thsType = 'Assessor';

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
						</div>

						<?php
					}
				}

				?>
			</div>
			<!-- ------------------------------------------------------------------------------------------------------------ -->

			<!-- ------------------------------------------------------------------------------------------------------------ -->
			<div class="formGroup formGroup-100">
				<label for="">List of IQA</label>
			</div>
			<div class="dataContainer">
				<?php
				$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE ((`faculty_type` = '1') AND (`atp_id` = $atp_id))";
				$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
				if (mysqli_num_rows($qu_atps_list_faculties_EXE)) {
					while ($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)) {
						$faculty_id = (int) $atps_list_faculties_REC['faculty_id'];
						$faculty_name = $atps_list_faculties_REC['faculty_name'];
						$faculty_type = $atps_list_faculties_REC['faculty_type'];
						$faculty_spec = $atps_list_faculties_REC['faculty_spec'];
						//$faculty_cv   = $atps_list_faculties_REC['faculty_cv'];
						$thsType = 'IQA';

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
						</div>

						<?php
					}
				}

				?>
			</div>
			<!-- ------------------------------------------------------------------------------------------------------------ -->
















		</div>
		<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->




		<?php
		/*





																																								   <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
																																								   <!-- START OF FORM PANEL -->
																																								   <div class="formPanel">
																																									   <h2 class="formPanelTitle"><?=lang('Eligibility_Criteria'); ?></h2>
																																								   <?php

																																										   $ec1 = "";
																																										   $ec2 = "";
																																										   $ec3 = "";
																																										   $ec4 = "";
																																										   $ec5 = "";
																																										   $ec6 = "";
																																										   $ec7 = "";
																																										   $ec8 = "";
																																										   $ec9 = "";
																																										   $ec10 = "";
																																									   $qu_atps_elegibility_sel = "SELECT * FROM  `atps_elegibility` WHERE `atp_id` = $atp_id";
																																									   $qu_atps_elegibility_EXE = mysqli_query($KONN, $qu_atps_elegibility_sel);
																																									   if(mysqli_num_rows($qu_atps_elegibility_EXE)){
																																										   $atps_elegibility_DATA = mysqli_fetch_assoc($qu_atps_elegibility_EXE);
																																										   $record_id = ( int ) $atps_elegibility_DATA['record_id'];
																																										   $ec1 = findAnswer( ( int ) $atps_elegibility_DATA['ec1'] );
																																										   $ec2 = "".$atps_elegibility_DATA['ec2'];
																																										   $ec3 = "".$atps_elegibility_DATA['ec3'];
																																										   $ec4 = "".$atps_elegibility_DATA['ec4'];
																																										   $ec5 = findAnswer( ( int ) $atps_elegibility_DATA['ec5'] );
																																										   $ec6 = "".$atps_elegibility_DATA['ec6'];
																																										   $ec7 = findAnswer( ( int ) $atps_elegibility_DATA['ec7'] );
																																										   $ec8 = "".$atps_elegibility_DATA['ec8'];
																																										   $ec9 = findAnswer( ( int ) $atps_elegibility_DATA['ec9'] );
																																										   $ec10 = "".$atps_elegibility_DATA['ec10'];
																																									   }


																																									   function findAnswer( $src ): string{

																																										   if( $src == 1 ){
																																											   return lang("YES");
																																										   } else if( $src == 2 ) {
																																											   return lang("NO");
																																										   } else if( $src == 3 ) {
																																											   return lang("Under_Process");
																																										   } else {
																																											   return "";
																																										   }

																																									   }
																																								   ?>

																																									   <table>
																																												   <thead>
																																													   <tr>
																																														   <th style="width:75%;"><?=lang("Eligibility_Criteria"); ?></th>
																																														   <th style="width:10%;"><?=lang("Value"); ?></th>
																																														   <th style="width:15%;"><?=lang("Notes"); ?></th>
																																													   </tr>
																																												   </thead>
																																												   <tbody>
																																													   <tr>
																																														   <td><?=lang("Legally recognized as a business entity"); ?></td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec1; ?></h6>
																																														   </td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec2; ?></h6>
																																														   </td>
																																													   </tr>
																																													   <tr>
																																														   <td colspan="3"><br></td>
																																													   </tr>


																																													   <tr>
																																														   <td><?=lang("Organizational_Structure"); ?></td>
																																														   <td>
																																														   <h6 style="text-align:center;"><?=$ec3; ?></h6>
																																														   </td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec4; ?></h6>
																																														   </td>
																																													   </tr>
																																													   <tr>
																																														   <td colspan="3"><br></td>
																																													   </tr>


																																													   <tr>
																																														   <td><?=lang("Policies/ Processes/ Procedures are available to support their operations"); ?></td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec5; ?></h6>
																																														   </td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec6; ?></h6>
																																														   </td>
																																													   </tr>
																																													   <tr>
																																														   <td colspan="3"><br></td>
																																													   </tr>


																																													   <tr>
																																														   <td><?=lang("Ability to demonstrate compliance with ATP quality standards"); ?></td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec7; ?></h6>
																																														   </td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec8; ?></h6>
																																														   </td>
																																													   </tr>
																																													   <tr>
																																														   <td colspan="3"><br></td>
																																													   </tr>


																																													   <tr>
																																														   <td><?=lang("An appropriate level of Insurance"); ?></td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec9; ?></h6>
																																														   </td>
																																														   <td>
																																															   <h6 style="text-align:center;"><?=$ec10; ?></h6>
																																														   </td>
																																													   </tr>
																																													   <tr>
																																														   <td colspan="3"><br></td>
																																													   </tr>
																																													   




																																												   </tbody>
																																											   </table>





																																								   </div>
																																								   <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



																																								   */
		?>










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
						data: { "form": 'reg_req_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
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