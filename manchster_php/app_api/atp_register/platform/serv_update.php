<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;


	//check request
	if (isset($_POST['platform_id'])) {

		$platform_id = (int) test_inputs($_POST['platform_id']);
		$qqq = "";

		if( isset($_POST['platform_name']) ){
			$platform_name = "".test_inputs( "".$_POST['platform_name'] );
			$qqq = $qqq."`platform_name` = '".$platform_name."', ";
		}
		
		if( isset($_POST['platform_purpose']) ){
			$platform_purpose = "".test_inputs( "".$_POST['platform_purpose'] );
			$qqq = $qqq."`platform_purpose` = '".$platform_purpose."', ";
		}
		

		if ($qqq != "") {
			$qqq = substr($qqq, 0, -2);
			$qu_atps_electronic_systems_updt = "UPDATE  `atps_electronic_systems` SET " . $qqq . "  WHERE ((`atp_id` = $atp_id) AND (`platform_id` = $platform_id));";

			if (mysqli_query($KONN, $qu_atps_electronic_systems_updt)) {
				
				if (InsertAtpLog("ATP Updated Platform", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "Succeed";
				} else {
					reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-400";
				}





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


