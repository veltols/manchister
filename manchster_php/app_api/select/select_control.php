<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if (
	$IS_LOGGED == true && $USER_ID != 0 &&
	isset($_POST['data_dest'])
) {


	$data_dest = "" . test_inputs($_POST['data_dest']);



	if ($data_dest != '') {

		require_once($data_dest . '/serv_list.php');

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

