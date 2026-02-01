<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Accreditation");
$pageId = 200;
$subPageId = 100;
include("app/assets.php");

//get atp current phase
		$currentPhaseId = 0;
		$is_phase_ok = 0;
		
	$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $ATP_ID";
	$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
	$atps_list_DATA;
	if(mysqli_num_rows($qu_atps_list_EXE)){
		$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
		$currentPhaseId = ( int ) $atps_list_DATA['phase_id'];
		$is_phase_ok = ( int ) $atps_list_DATA['is_phase_ok'];
	}

?>


<div class="stagesContainer">
	<div class="stagesPhases">
<?php
$currentPhaseTitle = "";
$currentPhaseDesc = "";
$currentPhasetime = "";
$form_status = "";

	$qu_a_registration_phases_sel = "SELECT * FROM  `a_registration_phases` ORDER BY `phase_id` ASC";
	$qu_a_registration_phases_EXE = mysqli_query($KONN, $qu_a_registration_phases_sel);
	if(mysqli_num_rows($qu_a_registration_phases_EXE)){
		$currentProgress = 0;

		while($a_registration_phases_REC = mysqli_fetch_assoc($qu_a_registration_phases_EXE)){
			$phase_id = ( int ) $a_registration_phases_REC['phase_id'];
			$phase_title = "".$a_registration_phases_REC['phase_title'];
			$table_check = "".$a_registration_phases_REC['table_check'];
			
			
			$thsIcon = 'fa-hourglass-half';
			if ($phase_id < $currentPhaseId) {
				//its done
				$thsIcon = 'fa-check';
				$currentProgress += 13;
			} else if ($phase_id > $currentPhaseId) {
				//its pending
				$thsIcon = 'fa-hourglass-half';

			} else if ($phase_id == $currentPhaseId) {
				//its current
				$currentProgress += 10;
				$thsIcon = 'fa-list-check';
				if ($is_phase_ok == 0) {
					$thsIcon = 'fa-xmark';
				}
				
				$currentPhaseTitle = "" . $a_registration_phases_REC['phase_title'];
				$currentPhaseDesc = "" . $a_registration_phases_REC['phase_description'];
				$currentPhasetime = "" . $a_registration_phases_REC['phase_timeline'];
			}


			if ($table_check != "") {
				$qu_rcGet_sel = "SELECT `form_status`, `rc_comment` FROM  `$table_check` WHERE `atp_id` = $USER_ID";
				$qu_rcGet_EXE = mysqli_query($KONN, $qu_rcGet_sel);
				if (mysqli_num_rows($qu_rcGet_EXE)) {
					$rcGet_DATA = mysqli_fetch_assoc($qu_rcGet_EXE);
					$form_status = "" . $rcGet_DATA['form_status'];
					$rc_comment = "" . $rcGet_DATA['rc_comment'];
				}
			}





?>
		<div class="phase <?=$classes; ?>">
			<i class="fa-solid <?= $thsIcon; ?>"></i>
			<span><?=$phase_title; ?></span>
		</div>
<?php
		}
	}
