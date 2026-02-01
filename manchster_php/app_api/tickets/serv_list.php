<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "ticket_id";
$tableName = "support_tickets_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$extra_id = 0;
if (isset($_REQUEST['extra_id'])) {
	$extra_id = (int) test_inputs($_REQUEST['extra_id']);
}

if (isset($_REQUEST['currentPage'])) {
	$currentPage = (int) test_inputs($_REQUEST['currentPage']);
}
if (isset($_REQUEST['recordsPerPage'])) {
	$recordsPerPage = (int) test_inputs($_REQUEST['recordsPerPage']);
	if ($recordsPerPage == 0) {
		$recordsPerPage = 10;
	}
}

if ($IS_LOGGED == true && $USER_ID != 0) {

	if ($USER_ID != 1) {
		$COND = $COND . "(`$tableName`.`added_by` = $USER_ID) AND ";
	}
	if ($extra_id != 0) {
		$COND = $COND . "(`$tableName`.`status_id` = $extra_id) AND ";
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
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_employee`,
									CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `assigned_to_name`, 
									`support_tickets_list_status`.`status_name` AS `ticket_status`,
									`support_tickets_list_status`.`status_color` AS `status_color`,

									`support_tickets_list_cats`.`category_name` AS `category_name`,

									`sys_list_priorities`.`priority_name` AS `ticket_priority`,
									`sys_list_priorities`.`priority_color` AS `priority_color` 
							FROM  `$tableName`
							INNER JOIN `employees_list` `a` ON `$tableName`.`added_by` = `a`.`employee_id` 
							LEFT JOIN `employees_list` `b` ON `$tableName`.`assigned_to` = `b`.`employee_id` 
							INNER JOIN `support_tickets_list_status` ON `$tableName`.`status_id` = `support_tickets_list_status`.`status_id` 
							INNER JOIN `support_tickets_list_cats` ON `$tableName`.`category_id` = `support_tickets_list_cats`.`category_id` 
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
												`sys_logs`.`logged_by`, 
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


			$assigned_to_name = decryptData("" . $ARRAY_SRC['assigned_to_name']);

			if ($assigned_to_name == "") {
				$assigned_to_name = lang("Not_Assigned", "AAR");
			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"ticket_ref" => decryptData($ARRAY_SRC['ticket_ref']),
					"ticket_subject" => decryptData($ARRAY_SRC['ticket_subject']),
					"added_employee" => decryptData($ARRAY_SRC['added_employee']),
					"ticket_description" => decryptData($ARRAY_SRC['ticket_description']),
					"category_name" => decryptData($ARRAY_SRC['category_name']),

					"ticket_added_date" => decryptData($ARRAY_SRC['ticket_added_date']),
					"ticket_end_date" => decryptData($ARRAY_SRC['ticket_end_date']),

					"assigned_to" => (int) decryptData((int) $ARRAY_SRC['assigned_to']),

					"assigned_to_name" => $assigned_to_name,

					"last_updated" => $lastUpdated,
					"updated_by" => $updated_by,
					"ticket_status" => $ARRAY_SRC['ticket_status'],
					"status_color" => $ARRAY_SRC['status_color'],

					"ticket_priority" => $ARRAY_SRC['ticket_priority'],
					"priority_color" => $ARRAY_SRC['priority_color'],
					"status_id" => $ARRAY_SRC['status_id']
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



