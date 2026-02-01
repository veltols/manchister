<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();


$COND = "";

$start = time();
$timeout = 25; // seconds
$poll_sleep_us = 500000; // 0.5s
$last_count = isset( $_GET['last_count'] ) ? (int) test_inputs( $_GET['last_count'] ) : 0;


	if ($IS_LOGGED == true && $USER_ID != 0) {

		$totalNotifications = 0;
		$totalNewChats = 0;

		$qu_z_messages_list_sel = "SELECT * FROM  `z_messages_list` WHERE ( (`z_messages_list`.`a_id` = $USER_ID) OR (`z_messages_list`.`b_id` = $USER_ID) )";
		$qu_z_messages_list_EXE = mysqli_query($KONN, $qu_z_messages_list_sel);
		if (mysqli_num_rows($qu_z_messages_list_EXE)) {
			while ($z_messages_list_REC = mysqli_fetch_assoc($qu_z_messages_list_EXE)) {
				$chat_id = (int) $z_messages_list_REC['chat_id'];
				$qu_z_messages_list_posts_sel = "SELECT COUNT(`post_id`) FROM  `z_messages_list_posts` WHERE ((`added_by` <> $USER_ID) AND (`chat_id` = $chat_id) AND (`is_read` = 0));";
				$qu_z_messages_list_posts_EXE = mysqli_query($KONN, $qu_z_messages_list_posts_sel);
				if (mysqli_num_rows($qu_z_messages_list_posts_EXE)) {
					$z_messages_list_posts_DATA = mysqli_fetch_array($qu_z_messages_list_posts_EXE);
					$totalNewChats += (int) $z_messages_list_posts_DATA['0'];
				}
			}
		}
		
		$qu_employees_notifications_sel = "SELECT COUNT(`notification_id`) FROM  `employees_notifications` WHERE ((`is_seen` = 0) AND (`employee_id` = $USER_ID))";
		$qu_employees_notifications_EXE = mysqli_query($KONN, $qu_employees_notifications_sel);
		if (mysqli_num_rows($qu_employees_notifications_EXE)) {
			$employees_notifications_DATA = mysqli_fetch_array($qu_employees_notifications_EXE);
			$totalNotifications = (int) $employees_notifications_DATA[0];
		}
		
		$IAM_ARRAY['success'] = true;
		$IAM_ARRAY['new_notifications'] = $totalNotifications;
		$IAM_ARRAY['new_chats'] = $totalNewChats;

	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-564654";
	}

	






header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));