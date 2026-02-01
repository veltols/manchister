<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['local_map_id']) &&
		isset($_POST['employee_id'])
	) {



		$strategic_plan_id = 0;
		
		$local_map_id = (int) test_inputs($_POST['local_map_id']);
		$employee_id = (int) test_inputs($_POST['employee_id']);

		
		
		$department_id = 0;
		$strategic_plan_id = 0;
		$theme_id = 0;
		$objective_id = 0;
		$kpi_id = 0;
		$milestone_id = 0;
		$milestone_title = "";
		$milestone_description = "";
		$start_date = "";
		$end_date = "";
		$is_task_assigned = 0;
		$added_by = 0;
		$added_date = "";
	$qu_m_strategic_plans_internal_maps_sel = "SELECT `m_strategic_plans_internal_maps`.*, 
														`m_strategic_plans_milestones`.`milestone_title` AS `milestone_title`,
														`m_strategic_plans_milestones`.`milestone_description` AS `milestone_description`

												FROM  `m_strategic_plans_internal_maps` 
												INNER JOIN `m_strategic_plans_milestones` ON `m_strategic_plans_internal_maps`.`milestone_id` = `m_strategic_plans_milestones`.`milestone_id`
												WHERE `m_strategic_plans_internal_maps`.`local_map_id` = $local_map_id";
	$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
	if(mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE)){
		$m_strategic_plans_internal_maps_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_internal_maps_EXE);
		$department_id = ( int ) $m_strategic_plans_internal_maps_DATA['department_id'];
		$strategic_plan_id = ( int ) $m_strategic_plans_internal_maps_DATA['plan_id'];
		$theme_id = ( int ) $m_strategic_plans_internal_maps_DATA['theme_id'];
		$objective_id = ( int ) $m_strategic_plans_internal_maps_DATA['objective_id'];
		$kpi_id = ( int ) $m_strategic_plans_internal_maps_DATA['kpi_id'];
		$milestone_id = ( int ) $m_strategic_plans_internal_maps_DATA['milestone_id'];

		$milestone_title = $m_strategic_plans_internal_maps_DATA['milestone_title'];
		$milestone_description = $m_strategic_plans_internal_maps_DATA['milestone_description'];

		$start_date = $m_strategic_plans_internal_maps_DATA['start_date'];
		$end_date = $m_strategic_plans_internal_maps_DATA['end_date'];
		
		$task_id = 0;
		$status_id = 1;
		$taskThemeId = 2;
		$assigned_to = $employee_id;

		//insert task
		//strategic_plan_id
		//local_map_id
		$atpsListQryIns = "INSERT INTO `tasks_list` (
											`task_title`, 
											`task_description`, 
											`status_id`, 
											`theme_id`, 
											`task_assigned_date`, 
											`task_due_date`, 
											`assigned_to`, 
											`assigned_by`,
											`strategic_plan_id`,
											`local_map_id`
											) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );";
		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiissiiii", $milestone_title, $milestone_description, $status_id, $taskThemeId, $start_date, $end_date, $assigned_to, $USER_ID, $strategic_plan_id, $local_map_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$task_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);
					//insert task log
					$log_id = 0;
					$log_action = "Task_Added";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "HR";
					$logged_by = $USER_ID;
					
					if (insertSysLog('tasks_list', $task_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('Task log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}
					if ($assigned_to != 0) {
						notifyUser("New Task added", "tasks/", $assigned_to);
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
			reportError('tasks_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-55";
		}
		//-----------------------------------------------------------------------------

		//insert mapper
		$qu_m_strategic_plans_internal_maps_tasks_ins = "INSERT INTO `m_strategic_plans_internal_maps_tasks` (
							`local_map_id`, 
							`task_id`, 
							`task_progress`, 
							`employee_id` 
						) VALUES (
							'".$local_map_id."', 
							'".$task_id."', 
							'0', 
							'".$employee_id."' 
						);";

		if(!mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_tasks_ins)){
			die("Error-3986x");
		}

		//mark map as assigned
		$qu_m_strategic_plans_internal_maps_updt = "UPDATE  `m_strategic_plans_internal_maps` SET 
		`is_task_assigned` = 1 
		WHERE `local_map_id` = $local_map_id;";

		if(!mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_updt)){
			die("Error-3986y");
		}



		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['message'] = "Succeed";




	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-s156";
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


