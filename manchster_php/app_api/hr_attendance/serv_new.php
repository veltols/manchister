<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['added_date']) &&
		isset($_POST['checkin_date']) &&
		isset($_POST['checkin_time']) &&
		isset($_POST['attendance_remarks']) &&
		isset($_POST['employee_id'])
	) {



		//todo
		//authorize inputs

		$attendance_id = 0;
		$added_date = "" . test_inputs($_POST['added_date']);
		$checkin_date = "" . test_inputs($_POST['checkin_date']);
		$checkin_time = "" . test_inputs($_POST['checkin_time']);

		$attendance_remarks = "" . test_inputs($_POST['attendance_remarks']);
		$employee_id = (int) test_inputs($_POST['employee_id']);



		$added_by = $USER_ID;

		$atpsListQryIns = "INSERT INTO `hr_attendance` (
											`added_date`, 
											`checkin_date`, 
											`checkin_time`, 
											`attendance_remarks`, 
											`employee_id`, 
											`added_by`
											) VALUES ( ?, ?, ?, ?, ?, ?  );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssssii", $added_date, $checkin_date, $checkin_time, $attendance_remarks, $employee_id, $added_by)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$attendance_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);


					$log_action = 'Attendance_Added';
					$log_remark = $attendance_remarks;
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
			reportError('hr_attendance failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


