<?php

$IAM_ARRAY;


$is_renew = 0;

if (isset($_POST['is_renew'])) {
	$is_renew = 1;
}



$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;
	//check if atp has submitted or not
	$totForms = 0;
	$qu_atps_form_init_sel = "SELECT COUNT(`form_id`) AS `form_count` FROM  `atps_form_init` WHERE ((`atp_id` = $USER_ID) AND (`is_renew` = $is_renew) )";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);
		$totForms = (int) $atps_form_init_DATA['form_count'];
	}


	if ($totForms != 0) {


		$submitted_date = date('Y-m-d H:i:00');

		$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
								`submitted_date` = ? , 
								`form_status` = 'pending', 
								`is_submitted` = 1 
								WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";


		if ($submitStmt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
			if (mysqli_stmt_bind_param($submitStmt, "si", $submitted_date, $atp_id)) {
				if (mysqli_stmt_execute($submitStmt)) {

					$qu_atps_list_updt = "UPDATE  `atps_list` SET 
											`is_phase_ok` = 0
											WHERE `atp_id` = $atp_id;";

					if (!mysqli_query($KONN, $qu_atps_list_updt)) {
						die(mysqli_error($KONN));
					}

					if (InsertAtpLog("ATP Submitted Initial Form", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
						
								//notify user
								$qu_atps_list_sel = "SELECT `added_by` FROM  `atps_list` WHERE `atp_id` = $atp_id";
								$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
								if(mysqli_num_rows($qu_atps_list_EXE)){
									$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
									$added_by = ( int ) $atps_list_DATA['added_by'];
									notifyUser("Training Provider Submitted Initial Request Form ", 'ext/atps/list/', $added_by);
								}
					} else {
						reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-400";
					}

				} else {
					//Execute failed 
					reportError(mysqli_stmt_error($submitStmt), "atps_list", $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-300";
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($submitStmt), "atps_list", $USER_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-200";
			}
		} else {
			//prepare failed 
			reportError('atps_list_contacts failed to prepare stmt ', "atps_list", $USER_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-100";
		}

	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Form is not filled";
	}

} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



