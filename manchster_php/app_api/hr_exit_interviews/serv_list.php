<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "interview_id";
$tableName = "hr_exit_interviews";
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

	$interview_id = 0;
	if (isset($_REQUEST['interview_id'])) {
		$interview_id = (int) test_inputs($_REQUEST['interview_id']);
		if ($interview_id != 0) {
			$COND = $COND . "(`$tableName`.`interview_id` = $interview_id) AND ";
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
									`b`.`department_name` AS `department_name`

							FROM  `$tableName` 
							INNER JOIN `employees_list` `a` ON `$tableName`.`employee_id` = `a`.`employee_id` 
							INNER JOIN `employees_list_departments` `b` ON `$tableName`.`current_department_id` = `b`.`department_id` 
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
					"department_name" => decryptData($ARRAY_SRC['department_name']),
					"interview_date" => $ARRAY_SRC['interview_date'],
					"interview_remarks" => $ARRAY_SRC['interview_remarks'],
					"employee_id" => $ARRAY_SRC['employee_id'],
					"current_department_id" => $ARRAY_SRC['current_department_id'],
					"added_by" => $ARRAY_SRC['added_by']
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



