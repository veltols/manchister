<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "log_id";
$tableName = "sys_logs";

if ($IS_LOGGED == true && $USER_ID != 0) {

	if (
		isset($_REQUEST['related_table']) &&
		isset($_REQUEST['related_id'])
	) {
		$related_id = (int) test_inputs($_REQUEST['related_id']);
		$related_table = "" . test_inputs($_REQUEST['related_table']);

		if (
			$related_id != 0 &&
			$related_table != ''
		) {

			$qu_table_related_sel = "SELECT `$tableName`.* , 
			CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `logged_by_user` 
			FROM  `$tableName`
			INNER JOIN `employees_list` ON `$tableName`.`logged_by` = `employees_list`.`employee_id` 
			WHERE ( ( `$tableName`.`related_id` = $related_id ) AND ( `$tableName`.`related_table` = '$related_table' ) ) ORDER BY `$primaryKey` DESC";

			$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
			if (mysqli_num_rows($qu_table_related_EXE)) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['data'] = [];
				while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


					$logged_by = "" . $ARRAY_SRC['logged_by_user'];



					array_push(
						$IAM_ARRAY['data'],
						array(
							"$primaryKey" => $ARRAY_SRC[$primaryKey],
							"log_action" => decryptData($ARRAY_SRC['log_action']),
							"log_remark" => decryptData($ARRAY_SRC['log_remark']),
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
			$IAM_ARRAY['message'] = "ERR-123112";
		}



	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-11223";
	}





} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));