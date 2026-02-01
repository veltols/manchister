<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['project_id'])) {

	$project_id = (int) test_inputs($_POST['project_id']);
	$qqq = "";

	if (isset($_POST['project_name'])) {
		$project_name = test_inputs($_POST['project_name']);
		$qqq = $qqq . "`project_name` = '" . $project_name . "', ";
	}
	if (isset($_POST['developer_name'])) {
		$developer_name = test_inputs($_POST['developer_name']);
		$qqq = $qqq . "`developer_name` = '" . $developer_name . "', ";
	}
	if (isset($_POST['project_status'])) {
		$project_status = test_inputs($_POST['project_status']);
		$qqq = $qqq . "`project_status` = '" . $project_status . "', ";
	}
	if (isset($_POST['handover_date'])) {
		$handover_date = test_inputs($_POST['handover_date']);
		$qqq = $qqq . "`handover_date` = '" . $handover_date . "', ";
	}
	if (isset($_POST['area_id'])) {
		$area_id = test_inputs($_POST['area_id']);
		$qqq = $qqq . "`area_id` = '" . $area_id . "', ";
	}

	if ($qqq != "") {
		$qqq = substr($qqq, 0, -2);

		$qu_portal_lists_areas_projects_updt = "UPDATE `portal_lists_areas_projects` SET " . $qqq . "  WHERE `project_id` = $project_id;";


		if (mysqli_query($KONN, $qu_portal_lists_areas_projects_updt)) {
			$IAM_ARRAY['success'] = true;
			$IAM_ARRAY['message'] = "succeed";
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-5675";
		}
	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-34345";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

