<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['employee_id']) && isset($_POST['log_remark'])) {

	$employee_id = (int) test_inputs($_POST['employee_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);

	$qqq = "";

	if (isset($_POST['passport_issue_date'])) {
		$passport_issue_date = test_inputs($_POST['passport_issue_date']);
		$qqq = $qqq . "`passport_issue_date` = '" . $passport_issue_date . "', ";
	}
	if (isset($_POST['passport_expiry_date'])) {
		$passport_expiry_date = test_inputs($_POST['passport_expiry_date']);
		$qqq = $qqq . "`passport_expiry_date` = '" . $passport_expiry_date . "', ";
	}
	if (isset($_POST['passport_no'])) {
		$passport_no = test_inputs($_POST['passport_no']);
		$qqq = $qqq . "`passport_no` = '" . $passport_no . "', ";
	}
	if (isset($_POST['visa_issue_date'])) {
		$visa_issue_date = test_inputs($_POST['visa_issue_date']);
		$qqq = $qqq . "`visa_issue_date` = '" . $visa_issue_date . "', ";
	}
	if (isset($_POST['visa_expiry_date'])) {
		$visa_expiry_date = test_inputs($_POST['visa_expiry_date']);
		$qqq = $qqq . "`visa_expiry_date` = '" . $visa_expiry_date . "', ";
	}
	if (isset($_POST['visa_no'])) {
		$visa_no = test_inputs($_POST['visa_no']);
		$qqq = $qqq . "`visa_no` = '" . $visa_no . "', ";
	}
	if (isset($_POST['eid_issue_date'])) {
		$eid_issue_date = test_inputs($_POST['eid_issue_date']);
		$qqq = $qqq . "`eid_issue_date` = '" . $eid_issue_date . "', ";
	}
	if (isset($_POST['eid_expiry_date'])) {
		$eid_expiry_date = test_inputs($_POST['eid_expiry_date']);
		$qqq = $qqq . "`eid_expiry_date` = '" . $eid_expiry_date . "', ";
	}
	if (isset($_POST['eid_no'])) {
		$eid_no = test_inputs($_POST['eid_no']);
		$qqq = $qqq . "`eid_no` = '" . $eid_no . "', ";
	}
	if (isset($_POST['labour_issue_date'])) {
		$labour_issue_date = test_inputs($_POST['labour_issue_date']);
		$qqq = $qqq . "`labour_issue_date` = '" . $labour_issue_date . "', ";
	}
	if (isset($_POST['labour_expiry_date'])) {
		$labour_expiry_date = test_inputs($_POST['labour_expiry_date']);
		$qqq = $qqq . "`labour_expiry_date` = '" . $labour_expiry_date . "', ";
	}
	if (isset($_POST['labour_no'])) {
		$labour_no = test_inputs($_POST['labour_no']);
		$qqq = $qqq . "`labour_no` = '" . $labour_no . "', ";
	}
	if (isset($_POST['license_issue_date'])) {
		$license_issue_date = test_inputs($_POST['license_issue_date']);
		$qqq = $qqq . "`license_issue_date` = '" . $license_issue_date . "', ";
	}
	if (isset($_POST['license_expiry_date'])) {
		$license_expiry_date = test_inputs($_POST['license_expiry_date']);
		$qqq = $qqq . "`license_expiry_date` = '" . $license_expiry_date . "', ";
	}
	if (isset($_POST['license_no'])) {
		$license_no = test_inputs($_POST['license_no']);
		$qqq = $qqq . "`license_no` = '" . $license_no . "', ";
	}

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_employees_list_updt = "UPDATE  `employees_list_creds` SET " . $qqq . "  WHERE `employee_id` = $employee_id;";

		if (mysqli_query($KONN, $qu_employees_list_updt)) {
			//insert Employee log
			$log_id = 0;
			$log_action = "Employee_Credintials_changed";
			$logger_type = "employees_list";
			$logged_by = $USER_ID;

			if (insertSysLog('employees_list', $employee_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-344343";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-564654";
	}






} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

