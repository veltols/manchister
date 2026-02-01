<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['employee_id'])) {


	$employee_id = (int) test_inputs($_POST['employee_id']);
	$log_remark = (isset($_POST['log_remark'])) ? "" . test_inputs($_POST['log_remark']) : '---';
	$is_pass = (isset($_POST['is_pass'])) ? (int) test_inputs($_POST['is_pass']) : 0;
	if ($is_pass == 0) {

		$qqq = "";

		if (isset($_POST['is_active'])) {
			$is_active = (int) test_inputs($_POST['is_active']);
			$qqq = $qqq . "`is_active` = '" . $is_active . "', ";
		}



		if ($qqq != "") {
			$qqq = substr($qqq, 0, -2);

			$qu_employees_list_updt = "UPDATE `users_list` SET " . $qqq . "  WHERE `user_id` = $employee_id;";


			if (mysqli_query($KONN, $qu_employees_list_updt)) {
				$newStt = 'NA';
				if ($is_active == 1) {
					$newStt = 'Activated';
				} else if ($is_active == 0) {
					$newStt = 'Disabled';
				}

				//insert Employee log
				$log_id = 0;
				$log_action = "Employee_Status_changed-" . $newStt;
				$log_date = date('Y-m-d H:i:00');
				$logger_type = "employees_list";
				$log_dept = "IT";
				$logged_by = $USER_ID;


				if ($newStt != 'NA') {

					if (insertSysLog('employees_list', $employee_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('Employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}
				}




			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-5675";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-34345";
		}
	} else if ($is_pass == 1) {
		if (isset($_POST['employee_pass'])) {

			$employee_pass = "" . test_inputs($_POST['employee_pass']);


			//deactivate old pass
			$qu_employees_list_pass_updt = "UPDATE  `employees_list_pass` SET 
			`is_active` = '0'
			WHERE `employee_id` = $employee_id;";

			if (mysqli_query($KONN, $qu_employees_list_pass_updt)) {
				//insert emp pass
				$is_active = 1;
				$pass_value = hashPass($employee_pass);
				$qu_employees_list_pass_ins = "INSERT INTO `employees_list_pass` (
																	`pass_value`, 
																	`employee_id`, 
																	`is_active` 
																	) VALUES (
																	'" . $pass_value . "', 
																	'" . $employee_id . "', 
																	'" . $is_active . "' 
																	);";

				if (mysqli_query($KONN, $qu_employees_list_pass_ins)) {




					$qu_employees_list_updt = "UPDATE  `employees_list` SET 
										`is_pass` = '1'
										WHERE `employee_id` = $employee_id;";

					if (!mysqli_query($KONN, $qu_employees_list_updt)) {
						die('Errorr-654654');
					}






					//insert employee log
					$log_id = 0;
					$log_action = "Employee_Pass_Change";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('employees_list', $employee_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}
				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error-3777";
				}
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Error-3443";
			}



		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "No-Password";
		}
	}








} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

