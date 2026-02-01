<?php
//hr_employees_leave_types

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['leave_type_id']) && isset($_POST['leave_type_name'])) {
	$qqq = "";
	$leave_type_id = (int) test_inputs($_POST['leave_type_id']);
	$leave_type_name = "" . test_inputs("" . $_POST['leave_type_name']);



	if (isset($_POST['leave_type_name'])) {
		$leave_type_name = test_inputs($_POST['leave_type_name']);
		$qqq = $qqq . "`leave_type_name` = '" . $leave_type_name . "', ";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_hr_employees_leave_types_updt = "UPDATE `hr_employees_leave_types` SET " . $qqq . "  WHERE `leave_type_id` = $leave_type_id;";


		if (mysqli_query($KONN, $qu_hr_employees_leave_types_updt)) {

			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "Succeed";


		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-34345";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

