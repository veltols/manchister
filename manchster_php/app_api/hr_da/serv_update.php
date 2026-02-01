<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['da_id']) && isset($_POST['log_remark'])) {

	$da_id = (int) test_inputs($_POST['da_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";


	if (isset($_POST['da_status_id'])) {
		$da_status_id = test_inputs($_POST['da_status_id']);
		$qqq = $qqq . "`da_status_id` = '" . $da_status_id . "', ";
	}


	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_hr_disp_actions_updt = "UPDATE  `hr_disp_actions` SET " . $qqq . "  WHERE `da_id` = $da_id;";

		if (mysqli_query($KONN, $qu_hr_disp_actions_updt)) {



			$log_action = 'Disciplinary_Request_Updated';
			$logger_type = 'employees_list';
			$logged_by = $USER_ID;
			if (InsertSysLog('hr_disp_actions', $da_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "log Failed-548";
			}



		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-11221";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-232344";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

