<?php




if (
	isset($_POST['group_id']) &&
	isset($_POST['record_id'])
) {
	$group_id = (int) test_inputs($_POST['group_id']);
	$record_id = (int) test_inputs($_POST['record_id']);
	//check if user is admin
	$qu_Qchk_sel = "SELECT * FROM  `z_groups_list_members` WHERE ((`group_id` = $group_id) AND (`employee_id` = $USER_ID) AND (`group_role_id` = 1))";
	$qu_Qchk_EXE = mysqli_query($KONN, $qu_Qchk_sel);
	if (mysqli_num_rows($qu_Qchk_EXE)) {
		//user is admin
		//remove member

		//get emp id
		$employee_id = 0;
		$qu_z_groups_list_members_sel = "SELECT * FROM  `z_groups_list_members` WHERE `record_id` = $record_id";
		$qu_z_groups_list_members_EXE = mysqli_query($KONN, $qu_z_groups_list_members_sel);
		if (mysqli_num_rows($qu_z_groups_list_members_EXE)) {
			$z_groups_list_members_DATA = mysqli_fetch_assoc($qu_z_groups_list_members_EXE);
			$employee_id = (int) $z_groups_list_members_DATA['employee_id'];
		}



		$qu_Qchk_sel = "DELETE FROM  `z_groups_list_members` WHERE ((`group_id` = $group_id) AND (`record_id` = $record_id))";
		if (mysqli_query($KONN, $qu_Qchk_sel)) {






			//Disable permission for added employee
			//check if employee is member in another group
			$isMember = false;
			$qu_z_groups_list_members_sel = "SELECT * FROM  `z_groups_list_members` WHERE ((`group_id` <> $group_id) AND (`employee_id` = $employee_id))";
			$qu_z_groups_list_members_EXE = mysqli_query($KONN, $qu_z_groups_list_members_sel);
			if (mysqli_num_rows($qu_z_groups_list_members_EXE) > 0) {
				$z_groups_list_members_DATA = mysqli_fetch_assoc($qu_z_groups_list_members_EXE);
				$isMember = true;
			}

			if (
				$isMember == false
			) {


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
																	`is_committee` = '0'
																	WHERE `employee_id` = $employee_id;";

				} else {
					//enable groups
					$qu_employees_list_updt = "UPDATE  `employees_list` SET 
																	`is_group` = '0'
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

			}













			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "Member Removed";
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Member removal failed";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "You need to be admin !";
	}

} else {
	//No request
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-12-4556";
}
