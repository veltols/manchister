<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "log_id";
$tableName = "tasks_list_logs";
$task_id = 0;


if (isset($_REQUEST['task_id'])) {
	$task_id = (int) test_inputs($_REQUEST['task_id']);
}


if ($IS_LOGGED == true && $USER_ID != 0) {



	$qu_table_related_sel = "SELECT `$tableName`.* , 
							CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `logged_by` 
							FROM  `$tableName`
							INNER JOIN `employees_list` ON `$tableName`.`logged_by` = `employees_list`.`employee_id` 
							WHERE ( ( `task_id` = $task_id ) ) ORDER BY `$primaryKey` DESC";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			$logged_by = "" . $ARRAY_SRC['logged_by'];



			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"log_action" => decryptData($ARRAY_SRC['log_action']),
					"log_date" => decryptData($ARRAY_SRC['log_date']),
					"logged_by" => $logged_by
				)
			);
		}

	} else {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['error'] = mysqli_error($KONN);
	}




} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));



