<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;

	if (isset($_POST['statistic_type'])) {

		$statistic_type = encryptData("" . test_inputs($_POST['statistic_type']));
		$qualification_id =  isset($_POST['qualification_id']) ? encryptData( (int) test_inputs($_POST['qualification_id'])) : '';
		$y1_value = isset($_POST['y1_value']) ? encryptData((int) test_inputs($_POST['y1_value'])) : '';
		$y2_value = isset($_POST['y2_value']) ? encryptData((int) test_inputs($_POST['y2_value'])) : '';
		$y3_value = isset($_POST['y3_value']) ? encryptData((int) test_inputs($_POST['y3_value'])) : '';
		$y4_value = isset($_POST['y4_value']) ? encryptData((int) test_inputs($_POST['y4_value'])) : '';



		$atpsListContactsQryIns = "INSERT INTO `atps_learners_statistics` (
									`statistic_type`, 
									`qualification_id`, 
									`y1_value`, 
									`y2_value`, 
									`y3_value`, 
									`y4_value`, 
									`atp_id` 
								) VALUES ( ?, ?, ?, ?, ?, ?, ?);";

		if ($atpsListContactsStmtIns = mysqli_prepare($KONN, $atpsListContactsQryIns)) {
			if (mysqli_stmt_bind_param($atpsListContactsStmtIns, "siiiiii", $statistic_type, $qualification_id,  $y1_value, $y2_value, $y3_value, $y4_value, $atp_id)) {
				if (mysqli_stmt_execute($atpsListContactsStmtIns)) {

					if (InsertAtpLog("ATP added new Learners Statistic", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
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
			reportError('atps_learners_statistics failed to prepare stmt ', "atps_list", $USER_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-100";
		}







	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-3432";
	}










} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


