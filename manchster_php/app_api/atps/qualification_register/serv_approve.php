<?php






//change form status form_status = approved @ atps_qualification_ev
$qu_atps_qualification_ev_updt = "UPDATE  `atps_qualification_ev` SET 
								`form_status` = 'approved'
								WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_qualification_ev_updt)) {
	die("ERr-98792");
}

//update next phase @ atps_list
$phase_id = 6;
$is_phase_ok = 1;

//check if to take back to reg proccess or not
$accreditation_type = 0;
$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$accreditation_type = (int) $atps_list_DATA['accreditation_type'];
	if ($accreditation_type == 1) {
		//it started at accredited
		//get accredited
		$phase_id = 7;
		$is_phase_ok = 0;
	} else {
		//it starts at qualification
		//send atp to phase 3
		$phase_id = 3;
		$is_phase_ok = 1;
	}
}

$qu_atps_list_updt = "UPDATE  `atps_list` SET 
							`phase_id` = '" . $phase_id . "', 
							`is_phase_ok` = '" . $is_phase_ok . "' 
							WHERE `atp_id` = $atp_id;";

if (!mysqli_query($KONN, $qu_atps_list_updt)) {
	die("ER-e398792");
}

//insert ATP log
if (InsertAtpLog("IQC Approved Faculty Details", $atp_id, 'employees_list', $EMPLOYEE_ID, 'R&C')) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['message'] = "Succeed";
} else {
	reportError('atp log failed - 4584864 ', 'employees_list', $EMPLOYEE_ID);
}





