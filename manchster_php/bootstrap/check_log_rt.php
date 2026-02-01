<?php

	
	$IS_LOGGED = false;
	$USER_ID = 0;
	$TOKEN_VALUE     = '';
	$USER_TYPE       = '';
	$USER_NAME    = '';
	$USER_CODE        = '';
	$USER_EMAIL   = '';
	$USER_PICTURE = '';
	$TMIEZONE_NAME   = '';

	if( isset( $_SESSION['token_value'] ) && 
		isset( $_SESSION['user_code'] ) && 
		isset( $_SESSION['user_type'] ) && 
		isset( $_SESSION['user_id'] ) && 
		isset( $_SESSION['user_name'] ) && 
		isset( $_SESSION['user_picture'] ) && 
		isset( $_SESSION['user_picture'] ) && 
		isset( $_SESSION['user_email'] )
	){
		
		$IS_LOGGED    = true;
		$USER_ID      = ( int ) $_SESSION['user_id'];
		
		$USER_CODE       = ''.$_SESSION['user_code'];
		$TOKEN_VALUE     = ''.$_SESSION['token_value'];
		$USER_TYPE       = ''.$_SESSION['user_type'];
		$USER_NAME    = ''.$_SESSION['user_name'];
		$USER_EMAIL   = ''.$_SESSION['user_email'];
		$USER_PICTURE = ''.$_SESSION['user_picture'];
		$TMIEZONE_NAME   = ''.$_SESSION['tmiezone_name'];
		
		if( $USER_ID != -1 ){
			$IS_LOGGED = false;
		}
		if( $USER_TYPE != 'root' ){
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