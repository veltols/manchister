<?php

function getTotals($tblPK, $tblNM, $COND){
	global $KONN;
	$qu_QS_sel = "SELECT COUNT(`$tblPK`) FROM  `$tblNM` WHERE  ( $COND (1=1) )";
	$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
	if (mysqli_num_rows($qu_QS_EXE)) {
		$QS_DATA = mysqli_fetch_array($qu_QS_EXE);
		return (int) $QS_DATA[0];
	} else {
		return 0;
	}
}

$page_title = $page_description = $page_keywords = $page_author = lang("View_Project_Details");


$addThemeController = $POINTER . 'ext/add_strategies_theme';
$addObjectiveController = $POINTER . 'ext/add_strategies_objectives';
$addKPIController = $POINTER . 'ext/add_strategies_kpis';
$addMilestoneController = $POINTER . 'ext/add_project_milestone';

$linkProjectKpiController = $POINTER . 'ext/link_project_kpi';

$addExternalMapController = $POINTER . 'ext/add_external_map';




$submitBtn = lang("Save_Project", "AAR");
$isNewForm = true;

$project_id = 0;

if (!isset($_GET['project_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$project_id = (int) test_inputs($_GET['project_id']);

if ($project_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}

$pageId = 10003;
$subPageId = 200;
include("app/assets.php");




$project_ref = "";
$project_code = "";
$project_name = "";
$project_description = "";
$project_start_date = "";
$project_end_date = "";
$project_period = "";
$project_analysis = "";
$project_recommendations = "";
$plan_id = 0;
$department_id = 0;
$project_status_id = 0;
$added_date = "";
$added_by = 0;
$qu_m_operational_projects_sel = "SELECT * FROM  `m_operational_projects` WHERE `project_id` = $project_id";
$qu_m_operational_projects_EXE = mysqli_query($KONN, $qu_m_operational_projects_sel);

if(mysqli_num_rows($qu_m_operational_projects_EXE)){
	$m_operational_projects_DATA = mysqli_fetch_assoc($qu_m_operational_projects_EXE);
	$project_id = ( int ) $m_operational_projects_DATA['project_id'];
	$project_ref = $m_operational_projects_DATA['project_ref'];
	$project_code = $m_operational_projects_DATA['project_code'];
	$project_name = $m_operational_projects_DATA['project_name'];
	$project_description = $m_operational_projects_DATA['project_description'];
	$project_start_date = $m_operational_projects_DATA['project_start_date'];
	$project_end_date = $m_operational_projects_DATA['project_end_date'];
	$project_period = $m_operational_projects_DATA['project_period'];
	$project_analysis = $m_operational_projects_DATA['project_analysis'];
	$project_recommendations = $m_operational_projects_DATA['project_recommendations'];
	$plan_id = ( int ) $m_operational_projects_DATA['plan_id'];
	$department_id = ( int ) $m_operational_projects_DATA['department_id'];
	$project_status_id = ( int ) $m_operational_projects_DATA['project_status_id'];
	$added_date = $m_operational_projects_DATA['added_date'];
	$added_by = ( int ) $m_operational_projects_DATA['added_by'];
}


		$plan_ref = "";
		$plan_title = "";
		$plan_vision = "";
		$plan_mission = "";
		$plan_values = "";
		$plan_period = "";
		$plan_from = 0;
		$plan_to = 0;
		$plan_level = 0;
		$plan_status_id = 0;
		$added_by = 0;
		$added_date = "";
		$is_published = 0;
	$qu_m_strategic_plans_sel = "SELECT * FROM  `m_strategic_plans` WHERE `plan_id` = $plan_id";
	$qu_m_strategic_plans_EXE = mysqli_query($KONN, $qu_m_strategic_plans_sel);
	$m_strategic_plans_DATA;
	if(mysqli_num_rows($qu_m_strategic_plans_EXE)){
		$m_strategic_plans_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_EXE);
		$plan_id = ( int ) $m_strategic_plans_DATA['plan_id'];
		$plan_ref = $m_strategic_plans_DATA['plan_ref'];
		$plan_title = $m_strategic_plans_DATA['plan_title'];
		$plan_vision = $m_strategic_plans_DATA['plan_vision'];
		$plan_mission = $m_strategic_plans_DATA['plan_mission'];
		$plan_values = $m_strategic_plans_DATA['plan_values'];
		$plan_period = $m_strategic_plans_DATA['plan_period'];
		$plan_from = ( int ) $m_strategic_plans_DATA['plan_from'];
		$plan_to = ( int ) $m_strategic_plans_DATA['plan_to'];
		$plan_level = ( int ) $m_strategic_plans_DATA['plan_level'];
		$plan_status_id = ( int ) $m_strategic_plans_DATA['plan_status_id'];
		$added_by = ( int ) $m_strategic_plans_DATA['added_by'];
		$added_date = $m_strategic_plans_DATA['added_date'];
		$is_published = ( int ) $m_strategic_plans_DATA['is_published'];
	}



$department_name = "";
$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $plan_level";
$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
$employees_list_departments_DATA;
if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
	$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
	$department_name = $employees_list_departments_DATA['department_name'];
}

//sssssssssssssssssssssssssssssssss

if( $project_status_id == 1 ){
	include_once("projects_view_draft.php");
} else if( $project_status_id == 2 ) {
	include_once("projects_view_overview.php");
}

?>
