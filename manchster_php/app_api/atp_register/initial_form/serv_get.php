<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();


$is_renew = 0;

if (isset($_POST['is_renew'])) {
	$is_renew = 1;
}

if ($IS_LOGGED == true && $USER_ID != 0 ) {
	
	
		
			$atpsFormInitQrySel = "SELECT 
			`delivery_plan`, 
			`org_chart`, 
			`site_plan`, 
			`sed_form`, 
			`atp_logo`, 
			`est_name`, 
			`est_name_ar`, 
			`iqa_name`, 
			`email_address`, 
			`registration_no`, 
			`registration_expiry`, 
			`emirate_id`, 
			`area_name`, 
			`street_name`, 
			`building_name`, 
			`atp_category_id` 
			FROM  `atps_form_init` WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";
			if ($atpsFormInitStt = mysqli_prepare($KONN, $atpsFormInitQrySel)) {
				if (mysqli_stmt_bind_param($atpsFormInitStt, "i", $USER_ID)) {
					if (mysqli_stmt_execute($atpsFormInitStt)) {
						$IAM_ARRAY['success'] = true;
						$atpsFormInitRows = mysqli_stmt_get_result($atpsFormInitStt);
						while ($atpsFormInitRecord = mysqli_fetch_assoc($atpsFormInitRows)) {
							$IAM_ARRAY['has_data'] = true;
							//$added_date          = "".decryptData( $atpsFormInitRecord['added_date'] );
							$delivery_plan = print_data("" . decryptData("".$atpsFormInitRecord['delivery_plan']));
							$org_chart = print_data("" . decryptData("".$atpsFormInitRecord['org_chart']));
							$site_plan = print_data("" . decryptData("".$atpsFormInitRecord['site_plan']));
							$sed_form = print_data("" . decryptData("".$atpsFormInitRecord['sed_form']));
							$atp_logo = print_data("" . decryptData("".$atpsFormInitRecord['atp_logo']));

							$est_name = print_data("" . decryptData($atpsFormInitRecord['est_name']));
							$est_name_ar = print_data("" . decryptData($atpsFormInitRecord['est_name_ar']));
							$iqa_name = "" . decryptData($atpsFormInitRecord['iqa_name']);
							$email_address = "" . decryptData($atpsFormInitRecord['email_address']);
							$registration_no = "" . decryptData($atpsFormInitRecord['registration_no']);
							$registration_expiry = "" . decryptData($atpsFormInitRecord['registration_expiry']);
							$emirate_id = print_data("" . decryptData($atpsFormInitRecord['emirate_id']));
							$area_name = print_data("" . decryptData($atpsFormInitRecord['area_name']));
							$street_name = print_data("" . decryptData($atpsFormInitRecord['street_name']));
							$building_name = print_data("" . decryptData($atpsFormInitRecord['building_name']));
							$atp_category_id = (int) $atpsFormInitRecord['atp_category_id'];
							//$is_submitted        = ( int ) $atpsFormInitRecord['is_submitted'];
							
							array_push(
								$IAM_ARRAY['data'],
								array(
									"delivery_plan" => $delivery_plan,
									"org_chart" => $org_chart,
									"site_plan" => $site_plan,
									"sed_form" => $sed_form,
									"atp_logo" => $atp_logo,
									"est_name" => $est_name,
									"est_name_ar" => $est_name_ar,
									"iqa_name" => $iqa_name,
									"email_address" => $email_address,
									"registration_no" => $registration_no,
									"registration_expiry" => $registration_expiry,
									"emirate_id" => $emirate_id,
									"area_name" => $area_name,
									"street_name" => $street_name,
									"building_name" => $building_name,
									"atp_category_id" => $atp_category_id
								)
							);
						}



					} else {
						//Execute failed 
						reportError(mysqli_stmt_error($atpsFormInitStt), "TYPER", $USER_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-300";
					}
				} else {
					//bind failed 
					reportError(mysqli_stmt_error($atpsFormInitStt), "TYPER", $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-200";
				}
			} else {
				//prepare failed 
				reportError('atps_form_init failed to prepare stmt ', "TYPER", $USER_ID);
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-100";
			}
			mysqli_stmt_close($atpsFormInitStt);

		//------------------------------------------------------------------------------------------------ LOAD DATA END
		//------------------------------------------------------------------------------------------------
	


} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

