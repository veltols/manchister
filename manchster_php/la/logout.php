<?php
		$_SESSION['token_value']  = "";
		$_SESSION['user_type']    = "";
		$_SESSION['user_id']      = 0;
		$_SESSION['user_name']    = "";
		$_SESSION['user_email']   = "";
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['token']   = $falseToken;
		$_SESSION['user_picture'] = "";
		
		session_destroy();
		header("location:../login");
?>