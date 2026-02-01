<?php

function checkUserService($service_id): bool
{
	global $KONN;
	global $USER_ID;
	$qu_chkSer_sel = "SELECT `service_id` FROM  `employees_services` WHERE ((`service_id` = $service_id) AND (`employee_id` = $USER_ID))";
	$qu_chkSer_EXE = mysqli_query($KONN, $qu_chkSer_sel);
	if(mysqli_num_rows($qu_chkSer_EXE) == 1){
		return true;
	} else {
		return false;
	}

}
function insertHRApprovalRequest($related_table, $related_id, $sent_to_id, $log_remark, $added_by): bool
{
	global $KONN;
	$sent_date = date('Y-m-d H:i:00');
	$qu_ins = "INSERT INTO `hr_approvals` (
						`related_table`, 
						`related_id`, 
						`sent_date`, 
						`sent_to_id`, 
						`log_remark`, 
						`added_by`
					) VALUES (
						'" . $related_table . "', 
						'" . $related_id . "', 
						'" . $sent_date . "', 
						'" . $sent_to_id . "', 
						'" . $log_remark . "', 
						'" . $added_by . "'
	);";

	if (mysqli_query($KONN, $qu_ins)) {
		return true;
	} else {
		return false;
	}

}

function notifyUser($notification_text, $related_page, $employee_id): bool
{
	global $KONN;
	$notification_date = date('Y-m-d H:i:00');

	$qu_ins = "INSERT INTO `employees_notifications` (
		`notification_date`, 
		`notification_text`, 
		`related_page`, 
		`employee_id`
	) VALUES (
		'" . $notification_date . "', 
		'" . $notification_text . "', 
		'" . $related_page . "', 
		'" . $employee_id . "'
	);";

	if (mysqli_query($KONN, $qu_ins)) {
		return true;
	} else {
		return false;
	}

}
function InsertAssetAssign($asset_id, $assigned_by, $assigned_to, $assign_remarks): bool
{
	global $KONN;
	$assigned_date = date('Y-m-d H:i:00');

	$qu_ins = "INSERT INTO `z_assets_list_assign` (
		`asset_id`, 
		`assigned_date`, 
		`assigned_by`, 
		`assigned_to`, 
		`assign_remarks`
	) VALUES (
		'" . $asset_id . "', 
		'" . $assigned_date . "', 
		'" . $assigned_by . "', 
		'" . $assigned_to . "', 
		'" . $assign_remarks . "'
	);";

	if (mysqli_query($KONN, $qu_ins)) {
		return true;
	} else {
		return false;
	}

}

function insertSysLog($related_table, $related_id, $log_action, $log_remark, $logger_type, $logged_by, $log_type): bool
{
	global $KONN;
	$log_date = date('Y-m-d H:i:00');
	$qu_ins = "INSERT INTO `sys_logs` (
						`related_table`, 
						`related_id`, 
						`log_date`, 
						`log_action`, 
						`log_remark`, 
						`logger_type`, 
						`logged_by`, 
						`log_type` 
					) VALUES (
						'" . $related_table . "', 
						'" . $related_id . "', 
						'" . $log_date . "', 
						'" . $log_action . "', 
						'" . $log_remark . "', 
						'" . $logger_type . "', 
						'" . $logged_by . "', 
						'" . $log_type . "' 
	);";

	if (mysqli_query($KONN, $qu_ins)) {
		return true;
	} else {
		return false;
	}

}

function get_time_progress($start_date, $end_date) {
    // Convert to timestamps
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    $now = time();

    // Handle edge cases
    if ($now < $start) $now = $start;
    if ($now > $end) $now = $end;

    // Calculate total and elapsed time
    $total = $end - $start;
    $elapsed = $now - $start;

    // Prevent division by zero
    if ($total <= 0) return 0;

    // Calculate percentage done
    $percent = round(($elapsed / $total) * 100, 2);

    // Calculate time left percentage
    $time_left_percent = 100 - $percent;

	return $time_left_percent;
	
}

function calculatePercentage($part, $total)
{
	if ($total == 0) {
		return 0; // Avoid division by zero
	}
	$percentage = ($part / $total) * 100;
	return round($percentage, 2); // Round to two decimal places
}

