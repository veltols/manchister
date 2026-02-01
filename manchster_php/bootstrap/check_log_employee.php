<?php


$IS_LOGGED = false;
$USER_RECORD_ID = 0;
$USER_ID = 0;
$EMPLOYEE_ID = 0;
$USER_TYPE = '';
$EMPLOYEE_NAME = '';
$USER_CODE = '';
$EMPLOYEE_EMAIL = '';
$EMPLOYEE_PICTURE = '';
$TMIEZONE_NAME = '';
$EMP_STATUS_ID = '';

$IS_GROUP = 0;
$IS_COMMITTEE = 0;

if (
	isset($_SESSION['user_code']) &&
	isset($_SESSION['user_type']) &&
	isset($_SESSION['user_id']) &&
	isset($_SESSION['user_name']) &&
	isset($_SESSION['user_picture']) &&
	isset($_SESSION['user_email']) &&
	isset($_SESSION['tmiezone_name'])
) {

	$IS_LOGGED = true;
	$USER_RECORD_ID = $EMPLOYEE_ID = (int) $_SESSION['user_record_id'];
	$USER_ID = $EMPLOYEE_ID = (int) $_SESSION['user_id'];

	$USER_CODE = '' . $_SESSION['user_code'];
	$USER_TYPE = '' . $_SESSION['user_type'];
	$EMPLOYEE_NAME = '' . $_SESSION['user_name'];
	$EMPLOYEE_EMAIL = '' . $_SESSION['user_email'];
	$EMPLOYEE_PICTURE = '' . $_SESSION['user_picture'];
	$TMIEZONE_NAME = '' . $_SESSION['tmiezone_name'];
	$EMP_STATUS_ID = isset($_SESSION['emp_status_id']) ? (int) $_SESSION['emp_status_id'] : 1;
	if ($EMP_STATUS_ID == 0) {
		$EMP_STATUS_ID = 1;
	}
	$IS_GROUP = (int) $_SESSION['is_group'];
	$IS_COMMITTEE = (int) $_SESSION['is_committee'];

	if ($USER_ID == 0) {
		$IS_LOGGED = false;
	}
	if ($USER_TYPE != $thsType) {
		$IS_LOGGED = false;
	}
	if ($TMIEZONE_NAME != '') {
		date_default_timezone_set($TMIEZONE_NAME);
	}

} else {
	$IS_LOGGED = false;
}


if ($IS_LOGGED == false) {
	header("location:../login?err=2");
	die();
}

