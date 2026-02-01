<?php
//z_assets_list_cats

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['category_id'])) {
	$qqq = "";
	$category_id = (int) test_inputs($_POST['category_id']);




	if (isset($_POST['category_name'])) {
		$category_name = test_inputs($_POST['category_name']);
		$qqq = $qqq . "`category_name` = '" . $category_name . "', ";
	}

	if (isset($_POST['category_alert_days'])) {
		$category_alert_days = (int) test_inputs($_POST['category_alert_days']);
		$qqq = $qqq . "`category_alert_days` = '" . $category_alert_days . "', ";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_support_tickets_list_category_updt = "UPDATE `z_assets_list_cats` SET " . $qqq . "  WHERE `category_id` = $category_id;";


		if (mysqli_query($KONN, $qu_support_tickets_list_category_updt)) {

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

