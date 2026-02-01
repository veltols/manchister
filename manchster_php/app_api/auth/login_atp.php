<?php



//get atp info
$atp_id = 0;
$atp_name = "";
$atp_status_id = "";
$dbPass = "";
$atpsListQrySel = "SELECT 
					`atps_list`.`atp_id`, 
					`atps_list`.`atp_name`, 
					`atps_list`.`atp_status_id`,
					`atps_list_pass`.`pass_value` AS `db_pass`
					FROM  `atps_list` 
					INNER JOIN `atps_list_pass` ON `atps_list`.`atp_id` = `atps_list_pass`.`atp_id`
					WHERE ( ( `atp_email` = ? ) ) LIMIT 1 ";

if ($atpsListStt = mysqli_prepare($KONN, $atpsListQrySel)) {
	if (mysqli_stmt_bind_param($atpsListStt, "s", $encIdder)) {
		if (mysqli_stmt_execute($atpsListStt)) {
			$atpsListRows = mysqli_stmt_get_result($atpsListStt);
			while ($atpsListRecord = mysqli_fetch_assoc($atpsListRows)) {
				$atp_id = (int) $atpsListRecord['atp_id'];
				$atp_name = print_data("" . decryptData($atpsListRecord['atp_name']));
				$atp_status_id = (int) $atpsListRecord['atp_status_id'];
				$dbPass = "" . $atpsListRecord['db_pass'];
			}
		} else {
			//Execute failed 
			reportError(mysqli_stmt_error($atpsListStt), $encIdder, '0');
		}
	} else {
		//bind failed 
		reportError(mysqli_stmt_error($atpsListStt), $encIdder, '0');
	}
} else {
	//prepare failed 
	reportError('atps_list failed to prepare stmt ', $encIdder, '0');
}
mysqli_stmt_close($atpsListStt);

//verify existance
if ($atp_id != 0 && $atp_name != '') {
	//verify pass

	if (verifyPass($passer, $dbPass)) {

		//-----------------------------------------------------------------------------------------

		//delete auth tokens
		//delete all auth tokens
		$authTokensQryDel = "DELETE FROM `auth_tokens` WHERE ( ( `token_idder` = ? ) ) ";
		if ($authTokensStmtDel = mysqli_prepare($KONN, $authTokensQryDel)) {
			if (mysqli_stmt_bind_param($authTokensStmtDel, "s", $encIdder)) {
				if (!mysqli_stmt_execute($authTokensStmtDel)) {
					//Execute failed 
					reportError(mysqli_stmt_error($authTokensStmtDel), 'atps_list', $atp_id);
					$RES = false;
					$error = 100;
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($authTokensStmtDel), 'atps_list', $atp_id);
				$RES = false;
				$error = 200;
			}
		} else {
			//prepare failed 
			reportError('auth_tokens failed to prepare stmt ', 'atps_list', $atp_id);
			$RES = false;
			$error = 300;
		}
		mysqli_stmt_close($authTokensStmtDel);

		//TODO
		//verify OTP

		//login


		if ($atp_status_id <= 2) {
			$IAM_ARRAY['user_type'] = "atp_register";
			$_SESSION['user_code'] = 'atpReg';
			$_SESSION['user_type'] = 'atp_register';
		} else {
			$IAM_ARRAY['user_type'] = "atp";
			$_SESSION['user_code'] = 'atp';
			$_SESSION['user_type'] = 'atp';
		}



		$RES = true;
		$_SESSION['user_id'] = $atp_id;
		$_SESSION['user_record_id'] = $user_record_id;
		$_SESSION['user_name'] = $atp_name;
		$_SESSION['user_email'] = $encIdder;
		$_SESSION['user_picture'] = 'user.png';
		$_SESSION['tmiezone_name'] = 'Asia/Dubai';
		$_SESSION['is_contact'] = false;


		//-----------------------------------------------------------------------------------------




	} else {
		//err pass
		$RES = false;
		$error = 6500;
	}





} else {
	//err id
	$RES = false;
	$error = 1200;
}













