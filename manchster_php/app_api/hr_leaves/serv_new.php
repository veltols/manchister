<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['submission_date']) &&
		isset($_POST['start_date']) &&
		isset($_POST['end_date']) &&
		isset($_POST['leave_type_id']) &&
		isset($_POST['leave_remarks']) &&
		isset($_POST['employee_id'])
	) {



		//todo
		//authorize inputs

		$leave_id = 0;
		$submission_date = "" . test_inputs($_POST['submission_date']);
		$start_date = "" . test_inputs($_POST['start_date']);
		$end_date = "" . test_inputs($_POST['end_date']);
		$leave_type_id = (int) test_inputs($_POST['leave_type_id']);
		//$total_days = (int) test_inputs($_POST['total_days']);
		$leave_remarks = "" . test_inputs($_POST['leave_remarks']);
		$employee_id = (int) test_inputs($_POST['employee_id']);
		$leave_status_id = 1;

		//check if file is attached
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
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error in file - 574574568";
					}

				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format";
				}

			}

		}


		$atpsListQryIns = "INSERT INTO `hr_employees_leaves` (
											`submission_date`, 
											`start_date`, 
											`end_date`, 
											`leave_type_id`, 
											`leave_remarks`, 
											`leave_attachment`, 
											`employee_id`, 
											`leave_status_id` 
											) VALUES ( ?, ?, ?, ?, ?, ?, ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssissii", $submission_date, $start_date, $end_date, $leave_type_id, $leave_remarks, $leave_attachment, $employee_id, $leave_status_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$leave_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);


					$log_action = 'Leave_Request_Added';
					$log_remark = '---';
					$logger_type = 'employees_list';
					$logged_by = $USER_ID;
					if (InsertSysLog('hr_employees_leaves', $leave_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "log Failed-548";
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
			reportError('employees_list_designations failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


