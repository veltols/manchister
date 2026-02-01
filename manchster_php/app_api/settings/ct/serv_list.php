<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "communication_type_id";
$tableName = "m_communications_list_types";
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
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `approval_1_name`,
									CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `approval_2_name` 
							FROM  `$tableName`
							LEFT JOIN `employees_list` `a` ON `$tableName`.`approval_id_1` = `a`.`employee_id` 
							LEFT JOIN `employees_list` `b` ON `$tableName`.`approval_id_2` = `b`.`employee_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			$approval_1_name = '' . $ARRAY_SRC['approval_1_name'];
			if ($approval_1_name == '') {
				$approval_1_name = 'NA';
			}
			$approval_2_name = '' . $ARRAY_SRC['approval_2_name'];
			if ($approval_2_name == '') {
				$approval_2_name = 'NA';
			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"communication_type_name" => decryptData($ARRAY_SRC['communication_type_name']),
					"approval_id_1" => decryptData($ARRAY_SRC['approval_id_1']),
					"approval_1_name" => $approval_1_name,
					"approval_id_2" => decryptData($ARRAY_SRC['approval_id_2']),
					"approval_2_name" => $approval_2_name
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



