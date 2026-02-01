<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['new_status'])) {


	$new_status = (int) test_inputs($_POST['new_status']);

	$log_remark = 'Updated status to ' . $new_status;


	$qu_employees_list_updt = "UPDATE `employees_list` SET `emp_status_id` = $new_status WHERE `employee_id` = $USER_ID;";


	if (mysqli_query($KONN, $qu_employees_list_updt)) {

		$_SESSION['emp_status_id'] = $new_status;
		$EMP_STATUS_ID = $new_status;

		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['message'] = "Succeed";




	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-5675";
	}





} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

