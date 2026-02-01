<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['asset_id']) && isset($_POST['op'])) {

	$asset_id = (int) test_inputs($_POST['asset_id']);
	$op = (int) test_inputs($_POST['op']);
	$qqq = "";

	if ($op == 1) {
		//reassign
		if (isset($_POST['assigned_to']) && isset($_POST['assign_remark'])) {
			$assigned_to = (int) test_inputs($_POST['assigned_to']);
			$assign_remark = "" . test_inputs($_POST['assign_remark']);
			$qqq = $qqq . "`assigned_to` = '" . $assigned_to . "', ";
			$assigned_date = date('Y-m-d H:i:00');
			$qqq = $qqq . "`assigned_date` = '" . $assigned_date . "', ";


			$department_id = 0;
			if ($assigned_to == 0) {
				$qqq = $qqq . "`status_id` = '1', ";
			} else {
				$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $assigned_to";
				$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
				if (mysqli_num_rows($qu_employees_list_EXE)) {
					$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
					$department_id = (int) $employees_list_DATA['department_id'];
					$qqq = $qqq . "`status_id` = '2', ";
				}
			}
			$qqq = $qqq . "`department_id` = '" . $department_id . "', ";

			if ($qqq != "") {
				$qqq = substr($qqq, 0, -2);

				$qu_z_assets_list_updt = "UPDATE `z_assets_list` SET " . $qqq . "  WHERE `asset_id` = $asset_id;";


				if (mysqli_query($KONN, $qu_z_assets_list_updt)) {


					//add asset assign log
					if (InsertAssetAssign($asset_id, $USER_ID, $assigned_to, $assign_remark)) {

						//insert asset log
						$log_id = 0;
						$log_action = "Asset_Assigned-" . $assigned_to;
						if ($assigned_to == 0) {
							$log_action = "Asset_Assign_Revoked";
						}
						$log_remark = $assign_remark;
						$log_date = date('Y-m-d H:i:00');
						$logger_type = "employees_list";
						$log_dept = "IT";
						$logged_by = $USER_ID;
						if (insertSysLog('z_assets_list', $asset_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						} else {
							reportError('Asset log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
						}

					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error-34334";
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
			$IAM_ARRAY['message'] = "ERR-2211";
		}
		//--------------------------------------------------------------------------------------------------------------
	} else if ($op == 2) {
		//status
		if (isset($_POST['status_id']) && isset($_POST['assign_remark'])) {
			$status_id = (int) test_inputs($_POST['status_id']);
			$assign_remark = "" . test_inputs($_POST['assign_remark']);
			$qqq = $qqq . "`status_id` = '" . $status_id . "', ";



			if ($qqq != "") {
				$qqq = substr($qqq, 0, -2);

				$qu_z_assets_list_updt = "UPDATE `z_assets_list` SET " . $qqq . "  WHERE `asset_id` = $asset_id;";


				if (mysqli_query($KONN, $qu_z_assets_list_updt)) {


					//insert asset log
					$log_id = 0;
					$log_action = "Asset_Status_Change-" . $status_id;
					$log_remark = $assign_remark;
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;
					if (insertSysLog('z_assets_list', $asset_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('Asset log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
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
			$IAM_ARRAY['message'] = "ERR-2211";
		}
		//--------------------------------------------------------------------------------------------------------------
	} else if ($op == 3) {
		//revoke assign
		if (isset($_POST['assign_remark'])) {
			$assigned_to = 0;
			$assign_remark = "" . test_inputs($_POST['assign_remark']);
			$qqq = $qqq . "`assigned_to` = '" . $assigned_to . "', ";
			$assigned_date = date('Y-m-d H:i:00');
			$qqq = $qqq . "`assigned_date` = '" . $assigned_date . "', ";
			$qqq = $qqq . "`status_id` = '1', ";

			if ($qqq != "") {
				$qqq = substr($qqq, 0, -2);

				$qu_z_assets_list_updt = "UPDATE `z_assets_list` SET " . $qqq . "  WHERE `asset_id` = $asset_id;";


				if (mysqli_query($KONN, $qu_z_assets_list_updt)) {


					//add asset assign log
					if (InsertAssetAssign($asset_id, $USER_ID, $assigned_to, $assign_remark)) {

						//insert asset log
						$log_id = 0;
						$log_action = "Asset_Assign_Revoked";
						$log_remark = $assign_remark;
						$log_date = date('Y-m-d H:i:00');
						$logger_type = "employees_list";
						$log_dept = "IT";
						$logged_by = $USER_ID;
						if (insertSysLog('z_assets_list', $asset_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						} else {
							reportError('Asset log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
						}

					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error-56444";
					}



				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-7875";
				}
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-7987";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-334454342";
		}
		//--------------------------------------------------------------------------------------------------------------
	}










} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

