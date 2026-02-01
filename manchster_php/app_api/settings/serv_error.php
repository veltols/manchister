<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['error_text'])) {

	
	$error_text = "" . test_inputs($_POST['error_text']);
	
	$added_date = date('Y-m-d H:i:00');




	$usersListQryIns = "INSERT INTO `sys_errors` (
			`error_text`, 
			`added_date`, 
			`adder_type`, 
			`added_by` 
		) VALUES ( ?, ?, 'employees_list', ? );";

	if ($usersListStmtIns = mysqli_prepare($KONN, $usersListQryIns)) {
		if (mysqli_stmt_bind_param($usersListStmtIns, "ssi", $error_text, $added_date, $USER_ID)) {
			if (mysqli_stmt_execute($usersListStmtIns)) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-33";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-22";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-11";
	}


	
	

} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));














