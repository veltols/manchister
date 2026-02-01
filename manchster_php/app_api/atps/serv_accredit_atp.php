<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if (
	$IS_LOGGED == true && $USER_ID != 0 &&
	isset($_POST['atp_id'])
) {

	$atp_id = (int) test_inputs($_POST['atp_id']);


	$qu_atps_list_updt = "UPDATE  `atps_list` SET 
							`phase_id` = '1', 
							`is_phase_ok` = '1', 
							`status_id` = '3' 
							WHERE `atp_id` = $atp_id;";

	if (!mysqli_query($KONN, $qu_atps_list_updt)) {
		die("ER-e398792");
	}

	//insert ATP log
	if (InsertAtpLog("IQC Accredited ATP Details", $atp_id, 'employees_list', $EMPLOYEE_ID, 'R&C')) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['message'] = "Succeed";
	} else {
		reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
	}








} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));






