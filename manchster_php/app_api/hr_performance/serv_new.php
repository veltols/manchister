<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['added_date']) &&
		isset($_POST['performance_object']) &&
		isset($_POST['performance_kpi']) &&
		isset($_POST['performance_remark']) &&
		isset($_POST['employee_id'])
	) {



		//todo
		//authorize inputs

		$performance_id = 0;
		$added_date = date('Y-m-d H:i:00');
		$performance_object = "" . test_inputs($_POST['performance_object']);
		$performance_kpi = "" . test_inputs($_POST['performance_kpi']);

		$performance_remark = "" . test_inputs($_POST['performance_remark']);
		$employee_id = (int) test_inputs($_POST['employee_id']);



		$added_by = $USER_ID;

		$atpsListQryIns = "INSERT INTO `hr_performance` (
											`added_date`, 
											`performance_object`, 
											`performance_kpi`, 
											`performance_remark`, 
											`employee_id`, 
											`added_by`
											) VALUES ( ?, ?, ?, ?, ?, ?  );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssssii", $added_date, $performance_object, $performance_kpi, $performance_remark, $employee_id, $added_by)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$performance_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);


					$log_action = 'Performance_Added';
					$log_remark = $performance_remark;
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
			reportError('hr_performance failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


