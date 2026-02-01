<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['plan_title']) &&
		isset($_POST['plan_vision']) &&
		isset($_POST['plan_mission']) &&
		isset($_POST['plan_values']) &&
		isset($_POST['plan_period']) &&
		isset($_POST['plan_from']) &&
		isset($_POST['plan_to']) &&
		isset($_POST['plan_level'])
	) {



		$plan_id = 0;
		$plan_title = "" . test_inputs($_POST['plan_title']);

		$plan_vision = "" . test_inputs($_POST['plan_vision']);
		$plan_mission = "" . test_inputs($_POST['plan_mission']);
		$plan_values = "" . test_inputs($_POST['plan_values']);

		$plan_period = "". test_inputs($_POST['plan_period']);
		$plan_from = "".test_inputs($_POST['plan_from']);
		$plan_to = "".test_inputs($_POST['plan_to']);
		
		$plan_level = (int) test_inputs($_POST['plan_level']);
		$plan_status_id = 1;
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');




		//generate REF
		$plan_ref = "SP-";
		$qu_m_strategic_plans_sel = "SELECT COUNT(`plan_id`) AS `totRecs` FROM  `m_strategic_plans`";
		$qu_m_strategic_plans_EXE = mysqli_query($KONN, $qu_m_strategic_plans_sel);
		if (mysqli_num_rows($qu_m_strategic_plans_EXE)) {
			$m_strategic_plans_DATA = mysqli_fetch_array($qu_m_strategic_plans_EXE);
			$totRecs = (int) $m_strategic_plans_DATA[0];
			$totRecs++;
			$totRecsView = $totRecs;
			if ($totRecs < 10) {
				$totRecsView = '00' . $totRecs;
			} else if ($totRecs >= 10 && $totRecs < 100) {
				$totRecsView = '0' . $totRecs;
			} else if ($totRecs >= 100) {
				$totRecsView = $totRecs;
			}

			$plan_ref = $plan_ref . $totRecsView;
		}



		$atpsListQryIns = "INSERT INTO `m_strategic_plans` (
												`plan_ref`, 
												`plan_title`, 
												`plan_vision`, 
												`plan_mission`, 
												`plan_values`, 
												`plan_period`, 
												`plan_from`, 
												`plan_to`, 
												`plan_level`, 
												`plan_status_id`, 
												`added_by`, 
												`added_date` 
											) VALUES (  ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssssssiiiiis", $plan_ref, $plan_title, $plan_vision, $plan_mission, $plan_values, $plan_period, $plan_from, $plan_to, $plan_level, $plan_status_id, $added_by, $added_date)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$plan_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Strategic_Plan_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_strategic_plans', $plan_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
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
			reportError('m_strategic_plans failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


