<?php



//change form status cancel_status = approved @ atps_list_cancel
$qu_atps_list_cancel_updt = "UPDATE  `atps_list_cancel` SET 
								`cancel_status` = 'rejected',
								`rc_comment` = '" . $rc_comment . "'
								WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_list_cancel_updt)) {
	die("ERr-98792");
}



//insert ATP log
if (InsertAtpLog("IQC rejected cancellation request", $atp_id, 'employees_list', $EMPLOYEE_ID, 'R&C')) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['message'] = "Succeed";
} else {
	reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
}