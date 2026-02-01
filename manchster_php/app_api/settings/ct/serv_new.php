<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['communication_type_name']) &&
		isset($_POST['approval_id_2']) &&
		isset($_POST['approval_id_1'])
	) {


		//todo
		//authorize inputs

		$communication_type_id = 0;
		$communication_type_name = "" . test_inputs($_POST['communication_type_name']);
		$communication_type_name_ar = $communication_type_name;
		$approval_id_1 = (int) test_inputs($_POST['approval_id_1']);
		$approval_id_2 = (int) test_inputs($_POST['approval_id_2']);





		$atpsListQryIns = "INSERT INTO `m_communications_list_types` (
											`communication_type_name`, 
											`communication_type_name_ar`, 
											`approval_id_1`, 
											`approval_id_2` 
											) VALUES ( ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssii", $communication_type_name, $communication_type_name_ar, $approval_id_1, $approval_id_2)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$communication_type_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);

					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";

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
			reportError('m_communications_list_types failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


