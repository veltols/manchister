<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;


	//check request
	if (isset($_POST['qualification_id'])) {

		$qualification_id = (int) test_inputs($_POST['qualification_id']);
		$qqq = "";

		if( isset($_POST['qualification_type']) ){
			$qualification_type = test_inputs( $_POST['qualification_type'] );
			$qqq = $qqq."`qualification_type` = '".$qualification_type."', ";
		}
		if( isset($_POST['qualification_name']) ){
			$qualification_name = test_inputs( $_POST['qualification_name'] );
			$qqq = $qqq."`qualification_name` = '".$qualification_name."', ";
		}
		if( isset($_POST['qualification_category']) ){
			$qualification_category = test_inputs( $_POST['qualification_category'] );
			$qqq = $qqq."`qualification_category` = '".$qualification_category."', ";
		}
		if( isset($_POST['emirates_level']) ){
			$emirates_level = test_inputs( $_POST['emirates_level'] );
			$qqq = $qqq."`emirates_level` = '".$emirates_level."', ";
		}
		if( isset($_POST['qulaification_credits']) ){
			$qulaification_credits = test_inputs( $_POST['qulaification_credits'] );
			$qqq = $qqq."`qulaification_credits` = '".$qulaification_credits."', ";
		}
		if( isset($_POST['mode_of_delivery']) ){
			$mode_of_delivery = test_inputs( $_POST['mode_of_delivery'] );
			$qqq = $qqq."`mode_of_delivery` = '".$mode_of_delivery."', ";
		}
		


		if ($qqq != "") {
			$qqq = substr($qqq, 0, -2);
			$qu_atps_list_qualifications_updt = "UPDATE  `atps_list_qualifications` SET " . $qqq . "  WHERE ((`atp_id` = $atp_id) AND (`qualification_id` = $qualification_id));";

			if (mysqli_query($KONN, $qu_atps_list_qualifications_updt)) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "All Good";
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-650";
			}
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-550";
		}








	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-200";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


