<?php


if ($item_id != 0) {


	//remove all milestones
	$qu_m_strategic_plans_milestones_del = "DELETE FROM `m_operational_projects_kpis` WHERE `linked_kpi_id` = $item_id";
	if (mysqli_query($KONN, $qu_m_strategic_plans_milestones_del)) {


		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['message'] = "All Good";


	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-TACT-2223";
	}








} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-541642323";
}



