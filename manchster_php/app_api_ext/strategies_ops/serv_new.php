<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['project_code']) &&
		isset($_POST['project_name']) &&
		isset($_POST['project_description']) &&
		isset($_POST['project_analysis']) &&
		isset($_POST['project_recommendations']) &&
		isset($_POST['project_period']) &&
		isset($_POST['project_start_date']) &&
		isset($_POST['project_end_date']) &&
		isset($_POST['plan_id'])
	) {



		$project_id = 0;
		$project_code = "" . test_inputs($_POST['project_code']);
		$project_name = "" . test_inputs($_POST['project_name']);

		$project_description = "" . test_inputs($_POST['project_description']);
		$project_analysis = "" . test_inputs($_POST['project_analysis']);
		$project_recommendations = "" . test_inputs($_POST['project_recommendations']);

		$project_period = "". test_inputs($_POST['project_period']);
		$project_start_date = "".test_inputs($_POST['project_start_date']);
		$project_end_date = "".test_inputs($_POST['project_end_date']);
		$plan_id = (int) test_inputs($_POST['plan_id']);
		$project_status_id = 1;
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');




		//generate REF
		$project_ref = "OP-";
		$qu_m_operational_projects_sel = "SELECT COUNT(`project_id`) AS `totRecs` FROM  `m_operational_projects`";
		$qu_m_operational_projects_EXE = mysqli_query($KONN, $qu_m_operational_projects_sel);
		if (mysqli_num_rows($qu_m_operational_projects_EXE)) {
			$m_operational_projects_DATA = mysqli_fetch_array($qu_m_operational_projects_EXE);
			$totRecs = (int) $m_operational_projects_DATA[0];
			$totRecs++;
			$totRecsView = $totRecs;
			if ($totRecs < 10) {
				$totRecsView = '00' . $totRecs;
			} else if ($totRecs >= 10 && $totRecs < 100) {
				$totRecsView = '0' . $totRecs;
			} else if ($totRecs >= 100) {
				$totRecsView = $totRecs;
			}

			$project_ref = $project_ref . $totRecsView;
		}

		$department_id = 0;
		$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $USER_ID";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE)) {
			$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
			$department_id = (int) $employees_list_DATA['department_id'];
		}









		$atpsListQryIns = "INSERT INTO `m_operational_projects` (
												`project_ref`, 
												`project_code`, 
												`project_name`, 
												`project_description`, 
												`project_analysis`, 
												`project_recommendations`, 
												`project_period`, 
												`project_start_date`, 
												`project_end_date`, 
												`plan_id`, 
												`department_id`, 
												`project_status_id`, 
												`added_by`, 
												`added_date` 
											) VALUES (  ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ? , ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssssssiiiis", $project_ref, $project_code, $project_name, $project_description, $project_analysis, $project_recommendations, $project_period, $project_start_date, $project_end_date, $plan_id, $department_id, $project_status_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$project_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Operational_Project_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_operational_projects', $project_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('SP log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
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
			reportError('m_operational_projects failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


