<?php
require_once('../bootstrap/app_config.php');


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

$IAM_ARRAY;
$RES = false;
$idder = "";
//$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

//die('sss333');

$IAM_ARRAY['data'] = [];

$idder = 'city_id';
$namer = "city_name$lang_db";
$tbDb = 'sys_countries_cities';



$IAM_ARRAY['success'] = true;
array_push(
	$IAM_ARRAY['data'],
	array(
		"idder" => 100,
		"namer" => lang('Please_Select', 'الرجاء_الاختيار')
	)
);
array_push(
	$IAM_ARRAY['data'],
	array(
		"idder" => 1,
		"namer" => lang('Yes', 'AAR')
	)
);
array_push(
	$IAM_ARRAY['data'],
	array(
		"idder" => 0,
		"namer" => lang('No', 'AAR')
	)
);

