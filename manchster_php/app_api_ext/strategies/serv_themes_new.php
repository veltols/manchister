<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['theme_title']) &&
		isset($_POST['theme_description']) &&
		isset($_POST['theme_weight']) &&
		isset($_POST['plan_id'])
	) {




		$theme_id = 0;
		$theme_title = "" . test_inputs($_POST['theme_title']);
		$theme_description = "" . test_inputs($_POST['theme_description']);
		$order_no = 0;
		$plan_id = (int) test_inputs($_POST['plan_id']);
		$theme_weight = (int) test_inputs($_POST['theme_weight']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');



		$theme_ref = '0';
		//generate Order
		$qu_m_strategic_plans_themes_sel = "SELECT COUNT(`theme_id`) AS `totRecs` FROM  `m_strategic_plans_themes` WHERE ( `plan_id` = $plan_id )";
		$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
			$m_strategic_plans_themes_DATA = mysqli_fetch_array($qu_m_strategic_plans_themes_EXE);
			$totRecs = (int) $m_strategic_plans_themes_DATA[0];
			$order_no = $totRecs + 1;
			$theme_ref = '' . $order_no;
		}




		$atpsListQryIns = "INSERT INTO `m_strategic_plans_themes` (
											`theme_ref`, 
											`theme_title`, 
											`theme_description`, 
											`order_no`, 
											`theme_weight`, 
											`plan_id`, 
											`added_by`, 
											`added_date` 
											) VALUES (  ?, ?, ?, ?, ? , ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssiiiis", $theme_ref, $theme_title, $theme_description, $order_no, $theme_weight, $plan_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$theme_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Strategic_Plan_Theme_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_strategic_plans', $plan_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('SPP log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
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
			reportError('m_strategic_plans_themes failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


