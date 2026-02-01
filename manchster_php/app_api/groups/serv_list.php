<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "group_id";
$tableName = "z_groups_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$isCom = 0;
if (isset($_REQUEST['is_com'])) {
	$is_com = (int) test_inputs($_REQUEST['is_com']);
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

	if (isset($_REQUEST['query_srch'])) {
		$query = "" . test_inputs($_REQUEST['query_srch']);
		if ($query != "") {
			$COND = $COND . " (  ( `q_text` LIKE '%$query%' ) ) AND ";
		}
	}


	$COND = $COND . "( (`$tableName`.`is_commity` = $is_com) AND (`$tableName`.`is_archieve` = 0) AND (`$tableName`.`is_deleted` = 0) ) AND ";
	$totalPages = 0;
	$totalRecords = 0;
	$qu_dataQ_sel = "SELECT COUNT(`$tableName`.`$primaryKey`) FROM `$tableName` WHERE ( $COND (1=1) )";
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

	$COND = $COND . "(`z_groups_list_members`.`employee_id` = $USER_ID) AND ";



	$qu_table_related_sel = "SELECT `$tableName`.*, 
									`z_groups_list_members`.*,
									`sys_lists_colors`.`color_value` AS `group_color`, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_name` 
							FROM  `$tableName` 
							JOIN `z_groups_list_members` ON `$tableName`.`group_id` = `z_groups_list_members`.`group_id` 
							INNER JOIN `employees_list` ON `$tableName`.`added_by` = `employees_list`.`employee_id` 
							INNER JOIN `sys_lists_colors` ON `$tableName`.`group_color_id` = `sys_lists_colors`.`color_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$tableName`.`$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			$gn = getInitials("" . $ARRAY_SRC['group_name']);


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"group_initials" => $gn,
					"group_name" => decryptData($ARRAY_SRC['group_name']),
					"group_desc" => decryptData($ARRAY_SRC['group_desc']),
					"added_by" => decryptData($ARRAY_SRC['added_by']),
					"added_name" => decryptData($ARRAY_SRC['added_name']),
					"added_date" => decryptData($ARRAY_SRC['added_date']),
					"group_color" => decryptData($ARRAY_SRC['group_color'])
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



?>