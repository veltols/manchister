<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['permission_id']) && isset($_POST['log_remark'])) {

	$permission_id = (int) test_inputs($_POST['permission_id']);
	$log_remark = "" . test_inputs($_POST['log_remark']);
	$qqq = "";


	if (isset($_POST['permission_status_id'])) {
		$permission_status_id = test_inputs($_POST['permission_status_id']);
		$qqq = $qqq . "`permission_status_id` = '" . $permission_status_id . "', ";
	}
	$empUpdate = false;
	if ($permission_status_id == 3) {
		//update employee data
		$empUpdate = true;
	}
	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_hr_employees_permissions_updt = "UPDATE  `hr_employees_permissions` SET " . $qqq . "  WHERE `permission_id` = $permission_id;";

		if (mysqli_query($KONN, $qu_hr_employees_permissions_updt)) {


			$allowedUpdate = true;

			if ($empUpdate == true) {
				//get employee id and employee perm balance
				$employee_id = 0;
				$total_hours = 0;
				$qu_hr_employees_permissions_sel = "SELECT `employee_id`, `total_hours` FROM  `hr_employees_permissions` WHERE `permission_id` = $permission_id";
				$qu_hr_employees_permissions_EXE = mysqli_query($KONN, $qu_hr_employees_permissions_sel);
				if (mysqli_num_rows($qu_hr_employees_permissions_EXE)) {
					$hr_employees_permissions_DATA = mysqli_fetch_assoc($qu_hr_employees_permissions_EXE);
					$employee_id = (int) $hr_employees_permissions_DATA['employee_id'];
					$total_hours = (int) $hr_employees_permissions_DATA['total_hours'];
					if ($employee_id != 0) {

						$permission_hours = 0;
						$permission_hours_balance = 0;

						$qu_employees_list_sel = "SELECT `allowed_permission_hours`, `permission_hours_balance` FROM  `employees_list` WHERE `employee_id` = $employee_id";
						$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
						if (mysqli_num_rows($qu_employees_list_EXE)) {
							$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
							$allowed_permission_hours = (int) $employees_list_DATA['allowed_permission_hours'];
							$permission_hours_balance = (int) $employees_list_DATA['permission_hours_balance'];

							$newTotalHours = $permission_hours_balance + $total_hours;
							if ($newTotalHours > $allowed_permission_hours) {
								//reject permission
								$allowedUpdate = false;
							} else {
								//allow permission and update emp data

								$allowedUpdate = true;
								$qu_employees_list_updt = "UPDATE `employees_list` SET `permission_hours_balance` = '" . $newTotalHours . "' WHERE `employee_id` = $employee_id;";

								if (!mysqli_query($KONN, $qu_employees_list_updt)) {
									die('GeneralError-34967');
								}

							}


						}
					}
				}




			}

			if ($allowedUpdate == true) {

				$log_action = 'Status_Change';
				$logger_type = 'employees_list';
				$logged_by = $USER_ID;
				if (InsertSysLog('hr_employees_permissions', $permission_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";
				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "log Failed-548";
				}

			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Employee exceeded allowed permission";
			}


		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-11221";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-232344";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

