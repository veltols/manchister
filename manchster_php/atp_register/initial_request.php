<?php



$page_title = $page_description = $page_keywords = $page_author = lang("Accreditation");
$pageId = 200;
$subPageId = 100;
include("app/assets.php");

$formName = 'initial_form';
if( isset( $_GET['form'] ) ){
    $formName = "".test_inputs( $_GET['form'] );
}


		$atp_logo = "";
		$atp_ref = "";
		$atp_name = "";
		$atp_name_ar = "";
		$contact_name = "";
		$atp_email = "";
		$atp_phone = "";
		$emirate_id = 0;
		$area_name = "";
		$street_name = "";
		$building_name = "";
		$added_by = 0;
		$added_date = "";
		$atp_category_id = 0;
		$atp_type_id = 0;
		$atp_status_id = 0;
		$phase_id = 0;
		$is_phase_ok = 0;
		$todo_id = 0;
	$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $ATP_ID";
	$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
	if(mysqli_num_rows($qu_atps_list_EXE)){
		$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
		
		$atp_logo = "".$atps_list_DATA['atp_logo'];
		$atp_ref = "".$atps_list_DATA['atp_ref'];
		$atp_name = "".$atps_list_DATA['atp_name'];
		$atp_name_ar = "".$atps_list_DATA['atp_name_ar'];
		$contact_name = "".$atps_list_DATA['contact_name'];
		$atp_email = "".$atps_list_DATA['atp_email'];
		$atp_phone = "".$atps_list_DATA['atp_phone'];
		$emirate_id = ( int ) $atps_list_DATA['emirate_id'];
		/*
		$area_name = "".$atps_list_DATA['area_name'];
		$street_name = "".$atps_list_DATA['street_name'];
		$building_name = "".$atps_list_DATA['building_name'];
*/
		$added_by = ( int ) $atps_list_DATA['added_by'];
		$added_date = "".$atps_list_DATA['added_date'];
		$atp_category_id = ( int ) $atps_list_DATA['atp_category_id'];
		$atp_type_id = ( int ) $atps_list_DATA['atp_type_id'];
		$atp_status_id = ( int ) $atps_list_DATA['atp_status_id'];
		$phase_id = ( int ) $atps_list_DATA['phase_id'];
		$is_phase_ok = ( int ) $atps_list_DATA['is_phase_ok'];
		$todo_id = ( int ) $atps_list_DATA['todo_id'];
	}




include_once('initial_request/'.$formName.'.php');
?>


