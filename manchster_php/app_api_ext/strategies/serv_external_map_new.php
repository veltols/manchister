<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if(
		isset($_POST['objective_id']) && 
		isset($_POST['external_entity_name']) &&
		isset($_POST['map_description']) &&
		isset($_POST['start_date']) &&
		isset($_POST['end_date']) 
	){


		 


		$record_id = 0;
		
		$objective_id = ( int ) test_inputs($_POST['objective_id']);
		
		$external_entity_name = "".test_inputs($_POST['external_entity_name']);
		$map_description = "".test_inputs($_POST['map_description']);
		$start_date = "".test_inputs($_POST['start_date'])." 08:00:00";
		$end_date = "".test_inputs($_POST['end_date'])." 16:00:00";
		
		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');
		

		$plan_id = 0;
		$theme_id = 0;
		$qu_chkSel_sel = "SELECT `plan_id`, `theme_id` FROM  `m_strategic_plans_objectives` WHERE `objective_id` = $objective_id";
		$qu_chkSel_EXE = mysqli_query($KONN, $qu_chkSel_sel);
		if(mysqli_num_rows($qu_chkSel_EXE)){
			$chkSel_DATA = mysqli_fetch_assoc($qu_chkSel_EXE);
			$plan_id = ( int ) $chkSel_DATA['plan_id'];
			$theme_id = ( int ) $chkSel_DATA['theme_id'];
		}
		
		if( $plan_id == 0 ){
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-125-000";
		} else {
			$atpsListQryIns = "INSERT INTO `m_strategic_plans_external_maps` (
												`plan_id`, 
												`theme_id`, 
												`objective_id`, 
												`external_entity_name`, 
												`map_description`, 
												`start_date`, 
												`end_date`, 
												`added_by`, 
												`added_date` 
												) VALUES (  ?, ?, ?, ?, ?, ?, ?, ?, ? );";

			if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
				if (mysqli_stmt_bind_param($atpsListStmtIns, "iiissssis", $plan_id, $theme_id, $objective_id, $external_entity_name, $map_description, $start_date, $end_date, $added_by, $added_date)) {
					if (mysqli_stmt_execute($atpsListStmtIns)) {
						$objective_id = (int) mysqli_insert_id($KONN);
						mysqli_stmt_close(statement: $atpsListStmtIns);



						//insert ticket log
						$log_id = 0;
						$log_action = "Strategic_Plan_External_map_Added";
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


