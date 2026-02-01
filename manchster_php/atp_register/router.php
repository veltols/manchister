<?php

	$portalDirectory = 'iqc';
	require_once('../bootstrap/app_config.php');
	$thsType = 'atp_register';
	require_once('../bootstrap/check_log_atp.php');
	
//get lang and theme
$USER_LANG = 'en';
$USER_THEME_ID = 1;

$qu_users_list_sel = "SELECT `user_lang`, `user_theme_id` FROM  `users_list` WHERE ((`user_id` = $ATP_ID) AND (`user_family` = 'atps_list'))";
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
        
        $actual_path = "".substr($url_requested,strpos($url_requested,'/'),$url_len); 
		
		$mainDomain = "/$portalDirectory/atp_register/";
		
		
		if( $Running_Environment == "server" ){
			$mainDomain = "/atp_register/";
		}
		
		
		
		
		$actual_path = str_replace('"', "", $actual_path);
		$actual_path = str_replace("'", "", $actual_path);
		$actual_path = str_replace($mainDomain, "",$actual_path);
		
		
		
		$actual_pathArr = explode("?", $actual_path);
		$actual_path = $actual_pathArr[0];
		
		
		$POINTER = '';
		
		$t = 0;
		if( $t == 1 ){
			echo $mainDomain.'<br>';
			echo $actual_path;
			die();
		}
		
		
		$route_path    = "";
		$route_type    = "";
		
		$qu_local_routes_sel = "SELECT * FROM  `local_routes_atp_reg` WHERE (`route_src` = '$actual_path')";
		$qu_local_routes_EXE = mysqli_query($KONN, $qu_local_routes_sel);
		if(mysqli_num_rows($qu_local_routes_EXE)){
			$local_routes_DATA = mysqli_fetch_assoc($qu_local_routes_EXE);
			$route_family      = "".$local_routes_DATA['route_family'];
			$route_type        = "".$local_routes_DATA['route_type'];
			$POINTER           = "".$local_routes_DATA['route_pointer'];

			$route_path = ''.$local_routes_DATA['route_path'];
			if( strtolower($route_type) == 'api' ){
				$route_path = "app_api/".$thsType."/".$route_family."/".$local_routes_DATA['route_path'];
			} else if( strtolower($route_type) == 'select' ){
				$route_path = "app_api/".$route_family."/".$local_routes_DATA['route_path'];
			}
		} else {
			$route_path = '';
        }
		

//init main vars
		$UPLOADS_DIRECTORY = $POINTER.'../uploads/';


//init directories
		$DIR_dashboard   = $POINTER.'dashboard/';

		$DIR_reg_req_form = $POINTER.'app_init/req_reg_form/';
		$DIR_init_register_request = $POINTER.'app_init/init_register_request/';
		$DIR_Self_assessment = $POINTER.'app_init/self_assess/';

		$DIR_profile     = $POINTER.'profile/';
		$DIR_settings    = $POINTER.'settings/';
		$DIR_logout      = $POINTER.'logout/';

		
		if( $route_path != "" ){
			$thsPnt = '';
			
				//normal local route
				if( $route_type  == "API" || $route_type  == "select"){
					$thsPnt = '../';
				}
				
				include_once($thsPnt.$route_path);
				mysqli_close($KONN);
				die();
				
		} else {
			$POINTER = '';
			$page_title = "Error 404 not found!";
			//include_once('error.php');
			header("location:".$mainDomain."dashboard/");
			mysqli_close($KONN);
			die();
        }
		






