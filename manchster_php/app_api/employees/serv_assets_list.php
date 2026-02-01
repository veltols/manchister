<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "asset_id";
$tableName = "z_assets_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$employee_id = 0;
if (isset($_REQUEST['employee_id'])) {
	$employee_id = (int) test_inputs($_REQUEST['employee_id']);
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

if ($IS_LOGGED == true && $USER_ID != 0 && $employee_id != 0) {


	$COND = $COND . "(`$tableName`.`assigned_to` = '$employee_id') AND ";


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
									`z_assets_list_cats`.`category_name` AS `category_name`
									
							FROM  `$tableName` 
							INNER JOIN `z_assets_list_cats` ON `$tableName`.`category_id` = `z_assets_list_cats`.`category_id` 
							
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
					"asset_ref" => decryptData($ARRAY_SRC['asset_ref']),
					"asset_name" => decryptData($ARRAY_SRC['asset_name']),
					"asset_description" => decryptData($ARRAY_SRC['asset_description']),
					"assigned_date" => decryptData($ARRAY_SRC['assigned_date']),
					"expiry_date" => decryptData($ARRAY_SRC['expiry_date']),
					"asset_sku" => decryptData($ARRAY_SRC['asset_sku']),
					"asset_serial" => decryptData($ARRAY_SRC['asset_serial']),
					"category_name" => decryptData($ARRAY_SRC['category_name'])
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



