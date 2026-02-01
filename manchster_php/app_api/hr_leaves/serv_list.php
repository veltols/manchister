<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "leave_id";
$tableName = "hr_employees_leaves";
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


	//$COND = $COND . "(`$tableName`.`assigned_to` = $USER_ID) AND ";


	if ($USER_TYPE == 'emp') {
		$COND = $COND . "(`$tableName`.`employee_id` = $USER_ID) AND ";
	}

	$leave_id = 0;
	if (isset($_REQUEST['leave_id'])) {
		$leave_id = (int) test_inputs($_REQUEST['leave_id']);
		if ($leave_id != 0) {
			$COND = $COND . "(`$tableName`.`leave_id` = $leave_id) AND ";
		}
	}

	$employee_id = 0;
	if (isset($_REQUEST['employee_id'])) {
		$employee_id = (int) test_inputs($_REQUEST['employee_id']);
		if ($employee_id != 0) {
			$COND = $COND . "(`$tableName`.`employee_id` = $employee_id) AND ";
		}
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
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `employee_name`,
									`hr_employees_leave_types`.`leave_type_name` AS `leave_type`, 
									`hr_employees_leave_status`.`leave_status_name` AS `leave_status`

							FROM  `$tableName` 
							INNER JOIN `employees_list` `a` ON `$tableName`.`employee_id` = `a`.`employee_id` 
							INNER JOIN `hr_employees_leave_types` ON `$tableName`.`leave_type_id` = `hr_employees_leave_types`.`leave_type_id` 
							INNER JOIN `hr_employees_leave_status` ON `$tableName`.`leave_status_id` = `hr_employees_leave_status`.`leave_status_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"employee_name" => decryptData($ARRAY_SRC['employee_name']),
					"leave_type" => decryptData($ARRAY_SRC['leave_type']),
					"submission_date" => $ARRAY_SRC['submission_date'],
					"start_date" => $ARRAY_SRC['start_date'],
					"end_date" => $ARRAY_SRC['end_date'],
					"leave_type_id" => $ARRAY_SRC['leave_type_id'],
					"total_days" => $ARRAY_SRC['total_days'],
					"leave_remarks" => $ARRAY_SRC['leave_remarks'],
					"employee_id" => $ARRAY_SRC['employee_id'],
					"leave_attachment" => $ARRAY_SRC['leave_attachment'],
					"leave_status" => $ARRAY_SRC['leave_status'],
					"leave_status_id" => $ARRAY_SRC['leave_status_id']
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



