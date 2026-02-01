<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['a1']) &&
		isset($_POST['a2']) &&
		isset($_POST['a3']) &&
		isset($_POST['a4']) &&
		isset($_POST['a5']) &&
		isset($_POST['a6']) &&
		isset($_POST['a7']) &&
		isset($_POST['a8']) &&
		isset($_POST['a9']) &&
		isset($_POST['a10']) &&
		isset($_POST['a11']) &&
		isset($_POST['a12']) &&
		isset($_POST['a13']) &&
		isset($_POST['a14']) &&
		isset($_POST['a15']) &&
		isset($_POST['a16']) &&
		isset($_POST['a17'])
	) {

		$record_id = 0;
		$form_id = 0;
		$a1 = "" . test_inputs($_POST['a1']);
		$a2 = "" . test_inputs($_POST['a2']);
		$a3 = "" . test_inputs($_POST['a3']);
		$a4 = "" . test_inputs($_POST['a4']);
		$a5 = "" . test_inputs($_POST['a5']);
		$a6 = "" . test_inputs($_POST['a6']);
		$a7 = "" . test_inputs($_POST['a7']);
		$a8 = "" . test_inputs($_POST['a8']);
		$a9 = "" . test_inputs($_POST['a9']);
		$a10 = "" . test_inputs($_POST['a10']);
		$a11 = "" . test_inputs($_POST['a11']);
		$a12 = "" . test_inputs($_POST['a12']);
		$a13 = "" . test_inputs($_POST['a13']);
		$a14 = "" . test_inputs($_POST['a14']);
		$a15 = "" . test_inputs($_POST['a15']);
		$a16 = "" . test_inputs($_POST['a16']);
		$a17 = "" . test_inputs($_POST['a17']);

		$added_date = date('Y-m-d H:i:00');
		$qu_feedback_forms_ins = "INSERT INTO `feedback_forms` (
			`employee_id`, 
			`added_date` 
		) VALUES (
			'" . $USER_ID . "', 
			'" . $added_date . "' 
		);";

		if (mysqli_query($KONN, $qu_feedback_forms_ins)) {
			$form_id = (int) mysqli_insert_id($KONN);


			$atpsListQryIns = "INSERT INTO `feedback_forms_answers` (
				`form_id`, 
				`a1`, 
				`a2`, 
				`a3`, 
				`a4`, 
				`a5`, 
				`a6`, 
				`a7`, 
				`a8`, 
				`a9`, 
				`a10`, 
				`a11`, 
				`a12`, 
				`a13`, 
				`a14`, 
				`a15`, 
				`a16`, 
				`a17` 
		) VALUES ( ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?, ?, ? );";

			if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
				if (mysqli_stmt_bind_param($atpsListStmtIns, "isssssssssssssssss", $form_id, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10, $a11, $a12, $a13, $a14, $a15, $a16, $a17)) {
					if (mysqli_stmt_execute($atpsListStmtIns)) {

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
				reportError('feedback_forms_answers failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-55";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-53775";
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


