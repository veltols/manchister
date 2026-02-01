<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['ss_id']) && isset($_POST['status_id']) && isset($_POST['ss_remarks'])) {

	$ss_id = (int) test_inputs($_POST['ss_id']);
	$status_id = (int) test_inputs($_POST['status_id']);
	$ss_remarks = "" . test_inputs("" . $_POST['ss_remarks']);

	$log_date = date('Y-m-d H:i:00');

	$qqq = "";

	$log_action = "Request_Status_changed";
	$qqq = $qqq . "`status_id` = '" . $status_id . "', ";


	if ($status_id == 2) {
		//In Progress
		$qqq = $qqq . "`status_id` = '" . $status_id . "', ";
		$qqq = $qqq . "`ss_remarks` = '" . $ss_remarks . "', ";
	} else if ($status_id == 3) {
		//finished
		//require File
		$qqq = $qqq . "`status_id` = '" . $status_id . "', ";
		$qqq = $qqq . "`ss_remarks` = '" . $ss_remarks . "', ";
		$qqq = $qqq . "`ss_end_date` = '" . $log_date . "', ";
		//ask for file
		$ss_result_attachment = "";
		$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg', 'csv', 'doc', 'docx', 'xls', 'xlsx');

		if (isset($_FILES['ss_result_attachment']) && $_FILES['ss_result_attachment']["tmp_name"]) {
			$thsName = $_FILES['ss_result_attachment']["name"];
			$thsTemp = $_FILES['ss_result_attachment']["tmp_name"];
			if (!empty($thsName)) {
				$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);

				if (in_array($fileExt, $allowFileExt)) {

					//upload file to server
					$upload_res = upload_file('ss_result_attachment', 8000, 'uploads', '../');
					if ($upload_res == true) {
						$ss_result_attachment = $upload_res;
						$qqq = $qqq . "`ss_result_attachment` = '" . $ss_result_attachment . "', ";
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


	} else {
		$qqq = "";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_ss_list_updt = "UPDATE `ss_list` SET " . $qqq . "  WHERE `ss_id` = $ss_id;";


		if (mysqli_query($KONN, $qu_ss_list_updt)) {

			//insert Ticket log
			$log_id = 0;
			$log_remark = $ss_remarks;
			$logger_type = "employees_list";
			$log_dept = "EMP";
			$logged_by = $USER_ID;


			if (insertSysLog('ss_list', $ss_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Ticket log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}


			$added_by = 0;
			$qu_ss_list_sel = "SELECT `added_by` FROM  `ss_list` WHERE `ss_id` = $ss_id";
			$qu_ss_list_EXE = mysqli_query($KONN, $qu_ss_list_sel);
			if (mysqli_num_rows($qu_ss_list_EXE)) {
				$ss_list_DATA = mysqli_fetch_assoc($qu_ss_list_EXE);
				$added_by = (int) $ss_list_DATA['added_by'];
				notifyUser("Your Request has been updated", "ss/list/", $added_by);
			}






		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-34345";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

