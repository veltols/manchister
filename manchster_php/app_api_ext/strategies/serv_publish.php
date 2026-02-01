<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_POST['plan_id'])
	) {



		$plan_id = 0;
		$plan_id = (int) test_inputs($_POST['plan_id']);

		if( $plan_id != 0 ){
			//count local mappings

			$totLocalMaps = 0;
			$qu_selChk_sel = "SELECT COUNT(`objective_id`) FROM  `m_strategic_plans_objectives` WHERE `plan_id` = $plan_id";
			$qu_selChk_EXE = mysqli_query($KONN, $qu_selChk_sel);
			$selChk_DATA;
			if(mysqli_num_rows($qu_selChk_EXE)){
				$selChk_DATA = mysqli_fetch_array($qu_selChk_EXE);
				$totLocalMaps = ( int ) $selChk_DATA[0];
			}

			//count total milestones
			$totalMilestones = 0;
			$qu_m_strategic_plans_kpis_sel = "SELECT COUNT(`kpi_id`) FROM  `m_strategic_plans_kpis` WHERE `plan_id` = $plan_id";
			$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
			if(mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)){
				$m_strategic_plans_kpis_DATA = mysqli_fetch_array($qu_m_strategic_plans_kpis_EXE);
				$totalMilestones = ( int ) $m_strategic_plans_kpis_DATA[0];
			}
	

			if( $totLocalMaps == 0 ){
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Your plan has no Objectives, please add objectives and try publishing the plan again";
			} else {

				if( $totalMilestones == 0 ){
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Not all of your objectives are mapped to thier KPI, please check your Objectives KPIs";
				} else {

					//TODO
					//check if there is unwighted objectives

					//publish plan
					
					$qu_m_strategic_plans_updt = "UPDATE  `m_strategic_plans` SET 
										`plan_status_id` = '2', 
										`is_published` = '1'
										WHERE `plan_id` = $plan_id;";

					if(mysqli_query($KONN, $qu_m_strategic_plans_updt)){
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "All Good";
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error During Update, Error-29872";
					}




				}
				

			}
			



		} else {
			//No request
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Error in plan";
		}
		
	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