?>

		<div class="progressDec">
			<div class="progressPercent" style="width:<?= $currentProgress; ?>%;"></div>
		</div>
	</div>





	<div class="row" style="width: 90%;">
				<div class="col-2">
					<div class="parView">
						<h2><?= $currentPhaseTitle; ?></h2>
						<p><?= nl2br($currentPhaseDesc); ?></p>
						<br><br>
						<h2>Time Frame</h2>
						<p><?= nl2br($currentPhasetime); ?></p>

						<?php
						if ($form_status == 'review' || $form_status == 'rejected') {
							?>
							<br><br>
							<h2>Comments</h2>
							<p style="color:red;"><?= nl2br($rc_comment); ?></p>
							<?php
						}
						?>



					</div>
				</div>
				<div class="col-2">
					<div class="parView">
						<h2>Status List:</h2>
					</div>
					<div class="todoList">
						<?php
						//echo $form_status;
						
						if ((($form_status == 'review') || ($form_status == 'pending_submission') || ($is_phase_ok == 1))) {

							$qu_a_registration_phases_todos_sel = "SELECT * FROM  `a_registration_phases_todos` WHERE ((`phase_id` = $currentPhaseId) AND (`is_hidden` = 0))";
							$qu_a_registration_phases_todos_EXE = mysqli_query($KONN, $qu_a_registration_phases_todos_sel);
							if (mysqli_num_rows($qu_a_registration_phases_todos_EXE)) {
								$allGood = true;
								while ($a_registration_phases_todos_REC = mysqli_fetch_assoc($qu_a_registration_phases_todos_EXE)) {
									$todo_id = (int) $a_registration_phases_todos_REC['todo_id'];
									$todo_title = "" . $a_registration_phases_todos_REC['todo_title'];
									//$todo_description = $a_registration_phases_todos_REC['todo_description'];
									$is_submit = (int) $a_registration_phases_todos_REC['is_submit'];
									$todo_link = "" . $a_registration_phases_todos_REC['todo_link'];

									$table_check = "" . $a_registration_phases_todos_REC['table_check'];
									$col_check = "" . $a_registration_phases_todos_REC['col_check'];

									$thsBtn = 'fill';
									$thsCss = 'currentBtn';


									/*
									Finished:
									$thsIcon = 'fa-circle-check';
									pending:
									$thsIcon = 'fa-circle-xmark';
									default:
									$thsIcon = 'fa-circle';
									*/
									$thsIcon = 'fa-circle-xmark';
									//do filling check
									if ($is_submit == 0) {
										// $table_check = '';
										if ($table_check != '') {

											if ($col_check == '') {
												$qu_CheckFill_sel = "SELECT COUNT(*) FROM  `$table_check` WHERE `atp_id` = $USER_ID";
												$qu_CheckFill_EXE = mysqli_query($KONN, $qu_CheckFill_sel);
												if (mysqli_num_rows($qu_CheckFill_EXE)) {
													$CheckFill_DATA = mysqli_fetch_array($qu_CheckFill_EXE);
													$totRecs = (int) $CheckFill_DATA[0];
													if ($_SESSION['is_contact'] == false) {
														if ($todo_id == 2 && $totRecs == 1) {
															$totRecs = 0;
														}
													}


													if ($totRecs == 0) {
														$thsIcon = 'fa-circle-xmark';
														$allGood = false;
													} else {
														$thsIcon = 'fa-circle-check';
														$thsBtn = 'Edit';
														$thsCss = 'finishedtBtn';
													}
												}
											} else {
												$qu_CheckFill_sel = "SELECT `$col_check` FROM  `$table_check` WHERE `atp_id` = $USER_ID";
												$qu_CheckFill_EXE = mysqli_query($KONN, $qu_CheckFill_sel);
												if (mysqli_num_rows($qu_CheckFill_EXE)) {
													$CheckFill_DATA = mysqli_fetch_array($qu_CheckFill_EXE);
													$dt = '' . $CheckFill_DATA[0];
													if ($dt == '') {
														$thsIcon = 'fa-circle-xmark';
														$allGood = false;
													} else {
														$thsIcon = 'fa-circle-check';
														$thsBtn = 'Edit';
														$thsCss = 'finishedtBtn';
													}
												} else {
													$thsIcon = 'fa-circle-xmark';
													$allGood = false;
												}
											}
										}
									}

									if ($is_submit == 1) {
										$thsBtn = 'Submit';
										$thsCss = 'pendingBtn';
										$thsIcon = 'fa-circle';
									}

									$showBtn = true;
									if ($is_submit == 1 && $allGood == false) {
										$showBtn = false;
									}
									?>
									<div class="todoItem">
										<span><i class="fa-solid <?= $thsIcon; ?>"></i> <?= $todo_title; ?></span>
										<?php
										if ($showBtn) {
											?>
											<a class="<?= $thsCss; ?>" href="<?= $todo_link; ?>"><?= $thsBtn; ?></a>
											<?php
										}
										?>
									</div>
									<?php
								}
							}


						} else if ($form_status == 'rejected') {
							// phase is denied
						
							?>
								<p style="color: red;">Submission is Rejected</p>
							<?php
						} else {
							// phase is pending
							if ($currentPhaseId == 3) {
								?>

									<?php
									//check if 008 has been submitted 
									$visit_date = "";
									$qu_eqa_008_sel = "SELECT `visit_date` FROM  `eqa_008` WHERE `atp_id` = $USER_ID";
									$qu_eqa_008_EXE = mysqli_query($KONN, $qu_eqa_008_sel);
									if (mysqli_num_rows($qu_eqa_008_EXE)) {
										$eqa_008_DATA = mysqli_fetch_assoc($qu_eqa_008_EXE);
										$visit_date = "" . $eqa_008_DATA['visit_date'];
										?>
										<p>Our EQA department will visit your institue on : <?= $visit_date; ?></p>
										<br>
										<p>Further instructions has been sent by email</p>


									<?php

									} else {
										?>
										<p>Pending EQA department</p>

									<?php
									}

							} else {
								?>
									<p>Pending for Approvals</p>
								<?php
							}
						}
						?>

					</div>
				</div>
			</div>


</div>




<?php
include("app/footer.php");
?>
