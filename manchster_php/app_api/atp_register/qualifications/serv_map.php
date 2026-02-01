<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;


	//check request
	if (isset($_POST['qualification_id']) && isset($_POST['faculty_id'])  ) {

		$qualification_id = (int) test_inputs($_POST['qualification_id']);
		$faculty_id = (int) test_inputs($_POST['faculty_id']);
		
		//check if already mapped
		$qu_atps_list_qualifications_faculties_sel = "SELECT * FROM  `atps_list_qualifications_faculties` WHERE(( `qualification_id` = $qualification_id) AND ( `faculty_id` = $faculty_id) AND ( `atp_id` = $atp_id))";
		$qu_atps_list_qualifications_faculties_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_faculties_sel);
		if(mysqli_num_rows($qu_atps_list_qualifications_faculties_EXE) > 0 ){
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Faculty already mapped to this qualification";
		} else {
			//map faculty to qualification


			$atpsListContactsQryIns = "INSERT INTO `atps_list_qualifications_faculties` (
										`qualification_id`, 
										`faculty_id`, 
										`atp_id`
									) VALUES ( ?, ?, ? );";

			if ($atpsListContactsStmtIns = mysqli_prepare($KONN, $atpsListContactsQryIns)) {
				if (mysqli_stmt_bind_param($atpsListContactsStmtIns, "iii", $qualification_id, $faculty_id, $atp_id)) {
					if (mysqli_stmt_execute($atpsListContactsStmtIns)) {

						if (InsertAtpLog("ATP mapped qualification", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

							$IAM_ARRAY['success'] = true;
							$IAM_ARRAY['message'] = "Succeed";



						} else {
							reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "ERR-12-400";
						}

					} else {
						//Execute failed 
						reportError(mysqli_stmt_error($atpsListContactsStmtIns), "atps_list", $USER_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-300";
					}
				} else {
					//bind failed 
					reportError(mysqli_stmt_error($atpsListContactsStmtIns), "atps_list", $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-200";
				}
			} else {
				//prepare failed 
				reportError('atps_list_qualifications failed to prepare stmt ', "atps_list", $USER_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-100";
			}







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


