<?php


if (
	isset($_POST['group_id']) &&
	isset($_POST['post'])
) {




	$record_id = 0;
	$group_id = (int) test_inputs($_POST['group_id']);
	$post_text = "" . test_inputs($_POST['post']);
	$post_type = 'text';

	$added_by = $USER_ID;
	$added_date = date('Y-m-d H:i:00');


	$atpsListQryIns = "INSERT INTO `z_groups_list_posts` (
						`post_text`, 
						`post_type`, 
						`group_id`, 
						`added_by`, 
						`added_date` 
			) VALUES ( ?, ?, ?, ?, ? );";

	if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
		if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiis", $post_text, $post_type, $group_id, $added_by, $added_date)) {
			if (mysqli_stmt_execute($atpsListStmtIns)) {
				$atp_id = (int) mysqli_insert_id($KONN);
				mysqli_stmt_close(statement: $atpsListStmtIns);
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				//Execute failed 
				reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-57";
			}
		} else {
			//bind failed 
			reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-56";
		}
	} else {
		//prepare failed 
		reportError('z_groups_list_posts failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-55";
	}


} else {
	//No request
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-12-4556";
}










