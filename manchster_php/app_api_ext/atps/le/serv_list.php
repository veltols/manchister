<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "le_id";
$tableName = "atps_list_le";
$currentPage = 1;
$recordsPerPage = 10;
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


	$atp_id = 0;
	if (isset($_REQUEST['atp_id'])) {
		$atp_id = (int) test_inputs($_REQUEST['atp_id']);
	}

	$COND = "(  ( `atp_id` = $atp_id ) ) AND ";


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



	$qu_table_related_sel = "SELECT * FROM  `$tableName` WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			$qualification_id = (int) $ARRAY_SRC['qualification_id'];
			$qu_atps_list_qualifications_sel = "SELECT `qualification_name` FROM  `atps_list_qualifications` WHERE `qualification_id` = $qualification_id";
			$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
			if (mysqli_num_rows($qu_atps_list_qualifications_EXE)) {
				$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
				$qualification_name = $atps_list_qualifications_DATA['qualification_name'];
			}



			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"submission_date" => $ARRAY_SRC['submission_date'],
					"start_date" => $ARRAY_SRC['start_date'],
					"end_date" => $ARRAY_SRC['end_date'],
					"learners_no" => $ARRAY_SRC['learners_no'],
					"cohort" => $ARRAY_SRC['cohort'],
					"qualification_id" => $ARRAY_SRC['qualification_id'],
					"qualification_name" => $qualification_name,
					"atp_id" => $ARRAY_SRC['atp_id']
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



