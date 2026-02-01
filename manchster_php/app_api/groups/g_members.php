<?php


$qu_table_related_sel = "SELECT `z_groups_list_members`.*, 
		`z_groups_list_roles`.`group_role_name`, 
		CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `member_name` 
	FROM  `z_groups_list_members` 
	INNER JOIN `employees_list` ON `z_groups_list_members`.`employee_id` = `employees_list`.`employee_id` 
	INNER JOIN `z_groups_list_roles` ON `z_groups_list_members`.`group_role_id` = `z_groups_list_roles`.`group_role_id` 
	WHERE ( ( `group_id` = '$group_id' )  )";



$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
if (mysqli_num_rows($qu_table_related_EXE)) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['data'] = [];
	while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

		array_push(
			$IAM_ARRAY['data'],
			array(
				"record_id" => $ARRAY_SRC['record_id'],
				"member_name" => decryptData($ARRAY_SRC['member_name']),
				"group_role_id" => decryptData($ARRAY_SRC['group_role_id']),
				"group_role_name" => decryptData($ARRAY_SRC['group_role_name']),
				"added_date" => decryptData($ARRAY_SRC['added_date'])
			)
		);
	}

} else {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['error'] = mysqli_error($KONN);
}