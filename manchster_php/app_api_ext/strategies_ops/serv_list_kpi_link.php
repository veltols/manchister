<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "linked_kpi_id";
$tableName = "m_operational_projects_kpis";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$project_id = 0;

if (isset($_REQUEST['project_id'])) {
	$project_id = (int) test_inputs($_REQUEST['project_id']);
}

if (isset($_REQUEST['currentPage'])) {
	$currentPage = (int) test_inputs($_REQUEST['currentPage']);
}
if (isset($_REQUEST['recordsPerPage'])) {
	$recordsPerPage = (int) test_inputs($_REQUEST['recordsPerPage']);
	if ($recordsPerPage == 0) {
		$recordsPerPage = 10;
	}
}
$recordsPerPage = 1000;

if ($IS_LOGGED == true && $USER_ID != 0 && $project_id != 0) {

	


	$COND = $COND . "(`$tableName`.`project_id` = $project_id) AND ";

	$totalPages = 0;
	$totalRecords = 0;
	$qu_dataQ_sel = "SELECT COUNT(`$primaryKey`) FROM `$tableName` WHERE ( $COND (1=1) )";
	$qu_dataQ_EXE = mysqli_query($KONN, $qu_dataQ_sel);
	if (mysqli_num_rows($qu_dataQ_EXE)) {
		$dataQ_DATA = mysqli_fetch_array($qu_dataQ_EXE);
		$totalRecords = (int) $dataQ_DATA[0];
	}

	$totalPages = ceil($totalRecords / $recordsPerPage);

	$start = 0;
	if ($currentPage) {
		$start = ($currentPage - 1) * $recordsPerPage;
	}



	$qu_table_related_sel = "SELECT `$tableName`.*, 
	
									`m_strategic_plans`.`plan_title` AS `plan_title`,
									`m_strategic_plans_themes`.`theme_title` AS `theme_title`,
									`m_strategic_plans_objectives`.`objective_title` AS `objective_title`,
									`m_strategic_plans_kpis`.`kpi_title` AS `kpi_title`
									
									
							FROM  `$tableName`
							
							INNER JOIN `m_strategic_plans` ON `$tableName`.`plan_id` = `m_strategic_plans`.`plan_id` 
							INNER JOIN `m_strategic_plans_themes` ON `$tableName`.`theme_id` = `m_strategic_plans_themes`.`theme_id` 
							INNER JOIN `m_strategic_plans_objectives` ON `$tableName`.`objective_id` = `m_strategic_plans_objectives`.`objective_id` 
							INNER JOIN `m_strategic_plans_kpis` ON `$tableName`.`kpi_id` = `m_strategic_plans_kpis`.`kpi_id` 
							
							
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"plan_title" => $ARRAY_SRC['plan_title'],
					"theme_title" => $ARRAY_SRC['theme_title'],
					"objective_title" => $ARRAY_SRC['objective_title'],
					"kpi_title" => $ARRAY_SRC['kpi_title'] 
				)
			);
		}

	} else {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['error'] = mysqli_error($KONN);
	}




} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



