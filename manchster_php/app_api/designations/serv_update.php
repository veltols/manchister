<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['designation_id']) && isset($_POST['log_remark'])) {

	$designation_id = (int) test_inputs($_POST['designation_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";

	if (isset($_POST['designation_name'])) {
		$designation_name = test_inputs($_POST['designation_name']);
		$qqq = $qqq . "`designation_name` = '" . $designation_name . "', ";
	}
	if (isset($_POST['designation_code'])) {
		$designation_code = test_inputs($_POST['designation_code']);
		$qqq = $qqq . "`designation_code` = '" . $designation_code . "', ";
	}


	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_employees_list_designations_updt = "UPDATE  `employees_list_designations` SET " . $qqq . "  WHERE `designation_id` = $designation_id;";

		if (mysqli_query($KONN, $qu_employees_list_designations_updt)) {

			$log_action = 'Designation_Updated';
			$logger_type = 'employees_list';
			$logged_by = $USER_ID;
			if (InsertSysLog('employees_list_designations', $designation_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
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

