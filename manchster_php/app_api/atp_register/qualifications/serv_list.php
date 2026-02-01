<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "qualification_id";
$tableName = "atps_list_qualifications";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$extra_id = 0;

if (isset($_REQUEST['currentPage'])) {
	$currentPage = (int) test_inputs($_REQUEST['currentPage']);
}
if (isset($_REQUEST['recordsPerPage'])) {
	$recordsPerPage = (int) test_inputs($_REQUEST['recordsPerPage']);
	if ($recordsPerPage == 0) {
		$recordsPerPage = 10;
	}
}

if ($IS_LOGGED == true && $USER_ID != 0) {


	$COND = $COND . "(`$tableName`.`atp_id` = $USER_ID) AND ";



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


/*
	$qu_table_related_sel = "SELECT `$tableName`.*, 
									`z_assets_list_cats`.`category_name` AS `category_name`
									
							FROM  `$tableName` 
							INNER JOIN `z_assets_list_cats` ON `$tableName`.`category_id` = `z_assets_list_cats`.`category_id` 
							
							WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";
*/
	$qu_table_related_sel = "SELECT `$tableName`.* FROM  `$tableName` WHERE ( $COND (1=1) ) ORDER BY `$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {
			$mapped_faculty = 'NA';

			$qu_atps_list_qualifications_faculties_sel = "SELECT `atps_list_qualifications_faculties`.*, 
															`atps_list_faculties`.`faculty_name` AS `faculty_name`
															FROM  `atps_list_qualifications_faculties` 
															LEFT JOIN `atps_list_faculties` ON `atps_list_qualifications_faculties`.`faculty_id` = `atps_list_faculties`.`faculty_id`
															WHERE ((`atps_list_qualifications_faculties`.`qualification_id` = $ARRAY_SRC[$primaryKey]) AND (`atps_list_qualifications_faculties`.`atp_id` = $USER_ID))";
			$qu_atps_list_qualifications_faculties_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_faculties_sel);
			if(mysqli_num_rows($qu_atps_list_qualifications_faculties_EXE)){
				$mapped_faculty = '';
				while($atps_list_qualifications_faculties_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_faculties_EXE)){
					$record_id = ( int ) $atps_list_qualifications_faculties_REC['record_id'];
					$qualification_id = ( int ) $atps_list_qualifications_faculties_REC['qualification_id'];
					$mapped_faculty = $mapped_faculty." - ".$atps_list_qualifications_faculties_REC['faculty_name'];
					$atp_id = ( int ) $atps_list_qualifications_faculties_REC['atp_id'];
					
				}
			}
		


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"qualification_type" => decryptData("" . $ARRAY_SRC['qualification_type']),
					"qualification_name" => decryptData("" . $ARRAY_SRC['qualification_name']),
					"qualification_category" => decryptData("" . $ARRAY_SRC['qualification_category']),
					"emirates_level" => decryptData("" . $ARRAY_SRC['emirates_level']),
					"qulaification_credits" => decryptData("" . $ARRAY_SRC['qulaification_credits']),
					"mode_of_delivery" => decryptData("" . $ARRAY_SRC['mode_of_delivery']),
					"mapped_faculty" => $mapped_faculty
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



