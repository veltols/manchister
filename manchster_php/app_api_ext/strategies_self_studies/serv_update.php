<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['study_id']) ) {

	$study_id = (int) test_inputs($_POST['study_id']);
	$qqq = "";


	if (isset($_POST['study_title'])) {
		$study_title = test_inputs($_POST['study_title']);
		$qqq = $qqq . "`study_title` = '" . $study_title . "', ";
	}
	if (isset($_POST['study_overview'])) {
		$study_overview = test_inputs($_POST['study_overview']);
		$qqq = $qqq . "`study_overview` = '" . $study_overview . "', ";
	}
	

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_m_strategic_studies_updt = "UPDATE  `m_strategic_studies` SET " . $qqq . "  WHERE `study_id` = $study_id;";



		if (mysqli_query($KONN, $qu_m_strategic_studies_updt)) {


					//insert ticket log
					$log_id = 0;
					$log_action = "Study_Updated";
					$log_remark = "---";
					$log_date = date('Y-m-d H:i:00');
					$logger_type = "employees_list";
					$log_dept = "IT";
					$logged_by = $USER_ID;


					if (insertSysLog('m_strategic_studies', $study_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						reportError('SP log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
					}

		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-11221";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-232344";
	}






} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));