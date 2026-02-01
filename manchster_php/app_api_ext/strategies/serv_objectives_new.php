<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['objective_title']) &&
		isset($_POST['objective_description']) &&
		isset($_POST['objective_type_id']) &&
		isset($_POST['objective_weight']) &&
		isset($_POST['theme_id'])
	) {




		$objective_id = 0;
		$objective_title = "" . test_inputs($_POST['objective_title']);
		$objective_description = "" . test_inputs($_POST['objective_description']);
		$order_no = 0;
		$objective_type_id = (int) test_inputs($_POST['objective_type_id']);
		$objective_weight = (int) test_inputs($_POST['objective_weight']);
		$theme_id = (int) test_inputs($_POST['theme_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');


		$plan_id = 0;
		$theme_ref = '';
		$qu_m_strategic_plans_themes_sel = "SELECT `plan_id`, `theme_ref` FROM  `m_strategic_plans_themes` WHERE `theme_id` = $theme_id";
		$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
		$m_strategic_plans_themes_DATA;
		if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
			$m_strategic_plans_themes_DATA = mysqli_fetch_array($qu_m_strategic_plans_themes_EXE);
			$plan_id = (int) $m_strategic_plans_themes_DATA[0];
			$theme_ref = "" . $m_strategic_plans_themes_DATA[1];
		}



		//generate Order
		$objective_ref = '';
		$qu_m_strategic_plans_objectives_sel = "SELECT COUNT(`objective_id`) AS `totRecs` FROM  `m_strategic_plans_objectives` WHERE ( `theme_id` = $theme_id )";
		$qu_m_strategic_plans_objectives_EXE = mysqli_query($KONN, $qu_m_strategic_plans_objectives_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_objectives_EXE)) {
			$m_strategic_plans_objectives_DATA = mysqli_fetch_array($qu_m_strategic_plans_objectives_EXE);
			$totRecs = (int) $m_strategic_plans_objectives_DATA[0];
			$order_no = $totRecs + 1;
			$objective_ref = $theme_ref . '.' . $order_no;
		}




		$atpsListQryIns = "INSERT INTO `m_strategic_plans_objectives` (
											`objective_ref`, 
											`objective_title`, 
											`objective_description`, 
											`objective_type_id`, 
											`order_no`, 
											`objective_weight`, 
											`theme_id`, 
											`plan_id`, 
											`added_by`, 
											`added_date` 
											) VALUES (  ?, ?, ?, ?, ? , ? , ? , ? , ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssiiiiiis", $objective_ref, $objective_title, $objective_description, $objective_type_id, $order_no, $objective_weight, $theme_id, $plan_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$objective_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Strategic_Plan_Objective_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_strategic_plans', $theme_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
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
			reportError('m_strategic_plans_objectives failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


