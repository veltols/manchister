<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "department_id";
$tableName = "employees_list_departments";
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
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `line_manager`
							FROM  `$tableName` 
							LEFT JOIN `employees_list` `a` ON `$tableName`.`line_manager_id` = `a`.`employee_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {
			$main_department_name = '---';
			$main_department_id = decryptData((int) $ARRAY_SRC['main_department_id']);
			if ($main_department_id != 0) {

				$qu_employees_list_departments_sel = "SELECT `department_name` FROM  `employees_list_departments` WHERE `department_id` = $main_department_id";
				$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
				if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
					$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
					$main_department_name = $employees_list_departments_DATA['department_name'];
				}

			}
			$lmName = '' . $ARRAY_SRC['line_manager'];
			$lm = (int) $ARRAY_SRC['line_manager_id'];
			if ($lm == 0) {
				$lmName = "Not Assigned";
			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"main_department_name" => $main_department_name,
					"line_manager_id" => decryptData($ARRAY_SRC['line_manager_id']),
					"line_manager" => decryptData($lmName),
					"main_department_id" => decryptData($ARRAY_SRC['main_department_id']),
					"department_name" => decryptData($ARRAY_SRC['department_name']),
					"user_type" => decryptData($ARRAY_SRC['user_type']),
					"department_code" => decryptData($ARRAY_SRC['department_code'])
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