function getDateOnly($timeDate)
{
	$tdArr = explode(" ", $timeDate);
	return dater($tdArr[0]);
}
function timeDef($date1, $date2)
{

	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$interval = $datetime1->diff($datetime2);

	$year = $interval->format('%y');
	$months = $interval->format('%m');
	$days = $interval->format('%a');
	$hours = $interval->format('%h');
	$minutes = $interval->format('%i');

	$result = ''; 
	if ($year != 0) {
		$result .= $year . ' year ';
	}
	if ($months != 0) {
		$result .= $months . ' months ';
	}
	if ($days != 0) {
		$result .= $days . ' days ';
	}
	if ($hours != 0) {
		$result .= $hours . ' hours ';
	}
	if ($minutes != 0) {
		$result .= $minutes . ' minutes ';
	}
	return $result;
}
function timeAgo($datetime)
{
	$now = new DateTime();
	$uploaded = new DateTime($datetime);
	$diff = $now->diff($uploaded);

	// Total seconds difference
	$seconds = $now->getTimestamp() - $uploaded->getTimestamp();

	if ($seconds < 60) {
		return $seconds . " seconds ago";
	} elseif ($seconds < 3600) {
		return floor($seconds / 60) . " minutes ago";
	} elseif ($seconds < 86400) {
		return floor($seconds / 3600) . " hours ago";
	} elseif ($seconds < 86400 * 5) {
		return $diff->days . " days ago";
	} else {
		// More than 5 days → show date
		return $uploaded->format("Y-m-d");
	}
}
function getInitials($string)
{
	// Trim extra spaces and normalize
	$string = trim(preg_replace('/\s+/', ' ', $string));

	// Split words
	$words = explode(' ', $string);

	if (count($words) >= 2) {
		// Take first letter of first 2 words
		return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
	} else {
		// If only one word, take first 2 letters
		return strtoupper(substr($words[0], 0, 2));
	}
}

function verifyPass($pass, $dbPass): bool
{
	return password_verify($pass, $dbPass);
}
function hashPass($pass): string
{
	return password_hash($pass, PASSWORD_BCRYPT, ["cost" => 12]);
}

function InsertAtpLog($log_action, $atp_id, $logger_type, $logged_by, $log_dept): bool
{
	global $KONN;
	$log_date = date('Y-m-d H:i:00');
	$qu_properties_list_logs_ins = "INSERT INTO `atps_list_logs` (
						`atp_id`, 
						`log_action`, 
						`log_date`, 
						`logger_type`, 
						`log_dept`, 
						`logged_by` 
					) VALUES (
						'" . $atp_id . "', 
						'" . $log_action . "', 
						'" . $log_date . "', 
						'" . $logger_type . "', 
						'" . $log_dept . "', 
						'" . $logged_by . "' 
					);";

	if (mysqli_query($KONN, $qu_properties_list_logs_ins)) {
		return true;
	} else {
		return false;
	}

}

function getEmirateCode($eID): string
{
	$cde = '';
	if ($eID == 1) {
		$cde = 'AD';
	} else if ($eID == 2) {
		$cde = 'DXB';
	} else if ($eID == 3) {
		$cde = 'AHJ';
	} else if ($eID == 4) {
		$cde = 'AJM';
	} else if ($eID == 5) {
		$cde = 'RAK';
	} else if ($eID == 6) {
		$cde = 'UQM';
	} else if ($eID == 7) {
		$cde = 'FUJ';
	} else {
		$cde = 'NA';
	}
	return $cde;
}
function cleanString($string)
{
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function getAtpRef($namer, $emirate_id, $atpId): string
{
	$namer = cleanString($namer);
	$nameArr = explode(' ', $namer);
	$fsS = '';
	if (count($nameArr) > 1) {
		$wCount = 0;
		for ($i = 0; $i < count($nameArr); $i++) {
			$thsField = $nameArr[$i];
			$fsS .= strtoupper($thsField[0]);
			$wCount++;
			if ($wCount >= 3) {
				break;
			}
		}
	} else {
		//its 1 word
		$thsField = $nameArr[0];
		$len = strlen($thsField) - 1;
		$st = strtoupper($thsField[0]);
		$en = strtoupper($thsField[$len]);
		$fsS = $st . $en . '0';
	}




	return $fsS . date('my') . getEmirateCode($emirate_id) . '0' . $atpId;
}

function encryptData($data)
{
	global $isDataEncrypted;

	$first_key = base64_decode(FIRSTKEY);
	$second_key = base64_decode(SECONDKEY);

	$method = "AES-256-CBC";
	$iv_length = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($iv_length);

	$first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
	$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

	$output = base64_encode($iv . $second_encrypted . $first_encrypted);


	if ($isDataEncrypted) {
		return $output;
	} else {
		return $data;
	}
}

function decryptData($input)
{
	global $isDataEncrypted;
	if (!$isDataEncrypted) {
		return $input;
	}

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

function print_data($dt): string
{
	return htmlspecialchars_decode("" . $dt);
}

function reportError($error_text, $adderType, $addedBY)
{
	global $KONN;
	global $Running_Environment;
	$added_date = date('y-m-d H:i:s');
	$sysErrorsQryIns = "INSERT INTO `sys_errors` (
		`error_text`, 
		`added_date`, 
		`adder_type`, 
		`added_by` 
	) VALUES ( ?, ?, ?, ? );";

	if ($sysErrorsStmtIns = mysqli_prepare($KONN, $sysErrorsQryIns)) {
		if (mysqli_stmt_bind_param($sysErrorsStmtIns, "sssi", $error_text, $added_date, $adderType, $addedBY)) {
			if (!mysqli_stmt_execute($sysErrorsStmtIns)) {
				die("General Error - please contact support 3");
			}

		} else {
			die("General Error - please contact support2");
		}
	} else {
		die("General Error - please contact support1");
	}

	if ($Running_Environment == "local") {
		die("General Error - please contact support - 150");
	}
}


function generateToken()
{
	$data = openssl_random_pseudo_bytes(16);
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)) . "-" . round(microtime(true));
}

