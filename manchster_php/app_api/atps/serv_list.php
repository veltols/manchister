<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "atp_id";
$tableName = "atps_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "(  ( `is_draft` = '0' ) ) AND ";
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
			$COND = $COND . " (  ( `atp_name` LIKE '%$query%' ) OR ( `atp_email` LIKE '%$query%' ) OR ( `atp_phone` LIKE '%$query%' ) ) AND ";
		}
	}


	//$COND = $COND."(`added_by` = $USER_ID) AND ";
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
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_name`,
									`atps_list_status`.`status_name` AS `atp_status`,
									`atps_list_status`.`status_description` AS `atp_status_desc`
							FROM  `$tableName` 
							INNER JOIN `employees_list` ON `$tableName`.`added_by` = `employees_list`.`employee_id` 
							INNER JOIN `atps_list_status` ON `$tableName`.`status_id` = `atps_list_status`.`status_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			//lastUpdated
			$lastUpdated = '---';

			$qu_atps_list_logs_sel = "SELECT `log_date` FROM  `atps_list_logs` WHERE `atp_id` = " . $ARRAY_SRC[$primaryKey] . " ORDER BY `log_id` DESC LIMIT 1 ";
			$qu_atps_list_logs_EXE = mysqli_query($KONN, $qu_atps_list_logs_sel);
			if (mysqli_num_rows($qu_atps_list_logs_EXE)) {
				$atps_list_logs_DATA = mysqli_fetch_assoc($qu_atps_list_logs_EXE);
				$lastUpdated = $atps_list_logs_DATA['log_date'];
			}



			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"atp_ref" => decryptData($ARRAY_SRC['atp_ref']),
					"atp_name" => print_data(decryptData($ARRAY_SRC['atp_name' . $lang_db])),
					"contact_name" => decryptData($ARRAY_SRC['contact_name']),
					"atp_email" => decryptData($ARRAY_SRC['atp_email']),
					"atp_phone" => decryptData($ARRAY_SRC['atp_phone']),
					"added_by" => $ARRAY_SRC['added_name'],
					"added_date" => $ARRAY_SRC['added_date'],
					"last_updated" => $lastUpdated,
					"status_id" => (int) $ARRAY_SRC['status_id'],
					"atp_status" => $ARRAY_SRC['atp_status'],
					"atp_status_desc" => $ARRAY_SRC['atp_status_desc'],
					"is_draft" => $ARRAY_SRC['is_draft'],
					"stage_no" => $ARRAY_SRC['stage_no']
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



