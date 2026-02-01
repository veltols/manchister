<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['employee_no']) &&
		isset($_POST['first_name']) &&
		isset($_POST['last_name']) &&
		isset($_POST['employee_email']) &&
		isset($_POST['employee_pass']) &&
		isset($_POST['department_id'])
	) {

		$employee_id = 0;
		$employee_no = "" . test_inputs($_POST['employee_no']);
		$first_name = "" . test_inputs($_POST['first_name']);
		$last_name = "" . test_inputs($_POST['last_name']);
		$empName = $first_name . " " . $last_name;
		$employee_code = getInitials("" . $empName);


		$employee_email = "" . test_inputs($_POST['employee_email']);
		$employee_pass = "" . test_inputs($_POST['employee_pass']);
		$department_id = (int) test_inputs($_POST['department_id']);


		$dupCheck = false;
		//check if email does not exist
		//Stage 01
		$qu_employees_list_sel = "SELECT `employee_id` FROM  `employees_list` WHERE ((`employee_email` = '$employee_email'))";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE) > 0) {
			//email exist
			$dupCheck = false;
		} else {
			//check users list
			//Stage 02
			$qu_users_list_sel = "SELECT `record_id` FROM  `users_list` WHERE ((`user_email` = '$employee_email'))";
			$qu_users_list_EXE = mysqli_query($KONN, $qu_users_list_sel);
			if (mysqli_num_rows($qu_users_list_EXE) > 0) {
				//email exist
				$dupCheck = false;
			} else {
				$dupCheck = true;
			} // else stage 02
		} // else stage 01

		if ($dupCheck == true) {
			//add user

			$atpsListQryIns = "INSERT INTO `employees_list` (
				`employee_no`, 
				`first_name`, 
				`last_name`, 
				`employee_code`, 
				`employee_email`, 
				`department_id` 
				) VALUES ( ?, ?, ?, ?, ?, ? );";

			if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
				if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssi", $employee_no, $first_name, $last_name, $employee_code, $employee_email, $department_id)) {
					if (mysqli_stmt_execute($atpsListStmtIns)) {
						$employee_id = (int) mysqli_insert_id($KONN);
						mysqli_stmt_close(statement: $atpsListStmtIns);

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

						if (!mysqli_query($KONN, $qu_employees_list_pass_ins)) {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error-3777";
							header('Content-Type: application/json');
							echo json_encode(array($IAM_ARRAY));
							die();
						}

						//get user type
						$user_type = "NA";
						$qu_employees_list_departments_sel = "SELECT `user_type` FROM  `employees_list_departments` WHERE `department_id` = $department_id";
						$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
						if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
							$employees_list_departments_DATA = mysqli_fetch_array($qu_employees_list_departments_EXE);
							$user_type = $employees_list_departments_DATA[0];
						}
						//insert cred record

						$qu_employees_list_creds_ins = "INSERT INTO `employees_list_creds` (
															`employee_id` 
														) VALUES (
															'" . $employee_id . "' 
														);";

						if (!mysqli_query($KONN, $qu_employees_list_creds_ins)) {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error-3777";
							header('Content-Type: application/json');
							echo json_encode(array($IAM_ARRAY));
							die();
						}


						//insert user record
						$qu_users_list_ins = "INSERT INTO `users_list` (
															`user_id`, 
															`user_email`, 
															`user_type`, 
															`int_ext`, 
															`user_family` 
														) VALUES (
															'" . $employee_id . "', 
															'" . $employee_email . "', 
															'" . $user_type . "', 
															'int', 
															'employees_list' 
														);";

						if (!mysqli_query($KONN, $qu_users_list_ins)) {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error-3755";
							header('Content-Type: application/json');
							echo json_encode(array($IAM_ARRAY));
							die();
						}



						//insert employee log
						$log_id = 0;
						$log_action = "Employee_Added";
						$log_date = date('Y-m-d H:i:00');
						$logger_type = "employees_list";
						$log_dept = "IT";
						$logged_by = $USER_ID;


						if (insertSysLog('employees_list', $employee_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						} else {
							reportError('employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
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
				reportError('employees_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-55";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Email Exist in records, Error-335";
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


