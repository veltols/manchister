<?php

$portalDirectory = 'iqc';
require_once('../bootstrap/app_config.php');
$thsType = 'emp';
require_once('../bootstrap/check_log_employee.php');






//get lang and theme
$USER_LANG = 'en';
$USER_THEME_ID = 1;

$qu_users_list_sel = "SELECT `user_lang`, `user_theme_id` FROM  `users_list` WHERE `record_id` = $USER_RECORD_ID";
$qu_users_list_EXE = mysqli_query($KONN, $qu_users_list_sel);
if(mysqli_num_rows($qu_users_list_EXE)){
	$users_list_DATA = mysqli_fetch_assoc($qu_users_list_EXE);
	$USER_LANG = "".$users_list_DATA['user_lang'];
	$USER_THEME_ID = ( int ) $users_list_DATA['user_theme_id'];
}




$page_title = "";
$COMPANY_LOGO = "logo_hor.png";

$url_requested = $_SERVER['REQUEST_URI'];
$url_len = strlen($url_requested);

$actual_path = "" . substr($url_requested, strpos($url_requested, '/'), $url_len);

$mainDomain = "/$portalDirectory/$thsType/";


if ($Running_Environment == "server") {
	$mainDomain = "/$thsType/";
}


$actual_path = str_replace('"', "", $actual_path);
$actual_path = str_replace("'", "", $actual_path);
$actual_path = str_replace($mainDomain, "", $actual_path);



$actual_pathArr = explode("?", $actual_path);
$actual_path = $actual_pathArr[0];


$POINTER = '';


$actual_pathArr2 = explode("/", $actual_path);
$apCheck = $actual_pathArr2[0];

$route_path = "";
$route_type = "";



$qu_local_routes_sel = "SELECT * FROM  `local_routes_rc` WHERE (`route_src` = '$actual_path')";
$apiDirectory = 'app_api';
if ('ext' === $apCheck) {
	$qu_local_routes_sel = "SELECT * FROM  `local_routes_external` WHERE (`route_src` = '$actual_path')";
	$apiDirectory = 'app_api_ext';
} else {
	$qu_local_routes_sel = "SELECT * FROM  `local_routes_rc` WHERE (`route_src` = '$actual_path')";
	$apiDirectory = 'app_api';
}



$qu_local_routes_EXE = mysqli_query($KONN, $qu_local_routes_sel);
if (mysqli_num_rows($qu_local_routes_EXE)) {
	$local_routes_DATA = mysqli_fetch_assoc($qu_local_routes_EXE);
	$route_family = "" . $local_routes_DATA['route_family'];
	$route_type = "" . $local_routes_DATA['route_type'];
	$POINTER = "" . $local_routes_DATA['route_pointer'];

	$route_path = '' . $local_routes_DATA['route_path'];
	if (strtolower($route_type) == 'api') {
		$route_path = "$apiDirectory/" . $route_family . "/" . $local_routes_DATA['route_path'];
	} else if (strtolower($route_type) == 'select') {
		$route_path = "$apiDirectory/" . $route_family . "/" . $local_routes_DATA['route_path'];
	}
} else {
	$route_path = '';
}

$t = 0;
if ($t == 1) {
	//echo $mainDomain . '<br>';
	echo $route_path;
	die();
}

//init main vars
$UPLOADS_DIRECTORY = $POINTER . '../uploads/';


//init directories
$DIR_dashboard = $POINTER . 'dashboard/';
$DIR_atps = $POINTER . 'atps/list/';
$DIR_profile = $POINTER . 'profile/';
$DIR_settings = $POINTER . 'settings_lists/';

$DIR_groups = $POINTER . 'groups/';
$DIR_calendar = $POINTER . 'calendar/';
$DIR_messages = $POINTER . 'messages/';
$DIR_tasks = $POINTER . 'tasks/';
$DIR_tickets = $POINTER . 'tickets/list/';
$DIR_assets = $POINTER . 'assets/list/';
$DIR_users = $POINTER . 'users/list/';
$DIR_sys_lists = $POINTER . 'settings_lists/';

$DIR_logout = $POINTER . 'logout/';

if ($route_path != "") {
	$thsPnt = '';

	//normal local route
	if ($route_type == "API" || $route_type == "select") {
		$thsPnt = '../';
		include_once($thsPnt . $route_path);
	} else {
		
		include_once($thsPnt . $route_path);
		include($thsPnt ."../public/app/footer_modal.php");
		include($thsPnt . "../public/app/form_controller.php");
	}

	mysqli_close($KONN);
	die();

} else {
	$POINTER = '';
	$page_title = "Error 404 not found!";
	//include_once('error.php');
	header("location:" . $mainDomain . "dashboard/");
	mysqli_close($KONN);
	die();
}







