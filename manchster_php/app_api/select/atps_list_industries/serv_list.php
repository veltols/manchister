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

$idder = 'atp_category_id';
$namer = "atp_category_name$lang_db";
$tbDb = 'atps_list_categories';


$qu_table_related_sel = "SELECT `$idder`, `$namer` FROM `$tbDb` ORDER BY `$idder` ASC LIMIT 500";



$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
if (mysqli_num_rows($qu_table_related_EXE) >= 1) {
	$IAM_ARRAY['success'] = true;
	array_push(
		$IAM_ARRAY['data'],
		array(
			"idder" => 0,
			"namer" => lang('Please_Select', 'الرجاء_الاختيار')
		)
	);

	while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

		array_push(
			$IAM_ARRAY['data'],
			array(
				"idder" => (int) $ARRAY_SRC[$idder],
				"namer" => '' . decryptData($ARRAY_SRC[$namer])
			)
		);
	}

} else {
	$IAM_ARRAY['success'] = true;
	$IAM_ARRAY['error'] = mysqli_error($KONN);
	array_push(
		$IAM_ARRAY['data'],
		array(
			"idder" => 0,
			"namer" => lang('Please_Select', 'الرجاء_الاختيار')
		)
	);
}


