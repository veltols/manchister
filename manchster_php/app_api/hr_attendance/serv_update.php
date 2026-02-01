<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['attendance_id']) && isset($_POST['log_remark'])) {

	$attendance_id = (int) test_inputs($_POST['attendance_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_hr_attendance_updt = "UPDATE  `hr_attendance` SET " . $qqq . "  WHERE `attendance_id` = $attendance_id;";

		if (mysqli_query($KONN, $qu_hr_attendance_updt)) {


			$log_action = 'Status_Change';
			$logger_type = 'employees_list';
			$logged_by = $USER_ID;
			if (InsertSysLog('hr_attendance', $attendance_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
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

