<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['answer_texts']) &&
		isset($_POST['question_ids']) &&
		isset($_POST['interview_remarks']) &&
		isset($_POST['employee_id'])
	) {



		//todo
		//authorize inputs

		$interview_id = 0;
		$interview_date = date('Y-m-d');

		$question_ids = $_POST['question_ids'];
		$answer_texts = $_POST['answer_texts'];

		$interview_remarks = "" . test_inputs($_POST['interview_remarks']);
		$employee_id = (int) test_inputs($_POST['employee_id']);
		$current_department_id = 0;

		$added_by = $USER_ID;
		//get department
		$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $employee_id";
		$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
		if (mysqli_num_rows($qu_employees_list_EXE)) {
			$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
			$current_department_id = (int) $employees_list_DATA['department_id'];
		}



		$atpsListQryIns = "INSERT INTO `hr_exit_interviews` (
												`interview_date`, 
												`interview_remarks`, 
												`employee_id`, 
												`current_department_id`, 
												`added_by` 
											) VALUES ( ?, ?, ?, ?, ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "ssiii", $interview_date, $interview_remarks, $employee_id, $current_department_id, $added_by)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$interview_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);




					//insert answers
					for ($m = 0; $m < count($question_ids); $m++) {
						$question_id = (int) test_inputs($question_ids[$m]);
						$answer_text = '' . test_inputs($answer_texts[$m]);

						$qu_hr_exit_interviews_answers_ins = "INSERT INTO `hr_exit_interviews_answers` (
							`answer_text`, 
							`question_id`, 
							`interview_id`, 
							`employee_id` 
						) VALUES (
							'" . $answer_text . "', 
							'" . $question_id . "', 
							'" . $interview_id . "', 
							'" . $employee_id . "' 
						);";

						if (!mysqli_query($KONN, $qu_hr_exit_interviews_answers_ins)) {
							die("Error - jkldhs90-8");
						}

					}




					$log_action = 'Interview_Added';
					$log_remark = $interview_remarks;
					$logger_type = 'employees_list';
					$logged_by = $USER_ID;
					if (InsertSysLog('hr_exit_interviews', $interview_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "log Failed-548";
					}




				} else {
					//Execute failed 
					reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-57";
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-12-56";
			}
		} else {
			//prepare failed 
			reportError('hr_exit_interviews failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-55";
		}



	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


