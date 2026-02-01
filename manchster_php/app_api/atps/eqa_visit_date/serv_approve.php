<?php






//change form status form_status = approved @ atps_eqa_details
$qu_atps_eqa_details_updt = "UPDATE  `atps_eqa_details` SET 
								`form_status` = 'approved'
								WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_eqa_details_updt)) {
	die("ERr-98792");
}

//update next phase @ atps_list
$phase_id = 6;
$is_phase_ok = 1;

$qu_atps_list_updt = "UPDATE  `atps_list` SET 
							`phase_id` = '" . $phase_id . "', 
							`is_phase_ok` = '" . $is_phase_ok . "'
							WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_list_updt)) {
	die("ER-e398792");
}

//insert ATP log
if (InsertAtpLog("IQC Approved SED", $atp_id, 'employees_list', $EMPLOYEE_ID, 'R&C')) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['message'] = "Succeed";
} else {
	reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
}





