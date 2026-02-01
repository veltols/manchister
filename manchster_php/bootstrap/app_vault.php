<?php

function InsertVaultLog($userType, $userId, $vaultId, $operation): bool
{
	global $KONN;
	$action_date = date('Y-m-d H:i:00');
	$qu_vl_ins = "INSERT INTO `sys_vault_logs` (
						`action_date`, 
						`action_by_type`, 
						`action_by`, 
						`vault_id`, 
						`operation` 
					) VALUES (
						'" . $action_date . "', 
						'" . $userType . "', 
						'" . $userId . "', 
						'" . $vaultId . "', 
						'" . $operation . "' 
					);";

	if (mysqli_query($KONN, $qu_vl_ins)) {
		return true;
	} else {
		return false;
	}
}




function encryptVaultData($data)
{


	$first_key = base64_decode(FIRSTKEY);
	$second_key = base64_decode(SECONDKEY);

	$method = "AES-256-CBC";
	$iv_length = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($iv_length);

	$first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
	$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

	$output = base64_encode($iv . $second_encrypted . $first_encrypted);


	return $output;
}

function decryptVaultData($input)
{


	$first_key = base64_decode(FIRSTKEY);
	$second_key = base64_decode(SECONDKEY);
	$mix = base64_decode($input);

	$method = "AES-256-CBC";
	$iv_length = openssl_cipher_iv_length($method);

	$iv = substr($mix, 0, $iv_length);
	$second_encrypted = substr($mix, $iv_length, 64);
	$first_encrypted = substr($mix, $iv_length + 64);

	$data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
	$second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

	if (hash_equals($second_encrypted, $second_encrypted_new)) {
		return $data;
	} else {
		return false;
	}

}




function generateVaultKey(): string
{
	$data = openssl_random_pseudo_bytes(16);
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	return "VSK_" . vsprintf('%s', str_split(bin2hex($data), 3)) . "-" . round(microtime(true));
}


function insertIntoVault($data): string
{
	global $KONN;
	$added_date = date('Y-m-d H:i:00');
	$vault_key = generateVaultKey();
	$vault_value = encryptVaultData($data);

	$qu_sys_vault_ins = "INSERT INTO `sys_vault` (
		`vault_key`, 
		`vault_value`, 
		`added_date` 
	) VALUES (
		'" . $vault_key . "', 
		'" . $vault_value . "', 
		'" . $added_date . "' 
	);";

	if (mysqli_query($KONN, $qu_sys_vault_ins)) {
		return $vault_key;
	} else {
		return "error";
	}
}

function revealVault($vault_key): string
{
	global $KONN;
	$added_date = date('Y-m-d H:i:00');
	$returnValue = "";

	$qu_sys_vault_sel = "SELECT `vault_id`, `vault_value` FROM  `sys_vault` WHERE ((`vault_key` = '$vault_key')) LIMIT 1";
	$qu_sys_vault_EXE = mysqli_query($KONN, $qu_sys_vault_sel);
	if (mysqli_num_rows($qu_sys_vault_EXE)) {
		$sys_vault_DATA = mysqli_fetch_assoc($qu_sys_vault_EXE);
		$vault_id = (int) $sys_vault_DATA['vault_id'];
		$vault_value = "" . $sys_vault_DATA['vault_value'];
		$returnValue = decryptVaultData($vault_value);
	}
	return $returnValue;
}
function updateVaultData($vault_key, $newData): bool
{
	global $KONN;
	$added_date = date('Y-m-d H:i:00');
	$vault_value = encryptVaultData($newData);

	$qu_sys_vault_updt = "UPDATE  `sys_vault` SET 
						`vault_value` = '" . $vault_value . "' 
						WHERE (('$vault_key' = `vault_key`))";

	if (mysqli_query($KONN, $qu_sys_vault_updt)) {
		return true;
	} else {
		return false;
	}

}