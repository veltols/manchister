<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "record_id";
$tableName = "z_assets_list_assign";
$asset_id = 0;


if (isset($_REQUEST['asset_id'])) {
	$asset_id = (int) test_inputs($_REQUEST['asset_id']);
}


if ($IS_LOGGED == true && $USER_ID != 0) {



	$qu_table_related_sel = "SELECT `$tableName`.* , 
							CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `assigned_by`, 
							CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `assigned_to` 
							FROM  `$tableName`
							INNER JOIN `employees_list` `a` ON `$tableName`.`assigned_by` = `a`.`employee_id` 
							LEFT JOIN `employees_list` `b` ON `$tableName`.`assigned_to` = `b`.`employee_id` 
							WHERE ( ( `asset_id` = $asset_id ) ) ORDER BY `$primaryKey` DESC";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			$assigned_by = "" . $ARRAY_SRC['assigned_by'];
			$assigned_to = "" . $ARRAY_SRC['assigned_to'];

			if ($assigned_to == '') {
				$assigned_to = "Revoked";
			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"assign_remarks" => decryptData($ARRAY_SRC['assign_remarks']),
					"assigned_date" => decryptData($ARRAY_SRC['assigned_date']),
					"assigned_by" => $assigned_by,
					"assigned_to" => $assigned_to
				)
			);
		}

	} else {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['error'] = mysqli_error($KONN);
	}




} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



