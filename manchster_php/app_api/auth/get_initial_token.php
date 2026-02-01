<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if (isset($_POST['idder'])) {
	$idder = "" . test_inputs($_POST['idder']);
	$encIdder = encryptData($idder);

	//generate true token
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['token'] = "sss";
	$token_date = date('Y-m-d H:i:s');
	$token_expiry = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:00') . ' + 1 minute'));
	$token_value = generateToken();

	$authTokensQryIns = "INSERT INTO `auth_tokens` (
			`token_value`, 
			`token_date`, 
			`token_expiry`, 
			`token_idder` 
		) VALUES ( ?, ?, ?, ? );";

	if ($authTokensStmtIns = mysqli_prepare($KONN, $authTokensQryIns)) {

		if (mysqli_stmt_bind_param($authTokensStmtIns, "ssss", $token_value, $token_date, $token_expiry, $encIdder)) {
			if (!mysqli_stmt_execute($authTokensStmtIns)) {
				$IAM_ARRAY['token'] = $falseToken;
				$token_value = $falseToken;
			}
		} else {
			//bind failed 
			reportError(mysqli_stmt_error($authTokensStmtIns), $encIdder, '0');
		}
	} else {
		//prepare failed 
		reportError(mysqli_stmt_error($authTokensStmtIns), $encIdder, '0');
	}




}




$IAM_ARRAY['success'] = true;
$IAM_ARRAY['token'] = $token_value;

header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


