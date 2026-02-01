<?php
//sys_list_priorities

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['theme_id']) && isset($_POST['priority_name'])) {
	$qqq = "";
	$theme_id = (int) test_inputs($_POST['theme_id']);
	$priority_name = "" . test_inputs("" . $_POST['priority_name']);



	if (isset($_POST['priority_name'])) {
		$priority_name = test_inputs($_POST['priority_name']);
		$qqq = $qqq . "`priority_name` = '" . $priority_name . "', ";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_support_tickets_list_priority_updt = "UPDATE `sys_list_priorities` SET " . $qqq . "  WHERE `theme_id` = $theme_id;";


		if (mysqli_query($KONN, $qu_support_tickets_list_priority_updt)) {

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

