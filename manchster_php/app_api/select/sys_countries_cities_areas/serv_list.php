<?php
require_once('../../bootstrap/app_config.php');


if (isset($_POST['lang_db'])) {
	$langDb = test_inputs($_POST['lang_db']);
	if ($langDb == '_ar') {
		$lang_db = '_ar';
	} else {
		$lang_db = '';
	}
}

if (isset($_POST['lang'])) {
	$langer = test_inputs($_POST['lang']);
	if ($langer == 'ar') {
		$lang = 'ar';
	} else {
		$lang = 'en';
	}
}

$city_id = 0;
if (isset($_POST['city_id'])) {
	$city_id = (int) test_inputs($_POST['city_id']);
}

$IAM_ARRAY;
$RES = false;
$idder = "";
//$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

//die('sss333');

$IAM_ARRAY['data'] = [];



$sysCountriesCitiesAreasQrySel = "SELECT `area_id`, `area_name` FROM  `sys_countries_cities_areas` WHERE ( ( `city_id` = ? ) ) ";
if ($sysCountriesCitiesAreasStt = mysqli_prepare($KONN, $sysCountriesCitiesAreasQrySel)) {
	if (mysqli_stmt_bind_param($sysCountriesCitiesAreasStt, "i", $city_id)) {
		if (mysqli_stmt_execute($sysCountriesCitiesAreasStt)) {
			$sysCountriesCitiesAreasRows = mysqli_stmt_get_result($sysCountriesCitiesAreasStt);
			$IAM_ARRAY['success'] = true;
			array_push(
				$IAM_ARRAY['data'],
				array(
					"idder" => 0,
					"namer" => lang('Please_Select', 'الرجاء_الاختيار')
				)
			);
			while ($sysCountriesCitiesAreasRecord = mysqli_fetch_assoc($sysCountriesCitiesAreasRows)) {
				$area_id = (int) decryptData($sysCountriesCitiesAreasRecord['area_id']);
				$area_name = "" . decryptData($sysCountriesCitiesAreasRecord['area_name']);


				array_push(
					$IAM_ARRAY['data'],
					array(
						"idder" => $area_id,
						"namer" => '' . $area_name
					)
				);
			}
		} else {
			//Execute failed 
			reportError(mysqli_stmt_error($sysCountriesCitiesAreasStt), 'API', '0');
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['error'] = 'err-33';
		}
	} else {
		//bind failed 
		reportError(mysqli_stmt_error($sysCountriesCitiesAreasStt), 'API', '0');
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['error'] = 'err-22';
	}
} else {
	//prepare failed 
	reportError('sys_countries_cities_areas failed to prepare stmt ', 'API', '0');
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['error'] = 'err-11';
}
mysqli_stmt_close($sysCountriesCitiesAreasStt);








header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


