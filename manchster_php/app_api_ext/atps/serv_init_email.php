<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (isset($_POST['atp_id'])) {


		//todo
		//authorize inputs


		$atp_id = (int) test_inputs($_POST['atp_id']);
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');

		//todo
		//send email
		//update atp status
		//update atp log

		$status_id = 2;

		$atpsListQryUpdt = "UPDATE  `atps_list` SET 
							`atp_status_id` = ? 
							WHERE ( ( `atp_id` = ? ) ) ";
		if ($atpsListStmtUpdt = mysqli_prepare($KONN, $atpsListQryUpdt)) {
			if (mysqli_stmt_bind_param($atpsListStmtUpdt, "ii", $status_id, $atp_id)) {
				if (mysqli_stmt_execute($atpsListStmtUpdt)) {

					mysqli_stmt_close($atpsListStmtUpdt);
					if (InsertAtpLog("ATP Init Email Sent", $atp_id, 'employees_list', $added_by, 'R&C')) {
						//activate user account
						$qu_users_list_updt = "UPDATE  `users_list` SET 
						 `is_active` = '1'
						WHERE ( (`user_id` = $atp_id) AND (`int_ext` = 'ext') AND (`user_type` = 'atp') AND (`user_family` = 'atps_list') );";

						if(!mysqli_query($KONN, $qu_users_list_updt)){
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Activation failed";
						} else {
							$request_name = $request_name_ar = 'new_accreditation';

							//insert request for accreditation
							$qu_atps_list_requests_ins = "INSERT INTO `atps_list_requests` (
													`request_name`, 
													`request_name_ar`, 
													`added_date`, 
													`added_by`, 
													`atp_id` 
												) VALUES (
													'".$request_name."', 
													'".$request_name_ar."', 
													'".$added_date."', 
													'".$added_by."',  
													'".$atp_id."' 
												);";

								if(mysqli_query($KONN, $qu_atps_list_requests_ins)){
									$request_id = mysqli_insert_id($KONN);
									if( $request_id != 0 ){
										
									}
								}

							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						}


					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-600";
						reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}
				} else {
					//Execute failed 
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-500";
					reportError(mysqli_stmt_error($atpsListStmtUpdt), 'employees_list', $EMPLOYEE_ID);
				}
			} else {
				//bind failed 
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-400";
				reportError(mysqli_stmt_error($atpsListStmtUpdt), 'employees_list', $EMPLOYEE_ID);
			}
		} else {
			//prepare failed 
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-300";
			reportError('atps_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
		}



	} else {
		//No request for stage no1
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-200";
	}




} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



