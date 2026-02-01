<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "employee_id";
$tableName = "employees_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
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

	if (isset($_REQUEST['query_srch'])) {
		$query = "" . test_inputs($_REQUEST['query_srch']);
		if ($query != "") {
			$COND = $COND . " (  ( `first_name` LIKE '%$query%' ) OR ( `last_name` LIKE '%$query%' ) OR ( `employee_email` LIKE '%$query%' ) ) AND ";
		}
	}



	$COND = $COND . "(`$tableName`.`is_hidden` = 0) AND ";
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
									`employees_list_departments`.`department_code` AS `department_code`,
									`employees_list_departments`.`department_name` AS `department_name`
									
							FROM  `$tableName` 
							INNER JOIN `employees_list_departments` ON `$tableName`.`department_id` = `employees_list_departments`.`department_id` 
							
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
					"$primaryKey" => (int) $ARRAY_SRC[$primaryKey],
					"employee_no" => "" . $ARRAY_SRC['employee_no'],
					"employee_name" => "" . $ARRAY_SRC['first_name'] . " " . $ARRAY_SRC['last_name'],
					"first_name" => "" . $ARRAY_SRC['first_name'],
					"last_name" => "" . $ARRAY_SRC['last_name'],
					"employee_code" => "" . $ARRAY_SRC['employee_code'],
					"title_id" => (int) $ARRAY_SRC['title_id'],
					"gender_id" => (int) $ARRAY_SRC['gender_id'],
					"nationality_id" => (int) $ARRAY_SRC['nationality_id'],
					"timezone_id" => (int) $ARRAY_SRC['timezone_id'],
					"employee_email" => "" . $ARRAY_SRC['employee_email'],
					"employee_picture" => "" . $ARRAY_SRC['employee_picture'],
					"department_id" => (int) $ARRAY_SRC['department_id'],
					"department_code" => "" . $ARRAY_SRC['department_id'],
					"department_name" => "" . $ARRAY_SRC['department_name'],
					"is_new" => (int) $ARRAY_SRC['is_new'],
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



