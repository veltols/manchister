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

		$qu_Qchk_sel = "UPDATE `z_groups_list_members` SET `group_role_id` = 1 WHERE ((`group_id` = $group_id) AND (`record_id` = $record_id))";
		if (mysqli_query($KONN, $qu_Qchk_sel)) {
			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "Member Promoted";
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Member Promote failed";
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
