<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;


if ($IS_LOGGED == true && $USER_ID != 0) {


	if (
		isset($_POST['group_id']) &&
		isset($_POST['employee_id']) &&
		isset($_POST['group_role_id'])
	) {

		$record_id = 0;
		$group_id = (int) test_inputs($_POST['group_id']);
		$employee_id = (int) test_inputs($_POST['employee_id']);
		$group_role_id = (int) test_inputs($_POST['group_role_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');
		//check if user is not inserted

		$qu_z_groups_list_members_sel = "SELECT * FROM  `z_groups_list_members` WHERE ((`group_id` = $group_id) AND (`employee_id` = $employee_id))";
		$qu_z_groups_list_members_EXE = mysqli_query($KONN, $qu_z_groups_list_members_sel);
		if (mysqli_num_rows($qu_z_groups_list_members_EXE) > 0) {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Employee already added";
		} else {
			//check if admin
			$qu_Qchk_sel = "SELECT * FROM  `z_groups_list_members` WHERE ((`group_id` = $group_id) AND (`employee_id` = $USER_ID) AND (`group_role_id` = 1))";
			$qu_Qchk_EXE = mysqli_query($KONN, $qu_Qchk_sel);
			if (mysqli_num_rows($qu_Qchk_EXE)) {
				//user is admin
				//add member

				$atpsListQryIns = "INSERT INTO `z_groups_list_members` (
					`group_id`, 
					`employee_id`, 
					`group_role_id`, 
					`added_by`, 
					`added_date` 
					) VALUES ( ?, ?, ?, ?, ? );";

				if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
					if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiis", $group_id, $employee_id, $group_role_id, $added_by, $added_date)) {
						if (mysqli_stmt_execute($atpsListStmtIns)) {
							$atp_id = (int) mysqli_insert_id($KONN);
							mysqli_stmt_close(statement: $atpsListStmtIns);

							//enable permission for added employee
							//get is_commity
							$is_commity = 0;
							$qu_z_groups_list_sel = "SELECT `is_commity` FROM  `z_groups_list` WHERE `group_id` = $group_id";
							$qu_z_groups_list_EXE = mysqli_query($KONN, $qu_z_groups_list_sel);
							if (mysqli_num_rows($qu_z_groups_list_EXE)) {
								$z_groups_list_DATA = mysqli_fetch_assoc($qu_z_groups_list_EXE);
								$is_commity = (int) $z_groups_list_DATA['is_commity'];
							}

							$qu_employees_list_updt = "";
							if ($is_commity == 1) {
								//enable committy
								$qu_employees_list_updt = "UPDATE  `employees_list` SET 
																	`is_committee` = '1'
																	WHERE `employee_id` = $employee_id;";

							} else {
								//enable groups
								$qu_employees_list_updt = "UPDATE  `employees_list` SET 
																	`is_group` = '1'
																	WHERE `employee_id` = $employee_id;";
							}

							if ($qu_employees_list_updt != "") {

								if (mysqli_query($KONN, $qu_employees_list_updt)) {

									$IAM_ARRAY['success'] = true;
									$IAM_ARRAY['message'] = "Succeed";
								} else {
									$IAM_ARRAY['success'] = false;
									$IAM_ARRAY['message'] = "Error-k33443";
								}
							} else {

								$IAM_ARRAY['success'] = true;
								$IAM_ARRAY['message'] = "Succeed";
							}












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
					reportError('z_groups_list_members failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-55";
				}









			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "You need to be admin !";
			}




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


