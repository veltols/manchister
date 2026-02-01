<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "post_id";
$tableName = "z_messages_list_posts";
$currentPage = 1;
$recordsPerPage = 1000;
$COND = "";
$chat_id = 0;

if (isset($_REQUEST['chat_id'])) {
	$chat_id = (int) test_inputs($_REQUEST['chat_id']);
}
if (isset($_REQUEST['currentPage'])) {
	$currentPage = (int) test_inputs($_REQUEST['currentPage']);
}
if (isset($_REQUEST['recordsPerPage'])) {
	$recordsPerPage = (int) test_inputs($_REQUEST['recordsPerPage']);
	if ($recordsPerPage == 0) {
		$recordsPerPage = 1000;
	}
}

if ($IS_LOGGED == true && $USER_ID != 0 && $chat_id != 0) {

	if (isset($_REQUEST['query_srch'])) {
		$query = "" . test_inputs($_REQUEST['query_srch']);
		if ($query != "") {
			$COND = $COND . " (  ( `q_text` LIKE '%$query%' ) ) AND ";
		}
	}

	$COND = $COND . "( (`$tableName`.`chat_id` = $chat_id) ) AND ";



	//$COND = $COND . "( (`$tableName`.`is_archieve` = 0) AND (`$tableName`.`is_deleted` = 0) ) AND ";
	$totalPages = 0;
	$totalRecords = 0;
	$qu_dataQ_sel = "SELECT COUNT(`$tableName`.`$primaryKey`) FROM `$tableName` WHERE ( $COND (1=1) )";
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
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `sender_name`,  
									`employees_list`.`employee_picture` AS `b_picture`
							FROM  `$tableName` 
							INNER JOIN `employees_list` ON `$tableName`.`added_by` = `employees_list`.`employee_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$tableName`.`$primaryKey` DESC LIMIT $start,$recordsPerPage";


	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {


			$timeAgo = timeAgo("" . $ARRAY_SRC['added_date']);
			$post_type = "" . $ARRAY_SRC['post_type'];

			$post_file_name = "";
			$post_file_path = "";
			if ($post_type != 'text') {
				$post_attachment_id = (INT) $ARRAY_SRC['post_attachment_id'];
				if ($post_attachment_id != 0) {
					//get related file
					$qu_z_messages_list_files_sel = "SELECT * FROM  `z_messages_list_files` WHERE ((`chat_id` = $chat_id) AND (`file_id` = $post_attachment_id))";
					$qu_z_messages_list_files_EXE = mysqli_query($KONN, $qu_z_messages_list_files_sel);
					if (mysqli_num_rows($qu_z_messages_list_files_EXE)) {
						$z_messages_list_files_DATA = mysqli_fetch_assoc($qu_z_messages_list_files_EXE);
						$post_file_name = $z_messages_list_files_DATA['file_name'];
						$post_file_path = $z_messages_list_files_DATA['file_path'];
					}
				}
			}


			array_push(
				$IAM_ARRAY['data'],
				array(
					"post_id" => $ARRAY_SRC['post_id'],
					"sender_id" => decryptData("" . $ARRAY_SRC['added_by']),
					"sender_name" => decryptData("" . $ARRAY_SRC['sender_name']),
					"post_text" => decryptData("" . $ARRAY_SRC['post_text']),
					"post_type" => decryptData($ARRAY_SRC['post_type']),
					"post_file_name" => $post_file_name,
					"post_file_path" => $post_file_path,
					"added_date" => $timeAgo
				)
			);

		}

		$COND = $COND . "( (`$tableName`.`added_by` <> $USER_ID) ) AND ";
		$qu_z_messages_list_posts_updt = "UPDATE  `z_messages_list_posts` SET 
													`is_read` = '1'
													WHERE ( $COND (1=1) );";

		if (mysqli_query($KONN, $qu_z_messages_list_posts_updt)) {

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



?>