function generateCode()
{
	$data = openssl_random_pseudo_bytes(16);
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	return "IAIDL_" . vsprintf('%s', str_split(bin2hex($data), 3)) . "-" . round(microtime(true));
}



function generatePassword()
{
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array();
	$alphaLength = strlen($alphabet) - 1;
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass);
}

function generate_guid()
{
	if (function_exists('com_create_guid')) {
		return com_create_guid();
	} else {
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8) . $hyphen
			. substr($charid, 8, 4) . $hyphen
			. substr($charid, 12, 4) . $hyphen
			. substr($charid, 16, 4) . $hyphen
			. substr($charid, 20, 12);
		return $uuid . "-" . round(microtime(true));
	}
}



function validateEmail($email)
{

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return false;
	} else {
		return true;
	}
}


function fixLines($dt): string
{
	return $dt;
}

function build_textarea(
	string $dataType,
	string $dataName,
	string $dataDen,
	string $dataValue,
	string $dataClass,
	int $isDisabled,
	int $isReadOnly,
	int $isRequired = 0,
	string $dir = "ltr",
	string $extraAttributes = ""
) {
	$dataValue = fixLines("" . $dataValue);
	$extra = $extraAttributes;
	if ($isDisabled == 1) {
		$extra .= ' disabled ';
	}
	if ($isReadOnly == 1) {
		$extra .= ' readonly ';
	}
	if ($isRequired == 1) {
		$extra .= ' placeholder="' . lang("*_Field_is_required") . '"';
	}
	if ($dir == 'rtl') {
		$extra .= ' dir="rtl" ';
	}
	$res = '<textarea class="' . $dataClass . '" data-req="' . $isRequired . '" data-type="' . $dataType . '" data-name="' . $dataName . '" data-den="' . $dataDen . '" ' . $extra . '>' . nl2br($dataValue) . '</textarea>';
	return $res;
}

function build_input(
	string $dataType,
	string $dataName,
	string $dataDen,
	string $dataValue,
	string $dataClass,
	int $isDisabled,
	int $isReadOnly,
	int $isRequired = 0,
	string $dir = "ltr",
	string $extraAttributes = ""
): string {



	$dataValue = fixLines("" . $dataValue);
	$extra = $extraAttributes;
	if ($isDisabled == 1) {
		$extra .= ' disabled ';
	}
	if ($isReadOnly == 1) {
		$extra .= ' readonly ';
	}

	if ($dir == 'rtl') {
		$extra .= ' dir="rtl" ';
	}


	if ($isRequired == 1) {
		$extra .= ' placeholder="' . lang("*_Field_is_required", "AAR") . '"';
	}
	$thsType = "text";
	$isTime = 0;
	if ($dataType == 'hidden') {
		$thsType = "hidden";
	} else if ($dataType == 'hidden time') {
		$thsType = "hidden";
		$isTime = 1;
	} else if ($dataType == 'password') {
		$thsType = "password";
	} else if ($dataType == 'hidden text_mixed') {
		$dataType = "text_mixed";
		$thsType = "hidden";
	} else if ($dataType == 'hidden float') {
		$dataType = "float";
		$thsType = "hidden";
	} else if ($dataType == 'text_mixed') {
		$dataType = "text_mixed";
		$thsType = "text";
	}
	$res = '<input type="' . $thsType . '" class="' . $dataClass . '" data-time="' . $isTime . '" data-req="' . $isRequired . '" data-type="' . $dataType . '" data-name="' . $dataName . '" data-den="' . $dataDen . '" value="' . $dataValue . '" ' . $extra . '>';
	return $res;
}

