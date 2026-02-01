<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['task_title']) &&
		isset($_POST['task_description']) &&
		isset($_POST['priority_id']) &&
		isset($_POST['task_assigned_date']) &&
		isset($_POST['task_due_date']) &&
		isset($_POST['assigned_to'])
	) {


		//todo
		//authorize inputs

		$task_id = 0;
		$task_title = "" . test_inputs($_POST['task_title']);
		$task_description = "" . test_inputs($_POST['task_description']);
		$status_id = 1;
		$priority_id = (int) test_inputs($_POST['priority_id']);

		$task_assigned_date = "" . test_inputs($_POST['task_assigned_date']);
		$task_due_date = "" . test_inputs($_POST['task_due_date']);
		$assigned_by = $USER_ID;
		$assigned_to = (int) test_inputs($_POST['assigned_to']);

		if (isset($_POST['start_time'])) {
			$start_time = "" . test_inputs($_POST['start_time']);
			if ($start_time != '') {
				$task_assigned_date = $task_assigned_date . ' ' . $start_time;
			}
		}

		if (isset($_POST['end_time'])) {
			$end_time = "" . test_inputs($_POST['end_time']);
			if ($end_time != '') {
				$task_due_date = $task_due_date . ' ' . $end_time;
			}
		}


		$atpsListQryIns = "INSERT INTO `tasks_list` (
											`task_title`, 
											`task_description`, 
											`status_id`, 
											`priority_id`, 
											`task_assigned_date`, 
											`task_due_date`, 
											`assigned_to`, 
											`assigned_by`
											) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiissii", $task_title, $task_description, $status_id, $priority_id, $task_assigned_date, $task_due_date, $assigned_to, $assigned_by)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$task_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert task log
					$log_id = 0;
					$log_action = "Task_Added";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "HR";
					$logged_by = $USER_ID;


					if (insertSysLog('tasks_list', $task_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('Task log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}





					if ($assigned_to != 0) {
						notifyUser("New Task added", "tasks/", $assigned_to);
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
			reportError('tasks_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


