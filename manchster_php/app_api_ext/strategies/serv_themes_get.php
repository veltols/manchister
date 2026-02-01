<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "theme_id";
$tableName = "m_strategic_plans_themes";
$currentPage = 1;
$recordsPerPage = 100;
$COND = "";
$theme_id = 0;
if (isset($_REQUEST['theme_id'])) {
	$theme_id = (int) test_inputs($_REQUEST['theme_id']);
}



if ($IS_LOGGED == true && $USER_ID != 0 && $theme_id != 0) {


	$COND = $COND . "((`$tableName`.`theme_id` = $theme_id) ) AND ";



	$qu_table_related_sel = "SELECT `$tableName`.* FROM  `$tableName` WHERE ( $COND (1=1) ) LIMIT 1";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"theme_ref" => $ARRAY_SRC['theme_ref'],
					"theme_title" => $ARRAY_SRC['theme_title'],
					"theme_description" => $ARRAY_SRC['theme_description'],
					"order_no" => $ARRAY_SRC['order_no'],
					"theme_weight" => (int) $ARRAY_SRC['theme_weight'],
					"plan_id" => $ARRAY_SRC['plan_id'],
					"added_by" => $ARRAY_SRC['added_by'],
					"added_date" => $ARRAY_SRC['added_date']
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



