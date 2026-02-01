<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['study_id']) && isset($_POST['page_id']) ) {

	$study_id = (int) test_inputs($_POST['study_id']);
	$page_id  = (int) test_inputs($_POST['page_id']);
	$qqq = "";


	if (isset($_POST['page_title'])) {
		$page_title = test_inputs($_POST['page_title']);
		$qqq = $qqq . "`page_title` = '" . $page_title . "', ";
	}
	if (isset($_POST['page_content'])) {
		$page_content = test_inputs($_POST['page_content']);
		$qqq = $qqq . "`page_content` = '" . $page_content . "', ";
	}
	

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);
		$qu_m_strategic_studies_pages_updt = "UPDATE  `m_strategic_studies_pages` SET " . $qqq . " 
												 WHERE ((`study_id` = $study_id) AND (`page_id` = $page_id));";



		if (mysqli_query($KONN, $qu_m_strategic_studies_pages_updt)) {


					//insert ticket log
					$log_id = 0;
					$log_action = "Study_Page_Updated";
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