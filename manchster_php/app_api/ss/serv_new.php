<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['sent_to_id']) &&
		isset($_POST['ss_description']) &&
		isset($_POST['category_id'])
	) {


		//todo
		//authorize inputs

		$ss_id = 0;
		$ss_description = "" . test_inputs($_POST['ss_description']);
		$status_id = 1;
		$sent_to_id = (int) test_inputs($_POST['sent_to_id']);
		$category_id = (int) test_inputs($_POST['category_id']);
		$ss_added_date = date('Y-m-d H:i:00');


		$added_by = $USER_ID;


		$department_id = 0;
		$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $USER_ID";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE)) {
			$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
			$department_id = (int) $employees_list_DATA['department_id'];
		}

		//generate REF
		$ss_ref = "SR-" . date("ym") . '';
		$qu_ss_list_sel = "SELECT COUNT(`ss_id`) AS `total_tickets` FROM  `ss_list`";
		$qu_ss_list_EXE = mysqli_query($KONN, $qu_ss_list_sel);
		if (mysqli_num_rows($qu_ss_list_EXE)) {
			$ss_list_DATA = mysqli_fetch_array($qu_ss_list_EXE);
			$total_tickets = (int) $ss_list_DATA[0];
			$total_tickets++;
			$ss_ref = $ss_ref . $total_tickets;
		}

		//check if file is attached
		$ss_attachment = "no-img.png";
		$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg', 'csv', 'doc', 'docx', 'xls', 'xlsx');

		if (isset($_FILES['ss_attachment']) && $_FILES['ss_attachment']["tmp_name"]) {
			$thsName = $_FILES['ss_attachment']["name"];
			$thsTemp = $_FILES['ss_attachment']["tmp_name"];
			if (!empty($thsName)) {
				$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);

				if (in_array($fileExt, $allowFileExt)) {

					//upload file to server
					$upload_res = upload_file('ss_attachment', 8000, 'uploads', '../');
					if ($upload_res == true) {
						$ss_attachment = $upload_res;
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



		$atpsListQryIns = "INSERT INTO `ss_list` (
											`ss_ref`, 
											`ss_description`, 
											`ss_attachment`, 
											`status_id`, 
											`category_id`, 
											`ss_added_date`, 
											`department_id`, 
											`added_by`,
											`sent_to_id`
											) VALUES (  ?, ?, ?, ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssiisiii", $ss_ref, $ss_description, $ss_attachment, $status_id, $category_id, $ss_added_date, $department_id, $added_by, $sent_to_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$ss_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Support_Request_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('ss_list', $ss_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('SS log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}

					//notify user
					notifyUser("A new Support Request has been added, REF: " . $ss_ref, 'ss/list/', $added_by);

					//notify user
					notifyUser("A new Support Request has been sent to you, REF: " . $ss_ref, 'ss/list/', $sent_to_id);














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
			reportError('ss_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


