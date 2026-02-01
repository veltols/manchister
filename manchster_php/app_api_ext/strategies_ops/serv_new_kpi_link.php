<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if(

		isset($_POST['project_id']) &&
		isset($_POST['plan_id']) &&
		isset($_POST['theme_id']) &&
		isset($_POST['objective_id']) &&
		isset($_POST['kpi_id']) 

	){


		


		$record_id = 0;
		
		$project_id = ( int ) test_inputs($_POST['project_id']);
		$plan_id = ( int ) test_inputs($_POST['plan_id']);
		$theme_id = ( int ) test_inputs($_POST['theme_id']);
		$objective_id = ( int ) test_inputs($_POST['objective_id']);
		$kpi_id = ( int ) test_inputs($_POST['kpi_id']);
		
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');
		
		//check if linked
		$totLinked = 0;
		$qu_m_operational_projects_kpis_sel = "SELECT COUNT(`linked_kpi_id`) FROM  `m_operational_projects_kpis` WHERE ( ( `project_id` = $project_id ) )";
		$qu_m_operational_projects_kpis_EXE = mysqli_query($KONN, $qu_m_operational_projects_kpis_sel);
		if(mysqli_num_rows($qu_m_operational_projects_kpis_EXE)){
			$m_operational_projects_kpis_DATA = mysqli_fetch_array($qu_m_operational_projects_kpis_EXE);
			$totLinked = ( int ) $m_operational_projects_kpis_DATA[0];
		}

		if( $totLinked != 0 ){
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "KPI already linked to this project";
		} else {
			$atpsListQryIns = "INSERT INTO `m_operational_projects_kpis` (
												`plan_id`, 
												`theme_id`, 
												`objective_id`, 
												`kpi_id`, 
												`project_id`, 
												`added_date`, 
												`added_by` 
												) VALUES (  ?, ?, ?, ?, ?, ?, ? );";

			if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
				if (mysqli_stmt_bind_param($atpsListStmtIns, "iiiiisi", $plan_id, $theme_id, $objective_id, $kpi_id, $project_id, $added_date, $added_by )) {
					if (mysqli_stmt_execute($atpsListStmtIns)) {
						//$objective_id = (int) mysqli_insert_id($KONN);
						mysqli_stmt_close(statement: $atpsListStmtIns);



						//insert ticket log
						$log_id = 0;
						$log_action = "KPI_linked_to_project";
						$log_remark = "---";
						$log_date = date('Y-m-d H:i:00');
						$logger_type = "employees_list";
						$log_dept = "IT";
						$logged_by = $USER_ID;


						if (insertSysLog('m_operational_projects', $plan_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
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


