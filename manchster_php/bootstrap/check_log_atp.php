<?php

	
	$IS_LOGGED = false;
	$USER_ID = 0;
	$ATP_ID = 0;
	$USER_TYPE       = '';
	$ATP_NAME    = '';
	$USER_CODE        = '';
	$ATP_EMAIL   = '';
	$ATP_PICTURE = '';
	$TMIEZONE_NAME   = '';
	
	if( isset( $_SESSION['user_code'] ) && 
		isset( $_SESSION['user_type'] ) && 
		isset( $_SESSION['user_id'] ) && 
		isset( $_SESSION['user_name'] ) && 
		isset( $_SESSION['user_picture'] ) && 
		isset( $_SESSION['user_email'] ) && 
		isset( $_SESSION['tmiezone_name'] )
	){
		
		$IS_LOGGED    = true;
		$USER_ID = $ATP_ID = ( int ) $_SESSION['user_id'];
		
		$USER_CODE       = ''.$_SESSION['user_code'];
		$USER_TYPE       = ''.$_SESSION['user_type'];
		$ATP_NAME    = ''.$_SESSION['user_name'];
		$ATP_EMAIL   = ''.$_SESSION['user_email'];
		$ATP_PICTURE = ''.$_SESSION['user_picture'];
		$TMIEZONE_NAME    = ''.$_SESSION['tmiezone_name'];

		if( $USER_ID == 0 ){
			$IS_LOGGED = false;
		}
		if( $USER_TYPE != $thsType ){
			$IS_LOGGED = false;
		}
		if( $TMIEZONE_NAME != '' ){
			date_default_timezone_set($TMIEZONE_NAME);
		}
		
	} else {
		$IS_LOGGED = false;
	}
	
	
	if( $IS_LOGGED == false ){
		header("location:../login");
		die();
	}
	
	
?>