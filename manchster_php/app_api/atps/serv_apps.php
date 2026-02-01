<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$atp_id = 0;
$is_renew = 0;


if (isset($_REQUEST['atp_id'])) {
	$atp_id = (int) test_inputs($_REQUEST['atp_id']);
}

if (isset($_REQUEST['is_renew'])) {
	$is_renew = 1;
}


if ($IS_LOGGED == true && $USER_ID != 0) {




	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	//get init form data
	$qu_atps_form_init_sel = "SELECT * FROM  `atps_form_init` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);

		$form_status = "" . $atps_form_init_DATA['form_status'];
		$added_date = "" . $atps_form_init_DATA['added_date'];
		$submitted_date = "" . $atps_form_init_DATA['submitted_date'];
		$is_submitted = (int) $atps_form_init_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("Initial Registration Form", "AAR"),
				"app_type" => '1',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
	} else {
		$IAM_ARRAY['success'] = true;
	}
	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	//get init form data
	$qu_atps_form_init_sel = "SELECT * FROM  `atps_prog_register_req` WHERE ((`atp_id` = $atp_id) ) LIMIT 1";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);

		$form_status = "" . $atps_form_init_DATA['form_status'];
		$added_date = "" . $atps_form_init_DATA['added_date'];
		$submitted_date = "" . $atps_form_init_DATA['submitted_date'];
		$is_submitted = (int) $atps_form_init_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("Information Request Form", "AAR"),
				"app_type" => '2',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
	} else {
		$IAM_ARRAY['success'] = true;
	}


	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
	//get atps_prog_register_req form data
	$qu_atps_form_init_sel = "SELECT * FROM  `atps_prog_register_req` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);

		$form_status = "" . $atps_form_init_DATA['form_status'];
		$added_date = "" . $atps_form_init_DATA['added_date'];
		$submitted_date = "" . $atps_form_init_DATA['submitted_date'];
		$is_submitted = (int) $atps_form_init_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("Registration Request form", "AAR"),
				"app_type" => '2',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
	} else {
		$IAM_ARRAY['success'] = true;
	}

*/




	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
	//get faculty details form data
	$qu_atps_form_init_sel = "SELECT * FROM  `atps_faculty_details` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);

		$form_status = "" . $atps_form_init_DATA['form_status'];
		$added_date = "" . $atps_form_init_DATA['added_date'];
		$submitted_date = "" . $atps_form_init_DATA['submitted_date'];
		$is_submitted = (int) $atps_form_init_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("Faculty_Details", "AAR"),
				"app_type" => '3',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
	} else {

		$IAM_ARRAY['success'] = true;
	}






*/



	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
	//get SED form data
	$qu_atps_sed_form_sel = "SELECT * FROM  `atps_sed_form` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_sed_form_EXE = mysqli_query($KONN, $qu_atps_sed_form_sel);
	if (mysqli_num_rows($qu_atps_sed_form_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_sed_form_DATA = mysqli_fetch_assoc($qu_atps_sed_form_EXE);

		$form_status = "" . $atps_sed_form_DATA['form_status'];
		$added_date = "" . $atps_sed_form_DATA['added_date'];
		$submitted_date = "" . $atps_sed_form_DATA['submitted_date'];
		$is_submitted = (int) $atps_sed_form_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("SED", "AAR"),
				"app_type" => '4',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
		
	} else {

		$IAM_ARRAY['success'] = true;
	}


*/






	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
	//get SED form data
	$qu_atps_eqa_details_sel = "SELECT * FROM  `atps_eqa_details` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_eqa_details_EXE = mysqli_query($KONN, $qu_atps_eqa_details_sel);
	if (mysqli_num_rows($qu_atps_eqa_details_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_eqa_details_DATA = mysqli_fetch_assoc($qu_atps_eqa_details_EXE);

		$form_status = "" . $atps_eqa_details_DATA['form_status'];
		$added_date = "" . $atps_eqa_details_DATA['added_date'];
		$submitted_date = "" . $atps_eqa_details_DATA['submitted_date'];
		$is_submitted = (int) $atps_eqa_details_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("EQA Visit", "AAR"),
				"app_type" => '7',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);

	} else {

		$IAM_ARRAY['success'] = true;
	}




*/


	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
	//get faculty details form data
	$qu_atps_form_init_sel = "SELECT * FROM  `atps_qualification_ev` WHERE ((`atp_id` = $atp_id) AND (`is_renew` = $is_renew)) LIMIT 1";
	$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
	if (mysqli_num_rows($qu_atps_form_init_EXE)) {
		$IAM_ARRAY['success'] = true;
		$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);

		$form_status = "" . $atps_form_init_DATA['form_status'];
		$added_date = "" . $atps_form_init_DATA['added_date'];
		$submitted_date = "" . $atps_form_init_DATA['submitted_date'];
		$is_submitted = (int) $atps_form_init_DATA['is_submitted'];

		$app_status = lang("Pending_Submission", "AAR");
		$is_show_view = 0;
		if ($is_submitted == 1) {
			$app_status = lang("Submitted", "AAR");
			$is_show_view = 1;
		}


		array_push(
			$IAM_ARRAY['data'],
			array(
				"app_name" => lang("Qualification_Registration", "AAR"),
				"app_type" => '8',
				"submission_start" => decryptData($added_date),
				"submitted_date" => decryptData($submitted_date),
				"app_status" => decryptData($app_status),
				"form_status" => decryptData($form_status),
				"is_show_view" => $is_show_view
			)
		);
	} else {

		$IAM_ARRAY['success'] = true;
	}


*/






} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


