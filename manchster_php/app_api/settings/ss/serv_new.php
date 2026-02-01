<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['category_name']) &&
		isset($_POST['destination_id'])
	) {


		//todo
		//authorize inputs

		$category_id = 0;
		$category_name = "" . test_inputs($_POST['category_name']);
		$category_name_ar = $category_name;
		$destination_id = (int) test_inputs($_POST['destination_id']);





		$atpsListQryIns = "INSERT INTO `ss_list_cats` (
											`category_name`, 
											`category_name_ar`, 
											`destination_id` 
											) VALUES ( ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssi", $category_name, $category_name_ar, $destination_id)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$category_id = (int) mysqli_insert_id($KONN);
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
			reportError('ss_list_cats failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
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


