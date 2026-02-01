<?php



//change form status form_status = approved @ atps_sed_form
$qu_atps_sed_form_updt = "UPDATE  `atps_sed_form` SET 
								`form_status` = 'review',
								`rc_comment` = '" . $rc_comment . "'
								WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_sed_form_updt)) {
	die("ERr-98792");
}


//update next phase @ atps_list
$phase_id = 4;
$is_phase_ok = 0;

$qu_atps_list_updt = "UPDATE  `atps_list` SET 
							`phase_id` = '" . $phase_id . "', 
							`is_phase_ok` = '" . $is_phase_ok . "'
							WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_list_updt)) {
	die("ERr-e398792");
}

//insert ATP log
if (InsertAtpLog("IQC commented on SED form", $atp_id, 'employees_list', $EMPLOYEE_ID, 'R&C')) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['message'] = "Succeed";
} else {
	reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
}





