<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "chat_id";
$tableName = "z_messages_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$isCom = 0;

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

	if (isset($_REQUEST['query_srch'])) {
		$query = "" . test_inputs($_REQUEST['query_srch']);
		if ($query != "") {
			$COND = $COND . " (  ( `q_text` LIKE '%$query%' ) ) AND ";
		}
	}


	$COND = $COND . "( (`$tableName`.`is_archieve` = 0) AND (`$tableName`.`is_deleted` = 0) ) AND ";
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

	$COND = $COND . "( (`$tableName`.`a_id` = $USER_ID) OR (`$tableName`.`b_id` = $USER_ID) ) AND ";



	$qu_table_related_sel = "SELECT `$tableName`.*, 
									CONCAT(`aa`.`first_name`, ' ', `aa`.`last_name`) AS `a_name`,  
									`aa`.`employee_picture` AS `a_picture`, 
									`aa`.`emp_status_id` AS `a_status_id`, 
									CONCAT(`bb`.`first_name`, ' ', `bb`.`last_name`) AS `b_name`,  
									`bb`.`employee_picture` AS `b_picture`, 
									`bb`.`emp_status_id` AS `b_status_id` 
							FROM  `$tableName` 
							INNER JOIN `employees_list` `aa` ON `$tableName`.`a_id` = `aa`.`employee_id` 
							INNER JOIN `employees_list` `bb` ON `$tableName`.`b_id` = `bb`.`employee_id` 
							WHERE ( $COND (1=1) ) ORDER BY `$tableName`.`$primaryKey` ASC LIMIT $start,$recordsPerPage";



	$qu_table_related_EXE = mysqli_query($KONN, $qu_table_related_sel);
	if (mysqli_num_rows($qu_table_related_EXE)) {
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['totalPages'] = $totalPages;
		$IAM_ARRAY['data'] = [];
		while ($ARRAY_SRC = mysqli_fetch_assoc($qu_table_related_EXE)) {

			$chat_id = (int) $ARRAY_SRC['chat_id'];
			$a_id = (int) $ARRAY_SRC['a_id'];
			$b_id = (int) $ARRAY_SRC['b_id'];

			$idder = 0;
			$namer = '';
			$picer = '';
			$initier = '';
			$otherUserStatus = 0;
			if ($USER_ID == $a_id) {
				//its A, view B
				$idder = $b_id;
				$namer = "" . $ARRAY_SRC['b_name'];
				$picer = "" . $ARRAY_SRC['b_picture'];
				$initier = getInitials("" . $ARRAY_SRC['b_name']);
				$otherUserStatus = (int) $ARRAY_SRC['b_status_id'];
			} else if ($USER_ID == $b_id) {
				//its B, View A
				$idder = $a_id;
				$namer = "" . $ARRAY_SRC['a_name'];
				$picer = "" . $ARRAY_SRC['a_picture'];
				$initier = getInitials("" . $ARRAY_SRC['a_name']);
				$otherUserStatus = (int) $ARRAY_SRC['a_status_id'];
			}

			$totalUnRead = 0;
			$qu_z_messages_list_posts_sel = "SELECT COUNT(`post_id`) FROM  `z_messages_list_posts` WHERE ((`added_by` <> $USER_ID) AND (`chat_id` = $chat_id) AND (`is_read` = 0))";
			$qu_z_messages_list_posts_EXE = mysqli_query($KONN, $qu_z_messages_list_posts_sel);
			if (mysqli_num_rows($qu_z_messages_list_posts_EXE)) {
				$z_messages_list_posts_DATA = mysqli_fetch_array($qu_z_messages_list_posts_EXE);
				$totalUnRead = (int) $z_messages_list_posts_DATA[0];
			}


			$staus_name = "";
			$qu_employees_list_staus_sel = "SELECT `staus_name` FROM  `employees_list_staus` WHERE `staus_id` = $otherUserStatus";
			$qu_employees_list_staus_EXE = mysqli_query($KONN, $qu_employees_list_staus_sel);
			if (mysqli_num_rows($qu_employees_list_staus_EXE)) {
				$employees_list_staus_DATA = mysqli_fetch_assoc($qu_employees_list_staus_EXE);
				$staus_name = $employees_list_staus_DATA['staus_name'];
			}
			if ($otherUserStatus == 4) {
				$staus_name = 'Offline';
			}

			array_push(
				$IAM_ARRAY['data'],
				array(
					"$primaryKey" => $ARRAY_SRC[$primaryKey],
					"total_unread" => $totalUnRead,
					"b_id" => $idder,
					"b_name" => "" . $namer,
					"b_picture" => "" . $picer,
					"b_initials" => $initier,
					"other_user_status" => $staus_name,
					"added_by" => (int) $ARRAY_SRC['added_by'],
					"added_date" => "" . $ARRAY_SRC['added_date']
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



?>