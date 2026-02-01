<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "document_id";
$tableName = "hr_documents";
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

	if ($extra_id != 0) {
		//policy
		$COND = $COND . "(`$tableName`.`document_type_id` = $extra_id) AND ";
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
							`hr_documents_types`.`document_type_name`
							FROM  `$tableName` 
							INNER JOIN `hr_documents_types` ON `$tableName`.`document_type_id` = `hr_documents_types`.`document_type_id`
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
					"document_title" => $ARRAY_SRC['document_title'],
					"document_description" => $ARRAY_SRC['document_description'],
					"document_type_name" => $ARRAY_SRC['document_type_name'],
					"document_attachment" => $ARRAY_SRC['document_attachment']
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



