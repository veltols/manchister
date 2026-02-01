<?php





$qu_table_related_sel = "SELECT `f`.*, 
		CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_name` 
	FROM  `z_groups_list_files` `f` 
	INNER JOIN `employees_list` ON `f`.`added_by` = `employees_list`.`employee_id` 
	WHERE ( ( `f`.`group_id` = '$group_id' ) ) 
	 ORDER BY 
	 COALESCE(f.main_file_id, f.file_id),
	 f.main_file_id IS NULL ASC,
	 f.added_date DESC";



$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
if (mysqli_num_rows($qu_table_related_EXE)) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['data'] = [];
	while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {



		$timeAgo = timeAgo("" . $ARRAY_SRC['added_date']);

		array_push(
			$IAM_ARRAY['data'],
			array(
				"file_id" => (INT) $ARRAY_SRC['file_id'],
				"main_file_id" => (INT) $ARRAY_SRC['main_file_id'],
				"file_name" => decryptData($ARRAY_SRC['file_name']),
				"file_version" => decryptData($ARRAY_SRC['file_version']),
				"file_path" => decryptData($ARRAY_SRC['file_path']),
				"added_by" => decryptData($ARRAY_SRC['added_by']),
				"added_name" => decryptData($ARRAY_SRC['added_name']),
				"added_date" => decryptData($timeAgo)
			)
		);
	}

} else {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['error'] = mysqli_error($KONN);
}