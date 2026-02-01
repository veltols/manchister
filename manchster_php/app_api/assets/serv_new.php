<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['category_id']) &&
		isset($_POST['asset_name']) &&
		isset($_POST['asset_description']) &&
		isset($_POST['asset_sku']) &&
		isset($_POST['asset_serial']) &&
		isset($_POST['status_id'])

	) {


		//todo
		//authorize inputs

		$asset_id = 0;
		$asset_name = "" . test_inputs($_POST['asset_name']);
		$asset_description = "" . test_inputs($_POST['asset_description']);
		$asset_sku = "" . test_inputs($_POST['asset_sku']);
		$asset_serial = "" . test_inputs($_POST['asset_serial']);
		$assign_remark = "";

		$category_id = (int) test_inputs($_POST['category_id']);

		$status_id = (int) test_inputs($_POST['status_id']);
		$assigned_date = date('Y-m-d H:i:00');
		$added_date = date('Y-m-d H:i:00');
		$assigned_by = $USER_ID;
		$added_by = $USER_ID;
		$expiry_date = "";

		if (isset($_POST['expiry_date'])) {
			$expiry_date = "" . test_inputs($_POST['expiry_date']);
		}
		$purchase_date = "";

		if (isset($_POST['purchase_date'])) {
			$purchase_date = "" . test_inputs($_POST['purchase_date']);
		}


		//generate REF
		$asset_ref = 'ASST-' . date("ym") . '0';
		$qu_z_assets_list_sel = "SELECT COUNT(`asset_id`) AS `total_assets` FROM  `z_assets_list`";
		$qu_z_assets_list_EXE = mysqli_query($KONN, $qu_z_assets_list_sel);
		if (mysqli_num_rows($qu_z_assets_list_EXE)) {
			$z_assets_list_DATA = mysqli_fetch_array($qu_z_assets_list_EXE);
			$total_assets = (int) $z_assets_list_DATA[0];
			$total_assets++;
			$asset_ref = $asset_ref . $total_assets;
		}

		$department_id = 0;
		/*
														$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $assigned_to";
														$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
														if (mysqli_num_rows($qu_employees_list_EXE)) {
															$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
															$department_id = (int) $employees_list_DATA['department_id'];
														}

														

									  //add asset assign log
									  if (!InsertAssetAssign($asset_id, $assigned_by, $assigned_to, $assign_remark)) {
										  $IAM_ARRAY['success'] = false;
										  $IAM_ARRAY['message'] = "Error-34334";
									  }
												*/
		$assigned_to = 0;
		$atpsListQryIns = "INSERT INTO `z_assets_list` (
											`asset_ref`, 
											`asset_name`, 
											`asset_description`, 
											`asset_sku`, 
											`asset_serial`, 
											`category_id`, 
											`assigned_to`, 
											`department_id`, 
											`assigned_date`, 
											`added_date`, 
											`added_by`, 
											`assigned_by`, 
											`purchase_date`, 
											`expiry_date`, 
											`status_id` 
											) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssiiissiissi", $asset_ref, $asset_name, $asset_description, $asset_sku, $asset_serial, $category_id, $assigned_to, $department_id, $assigned_date, $added_date, $added_by, $assigned_by, $purchase_date, $expiry_date, $status_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$asset_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);


					//insert asset log
					$log_id = 0;
					$log_action = "Asset_Added";
					$log_remark = "---";
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

					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";


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
			reportError('z_assets_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


