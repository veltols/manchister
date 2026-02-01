<?php
//z_assets_list_status

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['status_id']) && isset($_POST['status_name'])) {
	$qqq = "";
	$status_id = (int) test_inputs($_POST['status_id']);
	$status_name = "" . test_inputs("" . $_POST['status_name']);



	if (isset($_POST['status_name'])) {
		$status_name = test_inputs($_POST['status_name']);
		$qqq = $qqq . "`status_name` = '" . $status_name . "', ";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_z_assets_list_status_updt = "UPDATE `z_assets_list_status` SET " . $qqq . "  WHERE `status_id` = $status_id;";


		if (mysqli_query($KONN, $qu_z_assets_list_status_updt)) {

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

