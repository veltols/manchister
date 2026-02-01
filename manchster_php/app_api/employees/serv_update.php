<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['employee_id']) && isset($_POST['log_remark'])) {
	$employee_id = (int) test_inputs($_POST['employee_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);

	$qqq = "";

	$permChange = false;
	$is_committee = 100;
	$is_group = 100;
	if (isset($_POST['is_committee'])) {
		$is_committee = (int) test_inputs($_POST['is_committee']);
		$qqq = $qqq . "`is_committee` = '" . $is_committee . "', ";
		$permChange = true;
	}
	if (isset($_POST['is_group'])) {
		$is_group = (int) test_inputs($_POST['is_group']);
		$qqq = $qqq . "`is_group` = '" . $is_group . "', ";
		$permChange = true;
	}

	if (isset($_POST['employee_no'])) {
		$employee_no = test_inputs($_POST['employee_no']);
		$qqq = $qqq . "`employee_no` = '" . $employee_no . "', ";
	}
	if (isset($_POST['first_name'])) {
		$first_name = test_inputs($_POST['first_name']);
		$qqq = $qqq . "`first_name` = '" . $first_name . "', ";
	}
	if (isset($_POST['second_name'])) {
		$second_name = test_inputs($_POST['second_name']);
		$qqq = $qqq . "`second_name` = '" . $second_name . "', ";
	}
	if (isset($_POST['third_name'])) {
		$third_name = test_inputs($_POST['third_name']);
		$qqq = $qqq . "`third_name` = '" . $third_name . "', ";
	}
	if (isset($_POST['last_name'])) {
		$last_name = test_inputs($_POST['last_name']);
		$qqq = $qqq . "`last_name` = '" . $last_name . "', ";
	}
	if (isset($_POST['employee_dob'])) {
		$employee_dob = test_inputs($_POST['employee_dob']);
		$qqq = $qqq . "`employee_dob` = '" . $employee_dob . "', ";
	}
	if (isset($_POST['employee_code'])) {
		$employee_code = test_inputs($_POST['employee_code']);
		$qqq = $qqq . "`employee_code` = '" . $employee_code . "', ";
	}
	if (isset($_POST['employee_type'])) {
		$employee_type = test_inputs($_POST['employee_type']);
		$qqq = $qqq . "`employee_type` = '" . $employee_type . "', ";
	}
	if (isset($_POST['title_id'])) {
		$title_id = test_inputs($_POST['title_id']);
		$qqq = $qqq . "`title_id` = '" . $title_id . "', ";
	}
	if (isset($_POST['gender_id'])) {
		$gender_id = test_inputs($_POST['gender_id']);
		$qqq = $qqq . "`gender_id` = '" . $gender_id . "', ";
	}
	if (isset($_POST['nationality_id '])) {
		$nationality_id = test_inputs($_POST['nationality_id']);
		$qqq = $qqq . "`nationality_id ` = '" . $nationality_id . "', ";
	}
	if (isset($_POST['timezone_id'])) {
		$timezone_id = test_inputs($_POST['timezone_id']);
		$qqq = $qqq . "`timezone_id` = '" . $timezone_id . "', ";
	}
	if (isset($_POST['employee_email'])) {
		$employee_email = test_inputs($_POST['employee_email']);
		$qqq = $qqq . "`employee_email` = '" . $employee_email . "', ";
	}
	if (isset($_POST['employee_picture'])) {
		$employee_picture = test_inputs($_POST['employee_picture']);
		$qqq = $qqq . "`employee_picture` = '" . $employee_picture . "', ";
	}
	if (isset($_POST['department_id'])) {
		$department_id = test_inputs($_POST['department_id']);
		$qqq = $qqq . "`department_id` = '" . $department_id . "', ";
	}

	if (isset($_POST['department_id'])) {
		$department_id = test_inputs($_POST['department_id']);
		$qqq = $qqq . "`department_id` = '" . $department_id . "', ";
	}
	if (isset($_POST['certificate_id'])) {
		$certificate_id = test_inputs($_POST['certificate_id']);
		$qqq = $qqq . "`certificate_id` = '" . $certificate_id . "', ";
	}
	if (isset($_POST['employee_join_date'])) {
		$employee_join_date = test_inputs($_POST['employee_join_date']);
		$qqq = $qqq . "`employee_join_date` = '" . $employee_join_date . "', ";
	}
	if (isset($_POST['company_name'])) {
		$company_name = test_inputs($_POST['company_name']);
		$qqq = $qqq . "`company_name` = '" . $company_name . "', ";
	}
	if (isset($_POST['leaves_open_balance'])) {
		$leaves_open_balance = test_inputs($_POST['leaves_open_balance']);
		$qqq = $qqq . "`leaves_open_balance` = '" . $leaves_open_balance . "', ";
	}
	if (isset($_POST['nationality_id'])) {
		$nationality_id = test_inputs($_POST['nationality_id']);
		$qqq = $qqq . "`nationality_id` = '" . $nationality_id . "', ";
	}
	if (isset($_POST['is_deleted'])) {
		$is_deleted = test_inputs($_POST['is_deleted']);
		$qqq = $qqq . "`is_deleted` = '" . $is_deleted . "', ";
	}
	if ($permChange == false) {
		$qqq = $qqq . "`is_new` = '0', ";
	}
	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_employees_list_updt = "UPDATE `employees_list` SET " . $qqq . "  WHERE `employee_id` = $employee_id;";

		if (mysqli_query($KONN, $qu_employees_list_updt)) {


			//insert Employee log
			$log_id = 0;
			$log_action = "Employee_Data_changed";
			$logger_type = "employees_list";
			$logged_by = $USER_ID;

			if (insertSysLog('employees_list', $employee_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}

			if ($permChange == true) {
				$log_action = "Employee_Permission_changed-g=" . $is_group . "c=" . $is_committee;

				if (insertSysLog('employees_list', $employee_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";
				} else {
					reportError('Employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
				}

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

