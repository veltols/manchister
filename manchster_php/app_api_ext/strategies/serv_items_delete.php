<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['item_type']) && isset($_POST['item_id'])) {

	$item_id = (int) test_inputs($_POST['item_id']);
	$item_type = "" . test_inputs("" . $_POST['item_type']);

	include_once('remove/remove_' . $item_type . '.php');



} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));