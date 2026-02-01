<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['ticket_id']) && isset($_POST['op']) && isset($_POST['ticket_remarks'])) {

	$ticket_id = (int) test_inputs($_POST['ticket_id']);
	$ticket_remarks = "" . test_inputs("" . $_POST['ticket_remarks']);
	$op = (int) test_inputs($_POST['op']);

	$qqq = "";

	if ($ticket_remarks != "") {
		$qqq = $qqq . "`ticket_remarks` = '" . $ticket_remarks . "', ";
	}
	$log_action = "Ticket_Status_changed";


	if ($op == 200) {
		//In Progress
		$assigned_to = isset($_POST['assigned_to']) ? (int) test_inputs($_POST['assigned_to']) : 0;
		if ($assigned_to != 0) {
			$log_action = "Ticket_Assigned";
			$qqq = $qqq . "`assigned_to` = '" . $assigned_to . "', ";
		} else {
			$qqq = '';
		}
	} else if ($op == 100) {
		//Reopen ticket
		$qqq = $qqq . "`status_id` = '1', ";
		$log_action = "Ticket_Reopened";
	} else if ($op == 2) {
		//In Progress
		$qqq = $qqq . "`status_id` = '2', ";
		$log_action = "Ticket_in_Progress";
	} else if ($op == 3) {
		//resolved
		$qqq = $qqq . "`status_id` = '3', ";
		$log_action = "Ticket_Resolved";
	} else {
		$qqq = "";
	}



	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_support_tickets_list_updt = "UPDATE `support_tickets_list` SET " . $qqq . "  WHERE `ticket_id` = $ticket_id;";


		if (mysqli_query($KONN, $qu_support_tickets_list_updt)) {

			//insert Ticket log
			$log_id = 0;
			$log_remark = $ticket_remarks;
			$log_date = date('Y-m-d H:i:00');
			$logger_type = "employees_list";
			$log_dept = "IT";
			$logged_by = $USER_ID;


			if (insertSysLog('support_tickets_list', $ticket_id, $log_action, $log_remark, $logger_type, $logged_by, 'int')) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				reportError('Ticket log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
			}

			if ($op == 200) {
				if ($assigned_to != 0) {
					//notify
					notifyUser("A new ticket assigned to you", "tickets/list/", $assigned_to);
				}
			}



			$added_by = 0;
			$qu_support_tickets_list_sel = "SELECT `added_by` FROM  `support_tickets_list` WHERE `ticket_id` = $ticket_id";
			$qu_support_tickets_list_EXE = mysqli_query($KONN, $qu_support_tickets_list_sel);
			if (mysqli_num_rows($qu_support_tickets_list_EXE)) {
				$support_tickets_list_DATA = mysqli_fetch_assoc($qu_support_tickets_list_EXE);
				$added_by = (int) $support_tickets_list_DATA['added_by'];

				if ($added_by != 0) {
					//notify
					notifyUser("Your ticket has been assigned to IT Agent", "tickets/list/", $added_by);
				}

			}












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

