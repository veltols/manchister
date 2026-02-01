<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	

	if (
		isset($_POST['atp_name']) && 
		isset($_POST['contact_name']) && 
		isset($_POST['contact_name_designation']) && 
		isset($_POST['atp_email']) && 
		isset($_POST['atp_phone']) && 
		isset($_POST['emirate_id']) && 
		isset($_POST['atp_category_id']) && 
		isset($_POST['atp_type_id']) 
	) {


		$atp_id = 0;
		
		$atp_name = "".test_inputs($_POST['atp_name']);
		$atp_name_ar = $atp_name;
		$contact_name = "".test_inputs($_POST['contact_name']);
		$contact_name_designation = "".test_inputs($_POST['contact_name_designation']);
		$atp_email = "".test_inputs($_POST['atp_email']);
		$atp_phone = "".test_inputs($_POST['atp_phone']);
		$emirate_id = ( int ) test_inputs($_POST['emirate_id']);
		$atp_category_id = ( int ) test_inputs($_POST['atp_category_id']);
		$atp_type_id = ( int ) test_inputs($_POST['atp_type_id']);
		$atp_status_id = 1;

		
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');


		
		//check if email exists
		$tots = 0;
	$qu_users_list_sel = "SELECT COUNT(`record_id`) FROM  `users_list` WHERE `user_email` = '$atp_email'";
	$qu_users_list_EXE = mysqli_query($KONN, $qu_users_list_sel);
	$users_list_DATA;
	if(mysqli_num_rows($qu_users_list_EXE)){
		$users_list_DATA = mysqli_fetch_array($qu_users_list_EXE);
		$tots = ( int ) $users_list_DATA[0];
	}

	if( $tots == 0 ){




		$atpsListQryIns = "INSERT INTO `atps_list` (
										`atp_name`, 
										`atp_name_ar`, 
										`contact_name`, 
										`atp_email`, 
										`atp_phone`, 
										`emirate_id`, 
										`added_by`, 
										`added_date`, 
										`atp_category_id`, 
										`atp_type_id`, 
										`atp_status_id` 
										) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssiisiii", $atp_name, $atp_name_ar, $contact_name, $atp_email, $atp_phone, $emirate_id, $added_by, $added_date, $atp_category_id, $atp_type_id, $atp_status_id)) {
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
								die("General Error - 12-11");
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
										//temp pass
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








	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Error 654, Email already exists in records";
	}

		
		



	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}



} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}








header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));