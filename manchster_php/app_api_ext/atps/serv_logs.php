<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "log_id";
$tableName = "atps_list_logs";
$atp_id = 0;


if (isset($_REQUEST['atp_id'])) {
	$atp_id = (int) test_inputs($_REQUEST['atp_id']);
}


if ($IS_LOGGED == true && $USER_ID != 0) {



	$qu_table_related_sel = "SELECT * FROM  `$tableName` 
							WHERE ( ( `atp_id` = $atp_id ) AND ( `log_dept` = 'R&C' ) ) ORDER BY `$primaryKey` DESC";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			$logged_by = "";
			$thsTyper = "" . $ARRAY_SRC['logger_type'];
			$thsIdder = (int) $ARRAY_SRC['logged_by'];

			if ($thsTyper == 'employees_list') {
				//get employee name
				$qu_employees_list_sel = "SELECT CONCAT(`first_name`, ' ', `last_name`) AS `employee_name` FROM  `employees_list` WHERE `employee_id` = $thsIdder";
				$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
				if (mysqli_num_rows($qu_employees_list_EXE)) {
					$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
					$logged_by = $employees_list_DATA['employee_name'] . ' ( IQC )';
				}
			} else if ($thsTyper == 'atps_list') {

				$qu_atps_list_sel = "SELECT `atp_name` FROM  `atps_list` WHERE `atp_id` = $thsIdder";
				$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
				if (mysqli_num_rows($qu_atps_list_EXE)) {
					$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
					$logged_by = $atps_list_DATA['atp_name'];
				}
			}


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



