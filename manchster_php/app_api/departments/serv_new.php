<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['main_department_id']) &&
		isset($_POST['line_manager_id']) &&
		isset($_POST['user_type']) &&
		isset($_POST['department_name']) &&
		isset($_POST['department_code'])

	) {



		$department_id = 0;
		$main_department_id = (int) test_inputs($_POST['main_department_id']);
		$line_manager_id = (int) test_inputs($_POST['line_manager_id']);
		$department_name = "" . test_inputs($_POST['department_name']);
		$department_code = "" . test_inputs($_POST['department_code']);
		$user_type = "" . test_inputs($_POST['user_type']);


		if ($main_department_id == $department_id) {
			$main_department_id = 0;
		}
		$atpsListQryIns = "INSERT INTO `employees_list_departments` (
											`main_department_id`, 
											`department_name`, 
											`user_type`, 
											`department_code`, 
											`line_manager_id` 
											) VALUES ( ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "isssi", $main_department_id, $department_name, $user_type, $department_code, $line_manager_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$department_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);

					$log_action = 'Department_Added';
					$log_remark = '---';
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
					//Execute failed 
					reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-57";
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-56";
			}
		} else {
			//prepare failed 
			reportError('employees_list_departments failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-55";
		}



	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


