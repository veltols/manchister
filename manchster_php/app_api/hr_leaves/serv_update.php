<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
$qqq = "";
if (
	$IS_LOGGED == true &&
	$USER_ID != 0 &&
	isset($_POST['leave_id']) &&
	isset($_POST['is_owner'])
) {

	$leave_id = (int) test_inputs($_POST['leave_id']);
	$leave_attachment = "no-img.png";
	$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg', 'csv', 'doc', 'docx', 'xls', 'xlsx');

	if (isset($_FILES['leave_attachment']) && $_FILES['leave_attachment']["tmp_name"]) {
		$thsName = $_FILES['leave_attachment']["name"];
		$thsTemp = $_FILES['leave_attachment']["tmp_name"];
		if (!empty($thsName)) {
			$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);

			if (in_array($fileExt, $allowFileExt)) {

				//upload file to server
				$upload_res = upload_file('leave_attachment', 8000, 'uploads', '../');
				if ($upload_res == true) {
					$leave_attachment = $upload_res;
					$qqq = $qqq . "`leave_status_id` = '1', ";
					$qqq = $qqq . "`leave_attachment` = '" . $leave_attachment . "' ";

					$qu_hr_employees_leaves_updt = "UPDATE  `hr_employees_leaves` SET " . $qqq . "  WHERE `leave_id` = $leave_id;";
					if (mysqli_query($KONN, $qu_hr_employees_leaves_updt)) {

						$log_action = 'Leave_Updated';
						$log_remark = 'Leave_Updated_By_Employee-Attachment Added';
						$logger_type = 'employees_list';
						$logged_by = $USER_ID;

						if (InsertSysLog('hr_employees_leaves', $leave_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "log Failed-22";
						}


					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-3434";
					}


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file - 574574568";
				}

			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Error in file format";
			}

		}

	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Missing attachment";
	}


} else if (
	$IS_LOGGED == true &&
	$USER_ID != 0 &&
	isset($_POST['leave_id']) &&
	isset($_POST['leave_status_id']) &&
	isset($_POST['log_remark'])
) {

	$leave_id = (int) test_inputs($_POST['leave_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$thsStt = (int) test_inputs($_POST['leave_status_id']);
	$qqq = "";


	$leave_status_id = 0;
	if ($thsStt == 100) {
		//send to approval
		$leave_status_id = 2;
		$qqq = $qqq . "`leave_status_id` = '" . $leave_status_id . "', ";
	} else if ($thsStt == 200) {
		//send back to user
		$leave_status_id = 6;
		$qqq = $qqq . "`leave_status_id` = '" . $leave_status_id . "', ";
	}


	//get user ID
	$employee_id = 0;
	$department_id = 0;
	$line_manager_id = 0;
	$qu_hr_employees_leaves_sel = "SELECT `employee_id`FROM  `hr_employees_leaves` WHERE `leave_id` = $leave_id";
	$qu_hr_employees_leaves_EXE = mysqli_query($KONN, $qu_hr_employees_leaves_sel);
	if (mysqli_num_rows($qu_hr_employees_leaves_EXE)) {
		$hr_employees_leaves_DATA = mysqli_fetch_array($qu_hr_employees_leaves_EXE);
		$employee_id = (int) $hr_employees_leaves_DATA[0];
	}

	//get user department
	$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $employee_id";
	$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
	if (mysqli_num_rows($qu_employees_list_EXE)) {
		$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
		$department_id = (int) $employees_list_DATA['department_id'];
	}

	//get line manager ID
	$qu_employees_list_departments_sel = "SELECT `line_manager_id` FROM  `employees_list_departments` WHERE `department_id` = $department_id";
	$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
	if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
		$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
		$line_manager_id = (int) $employees_list_departments_DATA['line_manager_id'];
	}

	if ($line_manager_id == 0) {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Line Manager is not configured";
	} else {

		if ($qqq != "") {
			$qqq = substr($qqq, 0, -2);
			$qu_hr_employees_leaves_updt = "UPDATE  `hr_employees_leaves` SET " . $qqq . "  WHERE `leave_id` = $leave_id;";

			if (mysqli_query($KONN, $qu_hr_employees_leaves_updt)) {

				$log_action = 'Leave_Updated';
				$logger_type = 'employees_list';
				$logged_by = $USER_ID;

				if (InsertSysLog('hr_employees_leaves', $leave_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";
				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "log Failed-548";
				}

				if ($thsStt == 100) {
					//send to approval
					notifyUser("You have pending approval", "hr_approvals/list/", $line_manager_id);
					notifyUser("Your leave application has been sent for approval", "hr_leaves/list/", $employee_id);
					insertHRApprovalRequest("hr_leaves", $leave_id, $line_manager_id, $log_remark, $USER_ID);

				} else if ($thsStt == 200) {
					//send back to user
					//notify user
					notifyUser("Your request is pending your action - " . $log_remark, "hr_leaves/list/", $employee_id);
				}

			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-11221";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-232344";
		}

	}








} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

