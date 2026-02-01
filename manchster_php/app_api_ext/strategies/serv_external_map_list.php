<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "record_id";
$tableName = "m_strategic_plans_external_maps";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$plan_id = 0;
if (isset($_REQUEST['plan_id'])) {
	$plan_id = (int) test_inputs($_REQUEST['plan_id']);
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

if ($IS_LOGGED == true && $USER_ID != 0 && $plan_id != 0) {

	


	$COND = $COND . "(`$tableName`.`plan_id` = $plan_id) AND ";

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
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_employee`,
									`m_strategic_plans`.`plan_title` AS `plan_title`,
									`m_strategic_plans_themes`.`theme_title` AS `theme_title`,
									`m_strategic_plans_objectives`.`objective_title` AS `objective_title`
									
									
							FROM  `$tableName`
							INNER JOIN `employees_list` `a` ON `$tableName`.`added_by` = `a`.`employee_id` 
							INNER JOIN `m_strategic_plans` ON `$tableName`.`plan_id` = `m_strategic_plans`.`plan_id` 
							INNER JOIN `m_strategic_plans_themes` ON `$tableName`.`theme_id` = `m_strategic_plans_themes`.`theme_id` 
							INNER JOIN `m_strategic_plans_objectives` ON `$tableName`.`objective_id` = `m_strategic_plans_objectives`.`objective_id` 
							
							
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			$stArr = explode(' ', "".$ARRAY_SRC['start_date']);
			$enArr = explode(' ', "".$ARRAY_SRC['end_date']);


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"added_employee" => $ARRAY_SRC['added_employee'],
					"plan_title" => $ARRAY_SRC['plan_title'],
					"theme_title" => $ARRAY_SRC['theme_title'],
					"objective_title" => $ARRAY_SRC['objective_title'],
					"external_entity_name" => $ARRAY_SRC['external_entity_name'],
					"map_description" => $ARRAY_SRC['map_description'],
					"start_date" => $stArr[0],
					"end_date" => $enArr[0],
					
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



