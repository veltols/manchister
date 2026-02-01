<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "task_id";
$tableName = "tasks_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
if (isset($_REQUEST['currentPage'])) {
	$currentPage = (int) test_inputs($_REQUEST['currentPage']);
}
$extra_id = 0;
if (isset($_REQUEST['extra_id'])) {
	$extra_id = (int) test_inputs($_REQUEST['extra_id']);
}
if (isset($_REQUEST['recordsPerPage'])) {
	$recordsPerPage = (int) test_inputs($_REQUEST['recordsPerPage']);
	if ($recordsPerPage == 0) {
		$recordsPerPage = 10;
	}
}
if (isset($_REQUEST['date_from']) && isset($_REQUEST['date_to'])) {
	$date_from = ''.test_inputs($_REQUEST['date_from']);
	$date_to = ''.test_inputs($_REQUEST['date_to']);
	$startDate = $date_from . ' 00:00:00';
	$endDate = $date_to . ' 23:59:59';

	$currentPage = 1;
	$recordsPerPage = 1500;
	$COND = $COND . " ((`$tableName`.`task_assigned_date` >= '$startDate') AND (`$tableName`.`task_assigned_date` <= '$endDate')) AND ";
}

if ($IS_LOGGED == true && $USER_ID != 0) {

	if (isset($_REQUEST['query_srch'])) {
		$query = "" . test_inputs($_REQUEST['query_srch']);
		if ($query != "") {
			$COND = $COND . " (  ( `atp_nadphone` LIKE '%$query%' ) ) AND ";
		}
	}


	if( $extra_id == 150 ){
		$COND = $COND . "((`$tableName`.`assigned_by` = $USER_ID) AND (`$tableName`.`assigned_to` <> $USER_ID)) AND ";
	} else {
		$COND = $COND . "(`$tableName`.`assigned_to` = $USER_ID) AND ";
	}

	$totalPages = 0;
	$totalRecords = 0;
	$qu_dataQ_sel = "SELECT COUNT(`$primaryKey`) FROM `$tableName` WHERE ( $COND (1=1) )";
	$qu_dataQ_EXE = mysqli_query($KONN, $qu_dataQ_sel);
	if (mysqli_num_rows($qu_dataQ_EXE)) {
		$dataQ_DATA = mysqli_fetch_array($qu_dataQ_EXE);
		$totalRecords = (int) $dataQ_DATA[0];
	}

	$totalPages = ceil($totalRecords / $recordsPerPage);

	$start = 0;
	if ($currentPage) {
		$start = ($currentPage - 1) * $recordsPerPage;
	}
	


	$qu_table_related_sel = "SELECT `$tableName`.*, 
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `assigned_by`,
									CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `assigned_to_name`,
									`sys_list_status`.`status_name` AS `task_status`,
									`sys_list_status`.`status_color` AS `status_color`,

									`sys_list_priorities`.`priority_name` AS `task_priority`,
									`sys_list_priorities`.`priority_color` AS `priority_color` 
							FROM  `$tableName` 
							INNER JOIN `employees_list` `a` ON `$tableName`.`assigned_by` = `a`.`employee_id` 
							INNER JOIN `employees_list` `b` ON `$tableName`.`assigned_to` = `b`.`employee_id` 
							INNER JOIN `sys_list_status` ON `$tableName`.`status_id` = `sys_list_status`.`status_id` 
							INNER JOIN `sys_list_priorities` ON `$tableName`.`priority_id` = `sys_list_priorities`.`priority_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			//lastUpdated
			$lastUpdated = '---';
			$updated_by = '---';

			$qu_tickets_list_logs_sel = "SELECT `sys_logs`.`log_date`,
												CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `logged_by`
												FROM  `sys_logs` 
												INNER JOIN `employees_list` `a` ON `sys_logs`.`logged_by` = `a`.`employee_id` 
												WHERE ((`related_id` = " . $ARRAY_SRC[$primaryKey] . ") AND ( `related_table` = '$tableName' ) ) ORDER BY `log_id` DESC LIMIT 1 ";
			$qu_tickets_list_logs_EXE = mysqli_query($KONN, $qu_tickets_list_logs_sel);
			if (mysqli_num_rows($qu_tickets_list_logs_EXE)) {
				$tickets_list_logs_DATA = mysqli_fetch_assoc($qu_tickets_list_logs_EXE);
				$lastUpdated = $tickets_list_logs_DATA['log_date'];
				$updated_by = $tickets_list_logs_DATA['logged_by'];
			}



			$thsDateArr = explode(' ', "" . $ARRAY_SRC['task_assigned_date']);
			$thsDayId = $thsDateArr[0];
			$counted_time = 'NA';
			$timeProgress = '0';
			$task_assigned_date = "" . $ARRAY_SRC['task_assigned_date'];
			$task_due_date = "" . $ARRAY_SRC['task_due_date'];
			if ($task_assigned_date == '' || $task_due_date == '') {
				$counted_time = 'NA';
			} else {
				$counted_time = timeDef(''.date("Y-m-d"), ''.$task_due_date);
				$timeProgress = get_time_progress($task_assigned_date, $task_due_date);
			}
			
			

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"task_title" => decryptData($ARRAY_SRC['task_title']),
					"task_description" => decryptData($ARRAY_SRC['task_description']),
					"assigned_by" => decryptData($ARRAY_SRC['assigned_by']),
					"assigned_to_name" => decryptData($ARRAY_SRC['assigned_to_name']),
					"task_assigned_date" => decryptData($ARRAY_SRC['task_assigned_date']),
					"task_due_date" => decryptData($ARRAY_SRC['task_due_date']),
					"task_end_date" => $task_due_date,
					"day_id" => $thsDayId,
					"counted_time" => $counted_time,
					"time_progress" => $timeProgress,
					"last_updated" => $lastUpdated,
					"updated_by" => $updated_by,
					"task_status" => $ARRAY_SRC['task_status'],
					"status_color" => $ARRAY_SRC['status_color'],
					"task_priority" => $ARRAY_SRC['task_priority'],
					"priority_color" => $ARRAY_SRC['priority_color'],
				)
			);
		}

	} else {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['error'] = mysqli_error($KONN);
	}




} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



