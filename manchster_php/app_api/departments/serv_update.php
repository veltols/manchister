<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['department_id']) && isset($_POST['log_remark'])) {

	$department_id = (int) test_inputs($_POST['department_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";

	if (isset($_POST['main_department_id'])) {
		$main_department_id = (int) test_inputs($_POST['main_department_id']);
		if ($main_department_id != $department_id) {
			$qqq = $qqq . "`main_department_id` = '" . $main_department_id . "', ";
		}
	}

	if (isset($_POST['line_manager_id'])) {
		$line_manager_id = (int) test_inputs($_POST['line_manager_id']);
		$qqq = $qqq . "`line_manager_id` = '" . $line_manager_id . "', ";
	}
	if (isset($_POST['department_name'])) {
		$department_name = test_inputs($_POST['department_name']);
		$qqq = $qqq . "`department_name` = '" . $department_name . "', ";
	}
	if (isset($_POST['department_code'])) {
		$department_code = test_inputs($_POST['department_code']);
		$qqq = $qqq . "`department_code` = '" . $department_code . "', ";
	}


	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_employees_list_departments_updt = "		UPDATE  `employees_list_departments` SET " . $qqq . "  WHERE `department_id` = $department_id;";



		if (mysqli_query($KONN, $qu_employees_list_departments_updt)) {

			$log_action = 'Department_Updated';
			$logger_type = 'employees_list';
			$logged_by = $USER_ID;
			if (InsertSysLog('employees_list_departments', $department_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
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

