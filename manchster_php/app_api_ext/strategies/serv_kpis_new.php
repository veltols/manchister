<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['kpi_code']) &&
		isset($_POST['data_source']) &&
		isset($_POST['kpi_title']) &&
		isset($_POST['kpi_description']) && 
		isset($_POST['kpi_frequncy_id']) && 
		isset($_POST['kpi_formula']) && 
		isset($_POST['department_id']) &&
		//isset($_POST['kpi_progress']) &&
		isset($_POST['kpi_weight']) &&
		isset($_POST['objective_id'])
	) {




		$kpi_id = 0;
		$kpi_code = "" . test_inputs($_POST['kpi_code']);
		$data_source = "" . test_inputs($_POST['data_source']);
		$kpi_title = "" . test_inputs($_POST['kpi_title']);
		$kpi_description = "" . test_inputs($_POST['kpi_description']);
		$kpi_frequncy_id = (int) test_inputs($_POST['kpi_frequncy_id']);
		$kpi_formula = "" . test_inputs($_POST['kpi_formula']);
		//$kpi_progress = (int) test_inputs($_POST['kpi_progress']);
		$kpi_progress = 0;
		$department_id = (int) test_inputs($_POST['department_id']);

		$kpi_weight = (int) test_inputs($_POST['kpi_weight']);
		$order_no = 0;
		$objective_id = (int) test_inputs($_POST['objective_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');


		$plan_id = 0;
		$theme_id = 0;
		$objective_ref = '';
		$qu_m_strategic_plans_objectives_sel = "SELECT `plan_id`, `theme_id`, `objective_ref` FROM  `m_strategic_plans_objectives` WHERE `objective_id` = $objective_id";
		$qu_m_strategic_plans_objectives_EXE = mysqli_query($KONN, $qu_m_strategic_plans_objectives_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_objectives_EXE)) {
			$m_strategic_plans_objectives_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_objectives_EXE);
			$plan_id = (int) $m_strategic_plans_objectives_DATA['plan_id'];
			$theme_id = (int) $m_strategic_plans_objectives_DATA['theme_id'];
			$objective_ref = "" . $m_strategic_plans_objectives_DATA['objective_ref'];
		}


		//generate Order
		$kpi_ref = '';
		$qu_m_strategic_plans_kpis_sel = "SELECT COUNT(`kpi_id`) AS `totRecs` FROM  `m_strategic_plans_kpis` WHERE ( `objective_id` = $objective_id )";
		$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)) {
			$m_strategic_plans_kpis_DATA = mysqli_fetch_array($qu_m_strategic_plans_kpis_EXE);
			$totRecs = (int) $m_strategic_plans_kpis_DATA[0];
			$order_no = $totRecs + 1;
			$kpi_ref = $objective_ref . '.' . $order_no;
		}




		$atpsListQryIns = "INSERT INTO `m_strategic_plans_kpis` (
											`kpi_ref`, 
											`kpi_code`, 
											`data_source`, 
											`kpi_title`, 
											`kpi_description`, 
											`kpi_frequncy_id`, 
											`kpi_formula`, 
											`department_id`, 
											`kpi_progress`, 
											`order_no`, 
											`kpi_weight`, 
											`objective_id`, 
											`theme_id`, 
											`plan_id`, 
											`added_by`, 
											`added_date` 
											) VALUES (  ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssisiiiiiiiis", $kpi_ref, $kpi_code, $data_source, $kpi_title, $kpi_description, $kpi_frequncy_id, $kpi_formula, $department_id, $kpi_progress, $order_no, $kpi_weight, $objective_id, $theme_id, $plan_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$kpi_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Strategic_Plan_Objective_KPI_Added";
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
			reportError('m_strategic_plans_kpis failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