function build_select(
	string $dataName,
	string $mainId,
	string $mainName,
	string $tblName,
	string $cond,
	string $dataDen = '0',
	string $dataClass = 'inputer',
	int $dataValue = 0,
	int $isDisabled = 0,
	int $isReadOnly = 0,
	int $isRequired = 0,
	int $withoutPS = 0,
	string $startText = "",
	string $extraAttributes = ""
) {

	$dataValue = (int) $dataValue;


	$extra = $extraAttributes;
	if ($isReadOnly == 1) {
		$extra .= ' readonly ';
	}
	if ($isDisabled == 1) {
		$extra .= ' disabled ';
	}

	global $KONN;

	if ($startText == "") {
		$startText = lang("Please_Select");
	}



	$res = '<select data-name="' . $dataName . '" data-type="int" data-den="' . $dataDen . '" data-req="' . $isRequired . '" class="' . $dataClass . '" value="' . $dataValue . '"  ' . $extra . '>';
	if ($withoutPS == 0) {
		if ($dataValue == 0) {
			$res .= '<option value="0" selected>' . $startText . '</option>';
		} else {
			$res .= '<option value="0">' . $startText . '</option>';
		}
	}

	$qu_SS_sel = "SELECT `$mainId`, $mainName FROM  `$tblName` $cond";
	//return $qu_SS_sel;
	$qu_SS_EXE = mysqli_query($KONN, $qu_SS_sel);
	if (mysqli_num_rows($qu_SS_EXE)) {
		while ($SS_REC = mysqli_fetch_array($qu_SS_EXE)) {
			$optId = (int) $SS_REC[0];
			$optName = $SS_REC[1];
			$thsSlct = ' ';
			if ($optId == $dataValue) {
				$thsSlct = ' selected';
			}
			$res .= '<option value="' . $optId . '" ' . $thsSlct . '>' . $optName . '</option>';
		}
	}
	$res .= '</select>';



	return $res;


}

function build_actDis_select(
	string $dataName,
	string $dataClass = 'inputer',
	int $dataValue = 0,
	int $isDisabled = 0,
	int $isReadOnly = 0,
	int $isRequired = 1,
	int $isAlwaysChnge = 0,
	string $startText = "",
	string $extraAttributes = ""
) {

	$dataValue = (int) $dataValue;


	$extra = $extraAttributes;
	if ($isReadOnly == 1) {
		$extra .= ' readonly ';
	}
	if ($isDisabled == 1) {
		$extra .= ' disabled ';
	}

	if ($startText == "") {
		$startText = lang("Please_Select");
	}



	$res = '<select data-name="' . $dataName . '" req-cat="' . $isAlwaysChnge . '" data-type="int" data-den="100" data-req="' . $isRequired . '" class="' . $dataClass . '" value="' . $dataValue . '"  ' . $extra . '>';
	$res .= '<option value="100" disabled selected>' . $startText . '</option>';


	if ($dataValue == 1) {
		$res .= '<option value="1" selected>' . lang("Active") . '</option>';
		$res .= '<option value="0">' . lang("Disabled") . '</option>';
	} else {
		$res .= '<option value="1">' . lang("Active") . '</option>';
		$res .= '<option value="0" selected>' . lang("Disabled") . '</option>';
	}

	$res .= '</select>';



	return $res;


}
function build_yesNo_select(
	string $dataName,
	string $dataClass = 'inputer',
	int $dataValue = 0,
	int $isDisabled = 0,
	int $isReadOnly = 0,
	int $isRequired = 1,
	int $isAlwaysChnge = 0,
	string $startText = "",
	string $extraAttributes = ""
) {

	$dataValue = (int) $dataValue;


	$extra = $extraAttributes;
	if ($isReadOnly == 1) {
		$extra .= ' readonly ';
	}
	if ($isDisabled == 1) {
		$extra .= ' disabled ';
	}

	if ($startText == "") {
		$startText = lang("Please_Select");
	}


	$res = '<select data-name="' . $dataName . '" req-cat="' . $isAlwaysChnge . '" data-type="int" data-den="100" data-req="' . $isRequired . '" class="' . $dataClass . '" value="' . $dataValue . '"  ' . $extra . '>';
	$res .= '<option value="100" disabled selected>' . $startText . '</option>';


	if ($dataValue == 1) {
		$res .= '<option value="1" selected>' . lang("Yes") . '</option>';
		$res .= '<option value="0">' . lang("No") . '</option>';
	} else {
		$res .= '<option value="1">' . lang("Yes") . '</option>';
		$res .= '<option value="0" selected>' . lang("No") . '</option>';
	}

	$res .= '</select>';



	return $res;


}









