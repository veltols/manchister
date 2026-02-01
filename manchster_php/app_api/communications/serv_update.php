<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['communication_id']) && isset($_POST['communication_status_id']) && isset($_POST['log_remarks'])) {

	$communication_id = (int) test_inputs($_POST['communication_id']);
	$communication_status_id = (int) test_inputs($_POST['communication_status_id']);
	$log_remarks = "" . test_inputs("" . $_POST['log_remarks']);



	$typeApp_id_1 = 0;
	$typeApp_id_2 = 0;
	$requested_by = 0;

	$qu_m_communications_list_sel = "SELECT `communication_type_id`, `requested_by` FROM  `m_communications_list` WHERE `communication_id` = $communication_id";
	$qu_m_communications_list_EXE = mysqli_query($KONN, $qu_m_communications_list_sel);
	if (mysqli_num_rows($qu_m_communications_list_EXE)) {
		$m_communications_list_DATA = mysqli_fetch_array($qu_m_communications_list_EXE);
		$communication_type_id = (int) $m_communications_list_DATA[0];
		$requested_by = (int) $m_communications_list_DATA[0];

		$qu_m_communications_list_types_sel = "SELECT `approval_id_1`, `approval_id_2` FROM  `m_communications_list_types` WHERE `communication_type_id` = $communication_type_id";
		$qu_m_communications_list_types_EXE = mysqli_query($KONN, $qu_m_communications_list_types_sel);
		if (mysqli_num_rows($qu_m_communications_list_types_EXE)) {
			$m_communications_list_types_DATA = mysqli_fetch_assoc($qu_m_communications_list_types_EXE);
			$typeApp_id_1 = (int) $m_communications_list_types_DATA['approval_id_1'];
			$typeApp_id_2 = (int) $m_communications_list_types_DATA['approval_id_2'];
		}

	}











	$log_date = date('Y-m-d H:i:00');

	$qqq = "";

	$log_action = "Request_Status_changed";
	$qqq = $qqq . "`communication_status_id` = '" . $communication_status_id . "', ";


	if ($communication_status_id == 2) {
		//approved by 1 and pending 2
		$qqq = $qqq . "`is_approved_1` = '1', ";
		$qqq = $qqq . "`approved_1_date` = '" . $log_date . "', ";
		$qqq = $qqq . "`approval_id_1` = '" . $typeApp_id_1 . "', ";
		$qqq = $qqq . "`approved_1_notes` = '" . $log_remarks . "', ";

		//check approval 1 ID
		if ($typeApp_id_1 != $USER_ID) {
			$qqq = "";
		}


	} else if ($communication_status_id == 3) {
		//approved by 2
		$qqq = $qqq . "`is_approved_2` = '1', ";
		$qqq = $qqq . "`approved_2_date` = '" . $log_date . "', ";
		$qqq = $qqq . "`approval_id_2` = '" . $typeApp_id_2 . "', ";
		$qqq = $qqq . "`approved_2_notes` = '" . $log_remarks . "', ";

		//check approval 2 ID
		if ($typeApp_id_2 != $USER_ID) {
			$qqq = "";
		}



	} else {
		$qqq = "";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_m_communications_list_updt = "UPDATE `m_communications_list` SET " . $qqq . "  WHERE `communication_id` = $communication_id;";


		if (mysqli_query($KONN, $qu_m_communications_list_updt)) {

			//insert Ticket log
			$log_id = 0;
			$log_remark = $log_remarks;
			$logger_type = "employees_list";
			$log_dept = "EMP";
			$logged_by = $USER_ID;


			if (insertSysLog('m_communications_list', $communication_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Ticket log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}



			notifyUser("Your Communication Request has been updated", "communications/list/", $requested_by);
			if ($communication_status_id == 2) {
				notifyUser("You have 1 Communication Request pending your approval", "communications/list/", $typeApp_id_2);
			}





		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "You don't have permission!";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

