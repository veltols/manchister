<?php


$qu_table_related_sel = "SELECT `z_groups_list_posts`.*, 
		CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `sender_name` 
	FROM  `z_groups_list_posts` 
	INNER JOIN `employees_list` ON `z_groups_list_posts`.`added_by` = `employees_list`.`employee_id` 
	WHERE ( ( `z_groups_list_posts`.`group_id` = '$group_id' )  ) ORDER BY `z_groups_list_posts`.`added_date` ASC LIMIT 100";



$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
if (mysqli_num_rows($qu_table_related_EXE)) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['data'] = [];
	while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

		$timeAgo = timeAgo("" . $ARRAY_SRC['added_date']);
		$post_type = "" . $ARRAY_SRC['post_type'];

		$post_file_name = "";
		$post_file_path = "";
		if ($post_type != 'text') {
			$post_attachment_id = (INT) $ARRAY_SRC['post_attachment_id'];
			if ($post_attachment_id != 0) {
				//get related file
				$qu_z_groups_list_files_sel = "SELECT * FROM  `z_groups_list_files` WHERE ((`group_id` = $group_id) AND (`file_id` = $post_attachment_id))";
				$qu_z_groups_list_files_EXE = mysqli_query($KONN, $qu_z_groups_list_files_sel);
				if (mysqli_num_rows($qu_z_groups_list_files_EXE)) {
					$z_groups_list_files_DATA = mysqli_fetch_assoc($qu_z_groups_list_files_EXE);
					$post_file_name = $z_groups_list_files_DATA['file_name'];
					$post_file_path = $z_groups_list_files_DATA['file_path'];
				}
			}
		}

		array_push(
			$IAM_ARRAY['data'],
			array(
				"post_id" => $ARRAY_SRC['post_id'],
				"sender_id" => decryptData($ARRAY_SRC['added_by']),
				"sender_name" => decryptData($ARRAY_SRC['sender_name']),
				"post_text" => decryptData("" . $ARRAY_SRC['post_text']),
				"post_type" => decryptData($ARRAY_SRC['post_type']),
				"post_file_name" => $post_file_name,
				"post_file_path" => $post_file_path,
				"added_date" => $timeAgo
			)
		);
	}

} else {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['error'] = mysqli_error($KONN);
}