function test_inputs($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = str_replace("'", "", $data);
	$data = str_replace('"', "", $data);
	$data = str_replace(",", " ", $data);
	return $data;
}

function test_inputs_2($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlentities($data);
	$data = str_replace("'", "", $data);
	$data = str_replace('"', "", $data);
	$data = str_replace(",", " ", $data);
	return $data;
}


function printer($dt = '')
{
	$rr = '';
	if ($dt != '') {
		$rr = nl2br(html_entity_decode($dt));
	}
	return $rr;
}


function dater($date)
{
	if ($date != '') {
		$dateARR = explode('-', $date);
		return $dateARR[2] . '-' . $dateARR[1] . '-' . $dateARR[0];
	} else {
		return false;
	}
}

function compress_image($tempPath, $originalPath, $imageQuality)
{

	// Get image info 
	$imgInfo = getimagesize($tempPath);
	$mime = $imgInfo['mime'];

	// Create a new image from file 
	switch ($mime) {
		case 'image/jpeg':
			$image = imagecreatefromjpeg($tempPath);
			break;
		case 'image/png':
			$image = imagecreatefrompng($tempPath);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($tempPath);
			break;
		case 'image/webp':
			$image = imagecreatefromwebp($tempPath);
			break;
		default:
			$image = imagecreatefromjpeg($tempPath);
	}

	// Save image 
	imagejpeg($image, $originalPath, $imageQuality);
	// Return compressed image 
	return $originalPath;
}

function upload_file($file_req, $sizer = 3000, $directory = 'uploads', $pnters = '../')
{
	if (isset($_FILES[$file_req])) {
		$targer_dir = $pnters . $directory . "/";

		//data collection
		$fileName = $_FILES[$file_req]["name"]; // The file name
		$fileTmpLoc = $_FILES[$file_req]["tmp_name"]; // File in the PHP tmp folder
		$fileType = $_FILES[$file_req]["type"]; // The type of file it is
		$fileSize = $_FILES[$file_req]["size"]; // File size in bytes
		$fileErrorMsg = $_FILES[$file_req]["error"]; // 0 for false... and 1 for true

		if (!$fileTmpLoc) { // if file not chosen
			return false;
		}

		//check extensions
		$ths_ext = $fileType;
		if (!($ths_ext == 'image/svg+xml' || $ths_ext == 'image/jpeg' || $ths_ext == 'image/jpg' || $ths_ext == 'image/png' || $ths_ext == 'application/pdf' || $ths_ext == 'image/webp')) {
			//file is NOT ACCEPTED
			return false;
		}


		//check sizes
		$ths_size = $fileSize;
		$ths_size = round($ths_size / 1024);
		if ($ths_size > $sizer) {
			return false;
		}

		/*
																																																																																																																																																		//manipulate image width and height
																																																																																																																																																		$x = 480;
																																																																																																																																																		$y = 540;
																																																																																																																																																		$nw_img = @imagecreate($x, $y);
																																																																																																																																																		*/

		$ext = explode(".", $fileName);
		$extensiom = end($ext);
		if ($extensiom == 'blob') {
			$extensiom = 'png';
		}
		$New_name = 'p_' . generate_guid();
		$db_file_name = $New_name . '.' . $extensiom;
		if (move_uploaded_file($fileTmpLoc, $targer_dir . $New_name . '.' . $extensiom)) {
			return $db_file_name;
		} else {
			return false;
		}






	} else {
		return false;
	}//end of isset
}


function get_ip_address()
{
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip); // just to be safe
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
					return $ip;
				}
			}
		}
	}
}

