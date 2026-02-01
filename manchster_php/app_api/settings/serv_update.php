<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['user_theme_id']) && isset($_POST['user_lang'])) {

	$user_theme_id = (int) test_inputs($_POST['user_theme_id']);
	$user_lang = "" . test_inputs($_POST['user_lang']);
	$qqq = "";

	$qqq = $qqq . "`user_theme_id` = '" . $user_theme_id . "', ";
	$qqq = $qqq . "`user_lang` = '" . $user_lang . "', ";
	

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_users_list_updt = "UPDATE `users_list` SET " . $qqq . "  WHERE `record_id` = $USER_RECORD_ID;";


		if (mysqli_query($KONN, $qu_users_list_updt)) {
			

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

