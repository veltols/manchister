<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['task_id']) && isset($_POST['log_remark'])) {

	$task_id = (int) test_inputs($_POST['task_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";

	if (isset($_POST['task_title'])) {
		$task_title = test_inputs($_POST['task_title']);
		$qqq = $qqq . "`task_title` = '" . $task_title . "', ";
	}
	if (isset($_POST['task_description'])) {
		$task_description = test_inputs($_POST['task_description']);
		$qqq = $qqq . "`task_description` = '" . $task_description . "', ";
	}
	$status_id = 0;
	if (isset($_POST['status_id'])) {
		$status_id = (int) test_inputs($_POST['status_id']);
		$qqq = $qqq . "`status_id` = '" . $status_id . "', ";
		if ($status_id == 3) {
			$qqq = $qqq . "`task_end_date` = '" . date('Y-m-d H:i:00') . "', ";
		}
	}
	if (isset($_POST['task_progress'])) {
		$task_progress = test_inputs($_POST['task_progress']);
		$qqq = $qqq . "`task_progress` = '" . $task_progress . "', ";

	}
	if (isset($_POST['task_assigned_date'])) {
		$task_assigned_date = test_inputs($_POST['task_assigned_date']);
		$qqq = $qqq . "`task_assigned_date` = '" . $task_assigned_date . "', ";
	}
	if (isset($_POST['task_due_date'])) {
		$task_due_date = test_inputs($_POST['task_due_date']);
		$qqq = $qqq . "`task_due_date` = '" . $task_due_date . "', ";
	}
	if (isset($_POST['task_end_date'])) {
		$task_end_date = test_inputs($_POST['task_end_date']);
		$qqq = $qqq . "`task_end_date` = '" . $task_end_date . "', ";
	}
	if (isset($_POST['assigned_to'])) {
		$assigned_to = test_inputs($_POST['assigned_to']);
		$qqq = $qqq . "`assigned_to` = '" . $assigned_to . "', ";
	}
	if (isset($_POST['assigned_by'])) {
		$assigned_by = test_inputs($_POST['assigned_by']);
		$qqq = $qqq . "`assigned_by` = '" . $assigned_by . "', ";
	}

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_tasks_list_updt = "UPDATE `tasks_list` SET " . $qqq . "  WHERE `task_id` = $task_id;";


		if (mysqli_query($KONN, $qu_tasks_list_updt)) {
			
			//insert task log
			$log_id = 0;
			$log_action = "Task_Status_changed";
			if ($status_id != 0) {
				$log_action = "Task_Status_changed-" . $status_id;
			}
			$log_date = date('Y-m-d H:i:00');
			$logger_type = "employees_list";
			$log_dept = "HR";
			$logged_by = $USER_ID;


			if (insertSysLog('tasks_list', $task_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Task log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}


		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-34345";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

