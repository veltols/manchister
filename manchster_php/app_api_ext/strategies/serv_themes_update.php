<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;


if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['theme_ids']) && isset($_POST['order_nos'])) {

	$ps = $_POST['theme_ids'];
	$os = $_POST['order_nos'];
	$updatStt = '';

	for ($i = 0; $i < count($ps); $i++) {
		$theme_id = (int) test_inputs($ps[$i]);
		$order_no = (int) test_inputs($os[$i]);
		if ($theme_id != 0) {
			$updatStt .= "UPDATE `m_strategic_plans_themes` SET `order_no` = '" . $order_no . "' WHERE `theme_id` = $theme_id;";
		}
	}

	if ($updatStt != '') {
		if (mysqli_multi_query($KONN, $updatStt)) {

			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "Succeed";

		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-541533";
	}






} else if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['theme_id']) && isset($_POST['edit_priority'])) {
	$theme_id = (int) test_inputs($_POST['theme_id']);
	$qqq = "";
	if (isset($_POST['theme_title'])) {
		$theme_title = "" . test_inputs("" . $_POST['theme_title']);
		$qqq = $qqq . "`theme_title` = '" . $theme_title . "', ";
	}
	if (isset($_POST['theme_description'])) {
		$theme_description = "" . test_inputs("" . $_POST['theme_description']);
		$qqq = $qqq . "`theme_description` = '" . $theme_description . "', ";
	}
	if (isset($_POST['theme_weight'])) {
		$theme_weight = (int) test_inputs($_POST['theme_weight']);
		$qqq = $qqq . "`theme_weight` = '" . $theme_weight . "', ";
	}

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_m_strategic_plans_themes_updt = "UPDATE `m_strategic_plans_themes` SET " . $qqq . "  WHERE `theme_id` = $theme_id;";

		if (mysqli_query($KONN, $qu_m_strategic_plans_themes_updt)) {
			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "Succeed";
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-268889";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-121445";
	}



} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}




header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

