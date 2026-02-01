<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;


	//check request
	if (isset($_POST['faculty_id'])) {

		$faculty_id = (int) test_inputs($_POST['faculty_id']);
		$qqq = "";

		if( isset($_POST['faculty_name']) ){
			$faculty_name = "".test_inputs( "".$_POST['faculty_name'] );
			$qqq = $qqq."`faculty_name` = '".$faculty_name."', ";
		}
		if( isset($_POST['faculty_type_id']) ){
			$faculty_type_id = (int) test_inputs( "".$_POST['faculty_type_id'] );
			$qqq = $qqq."`faculty_type_id` = '".$faculty_type_id."', ";
		}
		if( isset($_POST['educational_qualifications']) ){
			$educational_qualifications = "".test_inputs( "".$_POST['educational_qualifications'] );
			$qqq = $qqq."`educational_qualifications` = '".$educational_qualifications."', ";
		}
		if( isset($_POST['years_experience']) ){
			$years_experience = "".test_inputs( "".$_POST['years_experience'] );
			$qqq = $qqq."`years_experience` = '".$years_experience."', ";
		}
		if( isset($_POST['certificate_name']) ){
			$certificate_name = "".test_inputs( "".$_POST['certificate_name'] );
			$qqq = $qqq."`certificate_name` = '".$certificate_name."', ";
		}
		

		$qqq = "";

		if ($qqq != "") {
			$qqq = substr($qqq, 0, -2);
			$qu_atps_list_faculties_updt = "UPDATE  `atps_list_faculties` SET " . $qqq . "  WHERE ((`atp_id` = $atp_id) AND (`faculty_id` = $faculty_id));";

			if (mysqli_query($KONN, $qu_atps_list_faculties_updt)) {
				
				if (InsertAtpLog("ATP Updated faculty", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
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


