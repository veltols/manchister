<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['milestone_title']) &&
		isset($_POST['milestone_description']) &&
		isset($_POST['milestone_weight']) &&
		isset($_POST['kpi_id'])
	) {


		$milestone_id = 0;
		$milestone_title = "" . test_inputs($_POST['milestone_title']);
		$milestone_description = "" . test_inputs($_POST['milestone_description']);
		$kpi_progress = 0;
		$milestone_weight = (int) test_inputs($_POST['milestone_weight']);
		$order_no = 0;
		$kpi_id = (int) test_inputs($_POST['kpi_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');


		$objective_id = 0;
		$plan_id = 0;
		$theme_id = 0;
		$kpi_ref = '';
		$qu_m_strategic_plans_kpis_sel = "SELECT `plan_id`, `theme_id`, `objective_id`, `kpi_ref` FROM  `m_strategic_plans_kpis` WHERE `kpi_id` = $kpi_id";
		$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)) {
			$m_strategic_plans_kpis_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_kpis_EXE);
			$plan_id = (int) $m_strategic_plans_kpis_DATA['plan_id'];
			$theme_id = (int) $m_strategic_plans_kpis_DATA['theme_id'];
			$objective_id = (int) $m_strategic_plans_kpis_DATA['objective_id'];
			$kpi_ref = "" . $m_strategic_plans_kpis_DATA['kpi_ref'];
		}


		//generate Order
		//SSm
		$milestone_ref = '';
		$qu_SSm_sel = "SELECT COUNT(`milestone_id`) AS `totRecs` FROM  `m_strategic_plans_milestones` WHERE ( `kpi_id` = $kpi_id )";
		$qu_SSm_EXE = mysqli_query($KONN, $qu_SSm_sel);
		if (mysqli_num_rows($qu_SSm_EXE)) {
			$SSm_DATA = mysqli_fetch_array($qu_SSm_EXE);
			$totRecs = (int) $SSm_DATA[0];
			$order_no = $totRecs + 1;
			$milestone_ref = $kpi_ref . '.' . $order_no;
		}

		//SSm


		$atpsListQryIns = "INSERT INTO `m_strategic_plans_milestones` (
											`milestone_ref`, 
											`milestone_title`, 
											`milestone_description`, 
											`order_no`, 
											`milestone_weight`, 
											`kpi_id`, 
											`objective_id`, 
											`theme_id`, 
											`plan_id`, 
											`added_by`, 
											`added_date` 
											) VALUES (  ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssiiiiiiis", $milestone_ref, $milestone_title, $milestone_description, $order_no, $milestone_weight, $kpi_id, $objective_id, $theme_id, $plan_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$milestone_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Strategic_Plan_KPI_Milestone_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_strategic_plans', $objective_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
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
			reportError('m_strategic_plans_milestones failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


