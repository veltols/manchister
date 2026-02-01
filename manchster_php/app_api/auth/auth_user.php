<?php


$IAM_ARRAY;

$user_record_id = 0;
$RES = false;
$idder = "";
$token_value = $falseToken;
$error = 0;
if (isset($_POST['idder']) && isset($_POST['passer']) && isset($_POST['token'])) {
	$idder = "" . test_inputs($_POST['idder']);
	$passer = "" . test_inputs($_POST['passer']);
	$authToken = "" . test_inputs($_POST['token']);
	$encIdder = encryptData($idder);

	if ($authToken != $falseToken) {

		//get token from db
		$dbTokenValue = "";

		$authTokensQrySel = "SELECT `token_value` FROM  `auth_tokens` WHERE ( ( `token_idder` = ? ) ) ORDER BY `token_id` DESC LIMIT 1";
		if ($authTokensStt = mysqli_prepare($KONN, $authTokensQrySel)) {
			if (mysqli_stmt_bind_param($authTokensStt, "s", $encIdder)) {
				if (mysqli_stmt_execute($authTokensStt)) {
					$authTokensRows = mysqli_stmt_get_result($authTokensStt);
					while ($authTokensRecord = mysqli_fetch_assoc($authTokensRows)) {
						$dbTokenValue = "" . $authTokensRecord['token_value'];
					}
				} else {
					//Execute failed 
					reportError(mysqli_stmt_error($authTokensStt), $encIdder, '0');
					$RES = false;
					$error = 7;
				}
			} else {
				//bind failed 
				reportError(mysqli_stmt_error($authTokensStt), $encIdder, '0');
				$RES = false;
				$error = 8;
			}
		} else {
			//prepare failed 
			reportError('Err-56454', $encIdder, '0');
			$RES = false;
			$error = 9;
		}

		//check if token id correct
		if ($authToken == $dbTokenValue) {
			//check if user exist in records

			$user_record_id = 0;
			$user_id = 0;
			$user_email = "";
			$user_type = "";
			$int_ext = "";



			$usersListQrySel = "SELECT * FROM  `users_list` WHERE ( ( `user_email` = ? ) AND ( `is_active` = 1) ) LIMIT 1 ";
			if ($usersListStt = mysqli_prepare($KONN, $usersListQrySel)) {
				if (mysqli_stmt_bind_param($usersListStt, "s", $encIdder)) {
					if (mysqli_stmt_execute($usersListStt)) {
						$usersListRows = mysqli_stmt_get_result($usersListStt);
						while ($usersListRecord = mysqli_fetch_assoc($usersListRows)) {
							$user_record_id = (int) $usersListRecord['record_id'];
							$user_id = (int) $usersListRecord['user_id'];
							$user_email = "" . $usersListRecord['user_email'];
							$user_type = "" . $usersListRecord['user_type'];
							$int_ext = "" . $usersListRecord['int_ext'];
						}
					} else {
						//Execute failed 
						reportError(mysqli_stmt_error($usersListStt), $encIdder, '0');
						$RES = false;
						$error = 2;
					}
				} else {
					//bind failed 
					reportError(mysqli_stmt_error($usersListStt), $encIdder, '0');
					$RES = false;
					$error = 3;
				}
			} else {
				//prepare failed 
				reportError('users_list failed to prepare stmt ', $encIdder, '0');
				$RES = false;
				$error = 4;
			}


			$user_id = (int) $user_id;

			
			if ($int_ext == 'int') {
				if ($user_type == 'root') {
					//its root
					$IAM_ARRAY['user_type'] = "root"; //root
					require_once('login_employee.php');
				} else if ($user_type == 'hr') {
					//its Employee
					$IAM_ARRAY['user_type'] = "hr";
					require_once('login_employee.php');
				} else {
					//its Employee
					$IAM_ARRAY['user_type'] = "emp";
					require_once('login_employee.php');
				}
			} else {
				//external user
				if ($user_type == 'atp') {
					require_once('login_atp.php');
				}

			}









		} else {
			//token failed
			$RES = false;
			$error = 66;
		}







	} else {
		$RES = false;
		$error = 13;
	}
} else {
	$RES = false;
	$error = 14;
}



if ($RES == true) {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['token'] = $token_value;
} else {
	$_SESSION['token_value'] = "";
	$_SESSION['user_type'] = "";
	$_SESSION['user_id'] = 0;
	$_SESSION['user_name'] = "";
	$_SESSION['user_email'] = "";
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['token'] = $falseToken;
	$IAM_ARRAY['error'] = "" . $error;
	$IAM_ARRAY['user_type'] = "";
	$_SESSION['user_picture'] = "";
}

header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


