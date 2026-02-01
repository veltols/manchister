<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "statistic_id";
$tableName = "atps_learners_statistics";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$extra_id = 0;

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


	$COND = $COND . "(`$tableName`.`atp_id` = $USER_ID) AND ";



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
									`atps_list_qualifications`.`qualification_name` AS `qualification_name`
									
							FROM  `$tableName` 
							INNER JOIN `atps_list_qualifications` ON `$tableName`.`qualification_id` = `atps_list_qualifications`.`qualification_id` 
							
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
					"statistic_type" => decryptData("" . $ARRAY_SRC['statistic_type']),
					"qualification_id" => decryptData("" . $ARRAY_SRC['qualification_id']),
					"qualification_name" => decryptData("" . $ARRAY_SRC['qualification_name']),
					"y1_value" => decryptData("" . $ARRAY_SRC['y1_value']),
					"y2_value" => decryptData("" . $ARRAY_SRC['y2_value']),
					"y3_value" => decryptData("" . $ARRAY_SRC['y3_value']), 
					"y4_value" => decryptData("" . $ARRAY_SRC['y4_value'])
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



