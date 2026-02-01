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

	$thsDate = date('Y-m-d');
	if ($extra_id == 1) {
		//about to expire

	} else if ($extra_id == 2) {
		//expired
		$COND = $COND . "(`$tableName`.`expiry_date` <= '$thsDate') AND ";
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

			$assigned_to = decryptData((int) $ARRAY_SRC['assigned_to']);
			$assigned_to_name = 'Not Assigned';
			if ($assigned_to != 0) {
				$qu_employees_list_sel = "SELECT CONCAT( `first_name`, ' ', `last_name` ) FROM  `employees_list` WHERE `employee_id` = $assigned_to";
				$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
				if (mysqli_num_rows($qu_employees_list_EXE)) {
					$employees_list_DATA = mysqli_fetch_array($qu_employees_list_EXE);
					$assigned_to_name = "" . $employees_list_DATA[0];
				}

			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"asset_ref" => decryptData($ARRAY_SRC['asset_ref']),
					"asset_name" => decryptData($ARRAY_SRC['asset_name']),
					"asset_description" => decryptData($ARRAY_SRC['asset_description']),
					"assigned_to" => $assigned_to_name,
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



