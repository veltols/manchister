<?php


if ($item_id != 0) {


	
	//check if mapped
	$qu_m_strategic_plans_internal_maps_sel = "SELECT * FROM  `m_strategic_plans_internal_maps` WHERE `objective_id` = $item_id";
	$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
	$m_strategic_plans_internal_maps_DATA;
	if( mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE) > 0 ){
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "Objective is mapped, please delete the map first";
		
	} else {
		//remove all milestones
		$qu_m_strategic_plans_milestones_del = "DELETE FROM `m_strategic_plans_milestones` WHERE `objective_id` = $item_id";
		if (mysqli_query($KONN, $qu_m_strategic_plans_milestones_del)) {


			//remove all kpis
			$qu_m_strategic_plans_kpis_del = "DELETE FROM `m_strategic_plans_kpis` WHERE `objective_id` = $item_id";
			if (mysqli_query($KONN, $qu_m_strategic_plans_kpis_del)) {

				//remove all objectives
				$qu_m_strategic_plans_objectives_del = "DELETE FROM `m_strategic_plans_objectives` WHERE `objective_id` = $item_id";
				if (mysqli_query($KONN, $qu_m_strategic_plans_objectives_del)) {

					$IAM_ARRAY['success'] = true;
					$IAM_ARRAY['message'] = "All Good";


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-GOAL-8898";
				}
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-GOAL-23232";
			}



		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-GOAL-2223";
		}
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-541642323";
}



