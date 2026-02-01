<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;

	if (isset($_POST['qualification_name'])) {

		$qualification_type = isset($_POST['qualification_type']) ? encryptData("" . test_inputs($_POST['qualification_type'])) : '';
		$qualification_category = isset($_POST['qualification_category']) ? encryptData("" . test_inputs($_POST['qualification_category'])) : '';
		$emirates_level = isset($_POST['emirates_level']) ? encryptData("" . test_inputs($_POST['emirates_level'])) : '';
		$qulaification_credits = isset($_POST['qulaification_credits']) ? encryptData("" . test_inputs($_POST['qulaification_credits'])) : '';
		$mode_of_delivery = isset($_POST['mode_of_delivery']) ? encryptData("" . test_inputs($_POST['mode_of_delivery'])) : '';

		$qualification_name = encryptData("" . test_inputs($_POST['qualification_name']));


		$atpsListContactsQryIns = "INSERT INTO `atps_list_qualifications` (
									`qualification_category`, 
									`qualification_name`, 
									`emirates_level`, 
									`qualification_type`, 
									`qulaification_credits`, 
									`mode_of_delivery`, 
									`atp_id` 
								) VALUES ( ?, ?, ?, ?, ?, ?, ? );";

		if ($atpsListContactsStmtIns = mysqli_prepare($KONN, $atpsListContactsQryIns)) {
			if (mysqli_stmt_bind_param($atpsListContactsStmtIns, "ssssssi", $qualification_category, $qualification_name, $emirates_level, $qualification_type, $qulaification_credits, $mode_of_delivery, $atp_id)) {
				if (mysqli_stmt_execute($atpsListContactsStmtIns)) {

					if (InsertAtpLog("ATP added new qualification", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-400";
					}

				} else {
					//Execute failed 
					reportError(mysqli_stmt_error($atpsListContactsStmtIns), "atps_list", $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-300";
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($atpsListContactsStmtIns), "atps_list", $USER_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-200";
			}
		} else {
			//prepare failed 
			reportError('atps_list_qualifications failed to prepare stmt ', "atps_list", $USER_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-100";
		}







	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-3432";
	}










} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


