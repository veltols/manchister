<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['performance_id']) && isset($_POST['log_remark'])) {

	$performance_id = (int) test_inputs($_POST['performance_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_hr_performance_updt = "UPDATE  `hr_performance` SET " . $qqq . "  WHERE `performance_id` = $performance_id;";

		if (mysqli_query($KONN, $qu_hr_performance_updt)) {


			$log_action = 'Status_Change';
			$logger_type = 'employees_list';
			$logged_by = $USER_ID;
			if (InsertSysLog('hr_performance', $performance_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
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

