<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['designation_name']) &&
		isset($_POST['designation_code'])

	) {


		//todo
		//authorize inputs

		$designation_id = 0;
		$designation_name = "" . test_inputs($_POST['designation_name']);
		$designation_code = "" . test_inputs($_POST['designation_code']);


		$atpsListQryIns = "INSERT INTO `employees_list_designations` (
											`designation_name`, 
											`designation_code` 
											) VALUES ( ?, ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ss", $designation_name, $designation_code)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$designation_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);

					$log_action = 'Designation_Added';
					$logger_type = 'employees_list';
					$logged_by = $USER_ID;
					if (InsertSysLog('employees_list_designations', $designation_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
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
			reportError('employees_list_designations failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


