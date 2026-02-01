<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['q_id'])) {

	$q_id = (int) test_inputs($_POST['q_id']);
	$qqq = "";

	if (isset($_POST['q_text'])) {
		$q_text = test_inputs($_POST['q_text']);
		$qqq = $qqq . "`q_text` = '" . $q_text . "', ";
	}


	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_eqa_questions_updt = "UPDATE `eqa_questions` SET " . $qqq . "  WHERE `q_id` = $q_id;";


		if (mysqli_query($KONN, $qu_eqa_questions_updt)) {
			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "succeed";
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



