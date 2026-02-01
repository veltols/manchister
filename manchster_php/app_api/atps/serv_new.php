<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (isset($_POST['stage_no'])) {

		$stage_no = (int) test_inputs($_POST['stage_no']);
		if ($stage_no == 1) {



			if (
				isset($_POST['atp_name']) &&
				isset($_POST['contact_name']) &&
				isset($_POST['contact_name_designation']) &&
				isset($_POST['atp_email']) &&
				isset($_POST['atp_phone']) &&
				isset($_POST['emirate_id']) &&
				isset($_POST['accreditation_type']) &&
				isset($_POST['atp_type_id'])
			) {


				//STAGE NO 1 ----------------------------------------------------------------




				//todo
				//authorize inputs


				$atp_name = "" . test_inputs($_POST['atp_name']);
				$contact_name = encryptData("" . test_inputs($_POST['contact_name']));
				$contact_name_designation = encryptData("" . test_inputs($_POST['contact_name_designation']));
				$atp_email = encryptData("" . test_inputs($_POST['atp_email']));
				$atp_phone = encryptData("" . test_inputs($_POST['atp_phone']));
				$emirate_id = (int) test_inputs($_POST['emirate_id']);
				$accreditation_type = (int) test_inputs($_POST['accreditation_type']);
				$atp_type_id = (int) test_inputs($_POST['atp_type_id']);
				$added_by = $USER_ID;
				$added_date = date('Y-m-d H:i:00');

				//get city code



				$atp_nameE = encryptData("" . test_inputs($atp_name));
				$atpsListQryIns = "INSERT INTO `atps_list` (
						`atp_name`, 
						`contact_name`, 
						`atp_email`, 
						`atp_phone`, 
						`emirate_id`, 
						`accreditation_type`, 
						`atp_type_id`, 
						`added_by`, 
						`added_date` 
					) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ? );";

				if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
					if (mysqli_stmt_bind_param($atpsListStmtIns, "ssssiiiis", $atp_nameE, $contact_name, $atp_email, $atp_phone, $emirate_id, $accreditation_type, $atp_type_id, $added_by, $added_date)) {
						if (mysqli_stmt_execute($atpsListStmtIns)) {
							$atp_id = (int) mysqli_insert_id($KONN);
							mysqli_stmt_close(statement: $atpsListStmtIns);

							//insert new contact

							$qu_atps_list_contacts_ins = "INSERT INTO `atps_list_contacts` (
																		`contact_name`, 
																		`contact_phone`, 
																		`contact_email`, 
																		`contact_designation`, 
																		`atp_id` 
																	) VALUES (
																		'" . $contact_name . "', 
																		'" . $atp_phone . "', 
																		'" . $atp_email . "', 
																		'" . $contact_name_designation . "', 
																		'" . $atp_id . "' 
																	);";

							if (!mysqli_query($KONN, $qu_atps_list_contacts_ins)) {
								die("General Error - 12-4568");
							}

							//insert user record
							$record_id = 0;
							$user_id = $atp_id;
							$user_type = 'atp';
							$int_ext = "ext";
							$user_family = "atps_list";

							$usersListQryIns = "INSERT INTO `users_list` (
									`user_id`, 
									`user_email`, 
									`user_type`, 
									`int_ext`, 
									`user_family` 
								) VALUES ( ?, ?, ?, ?, ? );";

							if ($usersListStmtIns = mysqli_prepare($KONN, $usersListQryIns)) {
								if (mysqli_stmt_bind_param($usersListStmtIns, "issss", $user_id, $atp_email, $user_type, $int_ext, $user_family)) {
									if (mysqli_stmt_execute($usersListStmtIns)) {
										$record_id = (int) mysqli_insert_id($KONN);
										mysqli_stmt_close(statement: $usersListStmtIns);
										//insert atp password

										$pass_value = '$2y$10$vyDJsXaSPt03tXcY8z/lBuLHIEyTFxbHkA7eB2L1uA36d.VaXzs/e';

										$atpsListPassQryIns = "INSERT INTO `atps_list_pass` (
												`pass_value`, 
												`atp_id`, 
												`is_active` 
											) VALUES ( ?, ?, 1 );";

										if ($atpsListPassStmtIns = mysqli_prepare($KONN, $atpsListPassQryIns)) {
											if (mysqli_stmt_bind_param($atpsListPassStmtIns, "si", $pass_value, $atp_id)) {
												if (mysqli_stmt_execute($atpsListPassStmtIns)) {
													$pass_id = (int) mysqli_insert_id($KONN);
													mysqli_stmt_close($atpsListPassStmtIns);
													//update atp ref
													$atp_ref = getAtpRef($atp_name, $emirate_id, $atp_id);
													$qu_atps_list_updt = "UPDATE  `atps_list` SET 
										`atp_ref` = '" . $atp_ref . "' WHERE `atp_id` = $atp_id;";
													if (mysqli_query($KONN, $qu_atps_list_updt)) {

														if (InsertAtpLog("ATP Added", $atp_id, 'employees_list', $added_by, 'R&C')) {
															$IAM_ARRAY['success'] = true;
															$IAM_ARRAY['message'] = "Succeed";
														} else {
															reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
														}



													} else {
														//uodate failed 
														reportError(mysqli_error($KONN), 'employees_list', $EMPLOYEE_ID);
													}


												} else {
													//Execute failed 
													reportError(mysqli_stmt_error($atpsListPassStmtIns), 'employees_list', $EMPLOYEE_ID);
												}
											} else {
												//bind failed 
												reportError(mysqli_stmt_error($atpsListPassStmtIns), 'employees_list', $EMPLOYEE_ID);
											}
										} else {
											//prepare failed 
											reportError('atps_list_pass failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
										}














									} else {
										//Execute failed 
										reportError(mysqli_stmt_error($usersListStmtIns), 'employees_list', $EMPLOYEE_ID);
									}
								} else {
									//bind failed 
									reportError(mysqli_stmt_error($usersListStmtIns), 'employees_list', $EMPLOYEE_ID);
								}
							} else {
								//prepare failed 
								reportError('users_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
							}













						} else {
							//Execute failed 
							reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "ERR-12-57";
						}
					} else {
						//bind failed 
						reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-56";
					}
				} else {
					//prepare failed 
					reportError('atps_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-55";
				}















				//STAGE NO 1 ----------------------------------------------------------------








			} else {
				//No request for stage no1
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-4556";
			}







		} else if ($stage_no == 2) {
			/*
																	 //STAGE NO 2 ----------------------------------------------------------------
																		 if( isset($_POST['project_id']) &&
																		 isset($_POST['area_id']) 
																		 ){
																	 
																		 $project_id = ( int ) test_inputs($_POST['project_id']);
																		 $area_id = ( int ) test_inputs($_POST['area_id']);
																		 $is_draft = 0;
																		 $stage_no = 3;
																			 if( $project_id != 0 ){
																				 //update project
																				 $qu_portal_lists_areas_projects_updt = "UPDATE  `portal_lists_areas_projects` SET 
																							 `is_draft` = '".$is_draft."', 
																							 `area_id` = '".$area_id."', 
																							 `stage_no` = '".$stage_no."'
																							 WHERE ((`added_by` = $USER_ID) AND (`project_id` = $project_id));";
																	 
																				 if(mysqli_query($KONN, $qu_portal_lists_areas_projects_updt)){
																					 $IAM_ARRAY['success'] = true;
																					 $IAM_ARRAY['message'] = "Succeed";
																				 }  else {
																					 //error update stage 02
																					 $IAM_ARRAY['success'] = false;
																					 $IAM_ARRAY['message'] = "ERR-12-4557";
																				 }
																			 }
																	 
																		 } else {
																			 //No request for stage no2
																			 $IAM_ARRAY['success'] = false;
																			 $IAM_ARRAY['message'] = "ERR-12-4557";
																		 }
																	 //STAGE NO 2 ----------------------------------------------------------------
																	 */
		}





	} else {
		//error no stage
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-200";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


