<?php


$qu_table_related_sel = "SELECT `$tableName`.*, 
		`sys_lists_colors`.`color_value` AS `group_color`, 
		CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_name` 
	FROM  `$tableName` 
	INNER JOIN `employees_list` ON `$tableName`.`added_by` = `employees_list`.`employee_id` 
	INNER JOIN `sys_lists_colors` ON `$tableName`.`group_color_id` = `sys_lists_colors`.`color_id` 
	WHERE ( ( `group_id` = '$group_id' )  )  LIMIT 1";



$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
if (mysqli_num_rows($qu_table_related_EXE)) {
	$IAM_ARRAY['success'] = true;
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
	$IAM_ARRAY['error'] = mysqli_error($KONN);
}