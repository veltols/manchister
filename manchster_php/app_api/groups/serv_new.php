<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;


if ($IS_LOGGED == true && $USER_ID != 0) {


	if (
		isset($_POST['is_com']) &&
		isset($_POST['group_name']) &&
		isset($_POST['group_desc']) &&
		isset($_POST['group_color_id'])
	) {





		$group_id = 0;
		$group_name = "" . test_inputs($_POST['group_name']);
		$group_desc = "" . test_inputs($_POST['group_desc']);
		$group_color_id = (int) test_inputs($_POST['group_color_id']);
		$is_com = (int) test_inputs($_POST['is_com']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');

		$atpsListQryIns = "INSERT INTO `z_groups_list` (
						`group_name`, 
						`group_desc`, 
						`group_color_id`, 
						`added_by`, 
						`added_date`,  
						`is_commity` 
						) VALUES ( ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiisi", $group_name, $group_desc, $group_color_id, $added_by, $added_date, $is_com)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$group_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);

					//insert role for current user as admin member
					$qu_z_groups_list_members_ins = "INSERT INTO `z_groups_list_members` (
						`group_id`, 
						`employee_id`, 
						`group_role_id`, 
						`added_by`, 
						`added_date` 
					) VALUES (
						'" . $group_id . "', 
						'" . $USER_ID . "', 
						'1', 
						'" . $USER_ID . "', 
						'" . $added_date . "' 
					);";

					if (mysqli_query($KONN, $qu_z_groups_list_members_ins)) {
						$record_id = mysqli_insert_id($KONN);
						if ($record_id != 0) {

						}
					}

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
			reportError('z_groups_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-55";
		}


	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}

















} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


