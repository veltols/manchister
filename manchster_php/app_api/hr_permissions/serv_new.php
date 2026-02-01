<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['submission_date']) &&
		isset($_POST['start_date']) &&
		isset($_POST['start_time']) &&
		isset($_POST['end_time']) &&
		isset($_POST['total_hours']) &&
		isset($_POST['permission_remarks']) &&
		isset($_POST['employee_id'])
	) {



		//todo
		//authorize inputs

		$permission_id = 0;
		$submission_date = "" . test_inputs($_POST['submission_date']);
		$start_date = "" . test_inputs($_POST['start_date']);
		$start_time = "" . test_inputs($_POST['start_time']);
		$end_time = "" . test_inputs($_POST['end_time']);
		$total_hours = "" . test_inputs($_POST['total_hours']);
		//$total_days = (int) test_inputs($_POST['total_days']);
		$permission_remarks = "" . test_inputs($_POST['permission_remarks']);
		$employee_id = (int) test_inputs($_POST['employee_id']);
		$permission_status_id = 1;


		//get emp balance
		$remainingHours = 0;
		$qu_employees_list_sel = "SELECT `allowed_permission_hours`, `permission_hours_balance` FROM  `employees_list` WHERE `employee_id` = $employee_id";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE)) {
			$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
			$allowed_permission_hours = (int) $employees_list_DATA['allowed_permission_hours'];
			$permission_hours_balance = (int) $employees_list_DATA['permission_hours_balance'];
			$remainingHours = $allowed_permission_hours - $permission_hours_balance;
		}
		if ($total_hours > $remainingHours) {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Not enough leave balance";
		} else {
			$atpsListQryIns = "INSERT INTO `hr_employees_permissions` (
				`submission_date`, 
				`start_date`, 
				`start_time`, 
				`end_time`, 
				`total_hours`, 
				`permission_remarks`, 
				`employee_id`, 
				`permission_status_id` 
				) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? );";

			if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
				if (mysqli_stmt_bind_param($atpsListStmtIns, "ssssisii", $submission_date, $start_date, $start_time, $end_time, $total_hours, $permission_remarks, $employee_id, $permission_status_id)) {
					if (mysqli_stmt_execute($atpsListStmtIns)) {
						$permission_id = (int) mysqli_insert_id($KONN);
						mysqli_stmt_close(statement: $atpsListStmtIns);


						$log_action = 'Permission_Request_Added';
						$log_remark = '---';
						$logger_type = 'employees_list';
						$logged_by = $USER_ID;
						if (InsertSysLog('hr_employees_permissions', $permission_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
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


