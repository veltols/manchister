<?php


//load employee info
$employee_id = 0;
$employee_code = "";
$employee_name = "";
$timezone_id = 0;
$employee_email = "";
$employee_picture = "";
$department_id = 0;
$employeesListQrySel = "SELECT `employee_id`, `employee_code`, CONCAT(`first_name`, ' ' ,`last_name`) AS `namer`, `timezone_id`, `employee_email`, `employee_picture`, `department_id` FROM  `employees_list` WHERE ( ( `employee_id` = ? ) AND ( `is_deleted` = 0 ) ) LIMIT 1 ";


if ($employeesListStt = mysqli_prepare($KONN, $employeesListQrySel)) {
	if (mysqli_stmt_bind_param($employeesListStt, "i", $user_id)) {
		if (mysqli_stmt_execute($employeesListStt)) {
			$employeesListRows = mysqli_stmt_get_result($employeesListStt);
			while ($employeesListRecord = mysqli_fetch_assoc($employeesListRows)) {
				$employee_id = (int) $employeesListRecord['employee_id'];
				$employee_code = "" . $employeesListRecord['employee_code'];
				$employee_name = "" . $employeesListRecord['namer'];
				$timezone_id = (int) $employeesListRecord['timezone_id'];
				$employee_picture = "" . $employeesListRecord['employee_picture'];
				$department_id = (int) $employeesListRecord['department_id'];

			}
		} else {
			//Execute failed 
			reportError("A-" . mysqli_stmt_error($employeesListStt), $encIdder, '0');
		}
	} else {
		//bind failed 
		reportError("B-" . mysqli_stmt_error($employeesListStt), $encIdder, '0');
	}
} else {
	//prepare failed 
	reportError('employees_list failed to prepare stmt ', $encIdder, '0');
}
mysqli_stmt_close($employeesListStt);


$dbPass = '';

if ($employee_id != 0 && $employee_name != '') {


	//get DB pass
	$employeesListPassQrySnglSel = "SELECT `pass_value` FROM  `employees_list_pass` WHERE ( ( `employee_id` = ? ) AND ( `is_active` = 1 ) ) LIMIT 1 ";
	if ($employeesListPassStt = mysqli_prepare($KONN, $employeesListPassQrySnglSel)) {
		if (mysqli_stmt_bind_param($employeesListPassStt, "i", $employee_id)) {
			if (mysqli_stmt_execute($employeesListPassStt)) {
				if (mysqli_stmt_bind_result($employeesListPassStt, $dbPass)) {
					if (!mysqli_stmt_fetch($employeesListPassStt)) {
						//fetch failed 
						reportError("C-" . mysqli_stmt_error($employeesListPassStt), 'employees_list', $employee_id);
					}
				} else {
					//result bind failed 
					reportError("D-" . mysqli_stmt_error($employeesListPassStt), 'employees_list', $employee_id);
				}
			} else {
				//Execute failed 
				reportError("E-" . mysqli_stmt_error($employeesListPassStt), 'employees_list', $employee_id);
			}
		} else {
			//bind failed 
			reportError("F-" . mysqli_stmt_error($employeesListPassStt), 'employees_list', $employee_id);
		}
	} else {
		//prepare failed 
		reportError('employees_list_pass failed to prepare stmt ', 'employees_list', $employee_id);
	}
	mysqli_stmt_close($employeesListPassStt);

	//verify pass
	if (verifyPass($passer, $dbPass)) {


		//get timezone
		$tmiezone_name = "";
		$qu_sys_timezones_list_sel = "SELECT `tmiezone_name` FROM  `sys_timezones_list` WHERE `timezone_id` = $timezone_id";
		$qu_sys_timezones_list_EXE = mysqli_query($KONN, $qu_sys_timezones_list_sel);
		if (mysqli_num_rows($qu_sys_timezones_list_EXE)) {
			$sys_timezones_list_DATA = mysqli_fetch_assoc($qu_sys_timezones_list_EXE);
			$tmiezone_name = $sys_timezones_list_DATA['tmiezone_name'];
		}

		//delete auth tokens
		//delete all auth tokens
		$authTokensQryDel = "DELETE FROM `auth_tokens` WHERE ( ( `token_idder` = ? ) ) ";
		if ($authTokensStmtDel = mysqli_prepare($KONN, $authTokensQryDel)) {
			if (mysqli_stmt_bind_param($authTokensStmtDel, "s", $encIdder)) {
				if (!mysqli_stmt_execute($authTokensStmtDel)) {
					//Execute failed 
					reportError("G-" . mysqli_stmt_error($authTokensStmtDel), 'employees_list', $employee_id);
					$RES = false;
					$error = 100;
				}
			} else {
				//bind failed 
				reportError("H-" . mysqli_stmt_error($authTokensStmtDel), 'employees_list', $employee_id);
				$RES = false;
				$error = 200;
			}
		} else {
			//prepare failed 
			reportError('auth_tokens failed to prepare stmt ', 'employees_list', $employee_id);
			$RES = false;
			$error = 300;
		}
		mysqli_stmt_close($authTokensStmtDel);

		//TODO
		//verify OTP

		//login

		$RES = true;
		$_SESSION['user_code'] = $employee_code;
		$_SESSION['user_type'] = $user_type;
		$_SESSION['user_id'] = $employee_id;
		$_SESSION['user_name'] = $employee_name;
		$_SESSION['user_email'] = $employee_email;
		$_SESSION['user_picture'] = $employee_picture;
		$_SESSION['tmiezone_name'] = $tmiezone_name;



	} else {
		//err pass
		$RES = false;
		$error = 6500;
	}


} else {
	//err id
	$RES = false;
	$error = 1200;
}