<?php
//m_communications_list_types

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['communication_type_id'])) {
	$qqq = "";
	$communication_type_id = (int) test_inputs($_POST['communication_type_id']);




	if (isset($_POST['communication_type_name'])) {
		$communication_type_name = test_inputs($_POST['communication_type_name']);
		$qqq = $qqq . "`communication_type_name` = '" . $communication_type_name . "', ";
	}

	if (isset($_POST['approval_id_1'])) {
		$approval_id_1 = (int) test_inputs($_POST['approval_id_1']);
		$qqq = $qqq . "`approval_id_1` = '" . $approval_id_1 . "', ";
	}

	if (isset($_POST['approval_id_2'])) {
		$approval_id_2 = (int) test_inputs($_POST['approval_id_2']);
		$qqq = $qqq . "`approval_id_2` = '" . $approval_id_2 . "', ";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_support_tickets_list_category_updt = "UPDATE `m_communications_list_types` SET " . $qqq . "  WHERE `communication_type_id` = $communication_type_id;";


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

