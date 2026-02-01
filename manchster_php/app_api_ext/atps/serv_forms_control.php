<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if (
	$IS_LOGGED == true && $USER_ID != 0 &&
	isset($_POST['ops']) &&
	isset($_POST['atp_id']) &&
	isset($_POST['rc_comment']) &&
	isset($_POST['form'])
) {

	$atp_id = (int) test_inputs($_POST['atp_id']);
	$ops = (int) test_inputs($_POST['ops']);
	$form = "" . test_inputs($_POST['form']);
	$rc_comment = "" . test_inputs($_POST['rc_comment']);


	if ($atp_id != 0 && $form != '') {
		$resDA = 'serv_deny.php';
		if ($ops == 1) {
			//approve
			$resDA = 'serv_approve.php';
		} else if ($ops == 0) {
			//deny
			$resDA = 'serv_deny.php';
		} else if ($ops == 2) {
			//review
			$resDA = 'serv_review.php';
		}

		require_once($form . '/' . $resDA);


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

