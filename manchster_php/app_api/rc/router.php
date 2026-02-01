<?php

	$portalDirectory = '6001';
	require_once('../bootstrap/app_config.php');
	$thsType = 'rc';
	require_once('../bootstrap/check_log_employee.php');
	$page_title = "";
	$COMPANY_LOGO = "logo_hor.png";
	
        $url_requested = $_SERVER['REQUEST_URI'];
        $url_len = strlen($url_requested);
        
        $actual_path = "".substr($url_requested,strpos($url_requested,'/'),$url_len); 
		
		$mainDomain = "/$portalDirectory/rc/";
		
		
		if( $Running_Environment == "server" ){
			$mainDomain = "/rc/";
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
		
		$qu_local_routes_sel = "SELECT * FROM  `local_routes_rc` WHERE (`route_src` = '$actual_path')";
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
		$DIR_dashboard  = $POINTER.'dashboard/';
		$DIR_atps       = $POINTER.'atps/list/';
		$DIR_profile    = $POINTER.'profile/';
		$DIR_settings   = $POINTER.'settings/';
		$DIR_logout     = $POINTER.'logout/';
		
		if( $route_path != "" ){
			$thsPnt = '';
			
				//normal local route
				if( $route_type  == "API" || $route_type  == "select"){
					$thsPnt = '../';
				}
				
				include_once($thsPnt.$route_path);
				mysqli_close($KONN);
				die();

			/*
			if( $route_type  == "select"){
				
				//remote route
				
				$reqDt = array(
					'lang' => $lang,
					'lang_db' => $lang_db
				);
				
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_POST, 1);
				if( $reqDt ){
					curl_setopt($curl, CURLOPT_POSTFIELDS, $reqDt);
				}
				
				
				// Optional Authentication:
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_USERPWD, "username:password");
			
				curl_setopt($curl, CURLOPT_URL, $route_path);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
				$result = curl_exec($curl);
				
				if (curl_errno($curl)) {
					$error_msg = curl_error($curl);
					//echo $error_msg;
					echo "Error - 999";
					//todo
					//error page
			
				}

				curl_close($curl);
				echo $result;







				




				
			} else {

			}
			*/
		} else {
			$POINTER = '';
			$page_title = "Error 404 not found!";
			//include_once('error.php');
			header("location:".$mainDomain."dashboard/");
			mysqli_close($KONN);
			die();
        }
		






