<?php
session_start();



//MUST BE CHANGED IN PRODUCTION ----------------------------------------------
$is_NOTIFICATIONS = true;
$error_report = true;
$Running_Environment = "server"; //local, server
$isDataEncrypted = false;
$rootPass = '$2y$10$vyDJsXaSPt03tXcY8z/lBuLHIEyTFxbHkA7eB2L1uA36d.VaXzs/e'; //123456
$API_LINK = 'http://localhost/6001_server/api_server/'; 	//must end with slash
//MUST BE CHANGED IN PRODUCTION ----------------------------------------------

//------------------------------------------------
//THEME COLORS
$DARK_COLOR = "#7B6231";
$LIGHT_COLOR = "#424242";
$ONDARK_COLOR = "#FFFFFF";
$ONLIGHT_COLOR = "#FFFFFF";
//-------------------------------------------------

//default app timezone
$defaultTimeZone = "Asia/Dubai";
//default app language
$lang = 'en';
//DB connection
$connect_db = true;
//multi language
$multi_lang = true;
$falseToken = 'Fea85a4-ae4c-l81d-sea5-ef8bcf9599e8';
$MAP_API_KEY = '';


$mailSmtpHost = 'mail.ex.com';
$mailUserName = 'noreply@ex.com';
$mailUserPass = 'pass';
$mailUserPort = 587;

date_default_timezone_set($defaultTimeZone);

$USER_ID = 0;
$USER_NAME = "";
$EMAIL = "";
$LEVEL = "";





define('base_url', '');
define('images_root', base_url . "assets/images/");
define('assets_root', base_url . "assets/");
define('api_root', base_url . "app_api/");
define('uploads_root', base_url . "uploads/");


define('FIRSTKEY', 'O3JZqWExT0IG53Rm3msZArQ6uZ8giI9PTPDdY1aaV3I==');
define('SECONDKEY', 'MQ8S3Nl0J4/IrkgGLmFOLRMj/V9u7t8+yBvrPq18iov44KYLuxncc4dO8MWo7bGiL3Ruru99+UhlXKARyaXf1g==');





if ($multi_lang == true) {
	$cookie_name = "s1_lang";


	if (isset($_COOKIE[$cookie_name])) {
		$lang = $_COOKIE[$cookie_name];
	} else {
		$lang = 'en';
	}

	if (isset($_GET['nw_lang'])) {
		$nw_lang = $_GET['nw_lang'];

		switch ($nw_lang) {
			case 'en':
				$lang = 'en';
				break;
			case 'ar':
				$lang = 'ar';
				break;
			default:
				$lang = 'en';
				break;
		}

		$cookie_value = $lang;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

	}
}









if ($error_report == true) {
	error_reporting(E_ALL);
} else {
	error_reporting(0);
}



//Load language settings
require_once('lang.php');

//Load DataBase settings
require_once('app_db.php');

//Load App Main Functions
require_once('app_functions.php');

//Load App Vault
require_once('app_vault.php');

