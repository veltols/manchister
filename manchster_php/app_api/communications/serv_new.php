<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['external_party_name']) &&
		isset($_POST['communication_subject']) &&
		isset($_POST['communication_description']) &&
		isset($_POST['information_shared']) &&
		isset($_POST['communication_type_id'])
	) {


		//todo
		//authorize inputs

		$communication_id = 0;
		$external_party_name = "" . test_inputs($_POST['external_party_name']);
		$communication_subject = "" . test_inputs($_POST['communication_subject']);
		$communication_description = "" . test_inputs($_POST['communication_description']);
		$information_shared = "" . test_inputs($_POST['information_shared']);
		$communication_type_id = (int) test_inputs($_POST['communication_type_id']);
		$communication_status_id = 1;
		$requested_date = date('Y-m-d H:i:00');
		$requested_by = $USER_ID;


		$department_id = 0;
		$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $USER_ID";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE)) {
			$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
			$department_id = (int) $employees_list_DATA['department_id'];
		}

		//generate REF
		$communication_code = "C-";
		$qu_m_communications_list_sel = "SELECT COUNT(`communication_id`) AS `totRecs` FROM  `m_communications_list`";
		$qu_m_communications_list_EXE = mysqli_query($KONN, $qu_m_communications_list_sel);
		if (mysqli_num_rows($qu_m_communications_list_EXE)) {
			$m_communications_list_DATA = mysqli_fetch_array($qu_m_communications_list_EXE);
			$totRecs = (int) $m_communications_list_DATA[0];
			$totRecs++;
			$totRecsView = $totRecs;
			if ($totRecs < 10) {
				$totRecsView = '00' . $totRecs;
			} else if ($totRecs >= 10 && $totRecs < 100) {
				$totRecsView = '0' . $totRecs;
			} else if ($totRecs >= 100) {
				$totRecsView = $totRecs;
			}

			$communication_code = $communication_code . $totRecsView;
		}


		$atpsListQryIns = "INSERT INTO `m_communications_list` (
											`communication_code`, 
											`external_party_name`, 
											`communication_subject`, 
											`communication_description`, 
											`information_shared`, 
											`communication_type_id`, 
											`communication_status_id`, 
											`requested_date`, 
											`requested_by` 
											) VALUES (  ?, ?, ?, ?, ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "sssssiisi", $communication_code, $external_party_name, $communication_subject, $communication_description, $information_shared, $communication_type_id, $communication_status_id, $requested_date, $requested_by)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$communication_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);



					//insert ticket log
					$log_id = 0;
					$log_action = "Communication_Request_Added";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_communications_list', $communication_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('SS log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}

					//notify first approval
					$approval_id_1 = 0;
					$qu_m_communications_list_types_sel = "SELECT `approval_id_1` FROM  `m_communications_list_types` WHERE `communication_type_id` = $communication_type_id";
					$qu_m_communications_list_types_EXE = mysqli_query($KONN, $qu_m_communications_list_types_sel);
					if (mysqli_num_rows($qu_m_communications_list_types_EXE)) {
						$m_communications_list_types_DATA = mysqli_fetch_assoc($qu_m_communications_list_types_EXE);
						$approval_id_1 = (int) $m_communications_list_types_DATA['approval_id_1'];
						if ($approval_id_1 != 0) {
							//notify user
							notifyUser("A new Communication Request has been added, REF: " . $communication_code, 'communications/list/', $approval_id_1);
						}
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
			reportError('m_communications_list failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


