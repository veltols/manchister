<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['employee_id']) && isset($_POST['service_id']) && isset($_POST['new_val']) ) {


	$employee_id = (int) test_inputs($_POST['employee_id']);
	$service_id  = (int) test_inputs($_POST['service_id']);
	$new_val  = (int) test_inputs($_POST['new_val']);

	//insert employee log
	$log_id = 0;
	$log_date = date('Y-m-d H:i:00');
	$logger_type = "employees_list";
	$log_dept = "IT";
	$logged_by = $USER_ID;
	

	if( $service_id <= 10004 ){

		$qq = '';
		if( $new_val == 0 ){

			//delete current permission
			$qu_employees_services_del = "DELETE FROM `employees_services` WHERE ((`employee_id` = $employee_id) AND (`service_id` = $service_id))";
			if(mysqli_query($KONN, $qu_employees_services_del)){
				
				$log_action = "Service_Removed-".$service_id;
				if (insertSysLog('employees_list', $employee_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";
				} else {
					reportError('employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "ALl good";
				}
				

			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Error-54545678";
			}
		

		} else if( $new_val == 1 ){
			//add service if not added
			$qu_employees_services_sel = "SELECT * FROM  `employees_services` WHERE ((`employee_id` = $employee_id) AND (`service_id` = $service_id))";
			$qu_employees_services_EXE = mysqli_query($KONN, $qu_employees_services_sel);
			if(mysqli_num_rows($qu_employees_services_EXE) == 0 ){
				
					$qu_employees_services_ins = "INSERT INTO `employees_services` (
										`employee_id`, 
										`service_id`, 
										`added_by`, 
										`added_date` 
									) VALUES (
										'".$employee_id."', 
										'".$service_id."', 
										'".$USER_ID."', 
										'".$log_date."' 
									);";

					if(mysqli_query($KONN, $qu_employees_services_ins)){
						
						$log_action = "Service_Added-".$service_id;
						if (insertSysLog('employees_list', $employee_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";
						} else {
							reportError('employee log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "ALl good";
						}
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error-12348";
					}

			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Error-Service already added";
			}


		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "No-Password";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Service files not loaded";
	}






} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

