<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "notification_id";
$tableName = "employees_notifications";
$currentPage = 1;
$recordsPerPage = 50;
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
		$recordsPerPage = 50;
	}
}

if ($IS_LOGGED == true && $USER_ID != 0) {


	$COND = $COND . "(`$tableName`.`employee_id` = $USER_ID) AND ";
	//$COND = $COND . "(`$tableName`.`is_seen` = 0) AND ";




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



	$qu_table_related_sel = "SELECT `$tableName`.* 
							FROM  `$tableName` 
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE) > 0) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];

		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"notification_date" => $ARRAY_SRC['notification_date'],
					"notification_text" => $ARRAY_SRC['notification_text'],
					"related_page" => $ARRAY_SRC['related_page'],
					"is_seen" => $ARRAY_SRC['is_seen']
				)
			);

		}

		//update notifications
		/*
		$qu_employees_notifications_updt = "UPDATE `employees_notifications` SET `is_seen` = '1' WHERE ( $COND (1=1) );";
		if (mysqli_query($KONN, $qu_employees_notifications_updt)) {

		}
		*/

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



