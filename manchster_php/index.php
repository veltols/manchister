<?php
require_once('bootstrap/app_config.php');







$page_title = "6001 - Login";

$url_requested = $_SERVER['REQUEST_URI'];
$url_len = strlen($url_requested);

$actual_path = substr($url_requested, strpos($url_requested, '/'), $url_len);

$mainDomain = "/iqc/";


if ($Running_Environment == "server") {
    $mainDomain = "/";
}



$actual_path = str_replace('"', "", $actual_path);
$actual_path = str_replace("'", "", $actual_path);


// die($actual_path );

if ($actual_path == $mainDomain . "login") {
    include_once('login.php');
} else if ($actual_path == $mainDomain . "get_auth_token") {
    include_once('app_api/auth/get_initial_token.php');
} else if ($actual_path == $mainDomain . "get_auth_user") {
    include_once('app_api/auth/auth_user.php');
} else {
    include_once('login.php');
}
