<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "communication_id";
$tableName = "m_communications_list";
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
		$COND = $COND . "((`$tableName`.`requested_by` = $USER_ID) ) AND ";
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
									`m_communications_list_status`.`communication_status_name` AS `communication_status`,
									`m_communications_list_status`.`status_color` AS `status_color`,

									`m_communications_list_types`.`communication_type_name` AS `communication_type_name`
									
							FROM  `$tableName`
							INNER JOIN `employees_list` `a` ON `$tableName`.`requested_by` = `a`.`employee_id` 
							INNER JOIN `m_communications_list_status` ON `$tableName`.`communication_status_id` = `m_communications_list_status`.`communication_status_id` 
							INNER JOIN `m_communications_list_types` ON `$tableName`.`communication_type_id` = `m_communications_list_types`.`communication_type_id` 
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


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"communication_code" => $ARRAY_SRC['communication_code'],
					"external_party_name" => $ARRAY_SRC['external_party_name'],
					"communication_subject" => $ARRAY_SRC['communication_subject'],
					"communication_description" => $ARRAY_SRC['communication_description'],
					"information_shared" => $ARRAY_SRC['information_shared'],
					"communication_type_id" => $ARRAY_SRC['communication_type_id'],
					"requested_date" => $ARRAY_SRC['requested_date'],
					"requested_by" => $ARRAY_SRC['requested_by'],

					"added_employee" => decryptData($ARRAY_SRC['added_employee']),
					"communication_type_name" => decryptData($ARRAY_SRC['communication_type_name']),
					"last_updated" => $lastUpdated,
					"updated_by" => $updated_by,
					"communication_status" => $ARRAY_SRC['communication_status'],
					"status_color" => $ARRAY_SRC['status_color'],
					"communication_status_id" => $ARRAY_SRC['communication_status_id']
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



