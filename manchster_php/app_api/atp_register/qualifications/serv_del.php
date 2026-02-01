<?php

$IAM_ARRAY;





$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;

	if (isset($_POST['qualification_id'])) {
		$qualification_id = (int) test_inputs($_POST['qualification_id']);

		$atpsListContactsQryIns = "DELETE FROM `atps_list_qualifications` WHERE ( ( `qualification_id` = ? ) AND ( `atp_id` = ? ) );";

		if ($atpsListContactsStmtIns = mysqli_prepare($KONN, $atpsListContactsQryIns)) {
			if (mysqli_stmt_bind_param($atpsListContactsStmtIns, "ii", $qualification_id, $atp_id)) {
				if (mysqli_stmt_execute($atpsListContactsStmtIns)) {
					$qualification_id = (int) mysqli_insert_id($KONN);
					if (InsertAtpLog("ATP removed qualification", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
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


