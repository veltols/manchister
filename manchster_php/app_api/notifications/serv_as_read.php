<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0 && isset($_POST['notification_id'])) {

	$notification_id = (int) test_inputs($_POST['notification_id']);
	if( isset($_POST['all_notifications']) ){
		$all_notifications = "".test_inputs($_POST['all_notifications']);
		$notArr = explode('-', $all_notifications);
		
		$qu_employees_notifications_updt = "";
		for( $m=0 ; $m < count($notArr) ; $m++ ){
			$thsId = (int) $notArr[$m];
			if( $thsId != 0 ){
				$qu_employees_notifications_updt .= "UPDATE `employees_notifications` SET `is_seen` = '1'  WHERE `notification_id` = $thsId;";
			}
			
		}
		
		if( $qu_employees_notifications_updt != "" ){
			if (mysqli_multi_query($KONN, $qu_employees_notifications_updt)) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = mysqli_error($KONN);
			}
			
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-121212888";
		}

	} else {
		$qu_employees_notifications_updt = "";
		if( $notification_id > 0 ){
			$qu_employees_notifications_updt = "UPDATE `employees_notifications` SET `is_seen` = '1'  WHERE `notification_id` = $notification_id;";
		} else if( $notification_id == 0 ){
			$qu_employees_notifications_updt = "UPDATE `employees_notifications` SET `is_seen` = '1'  WHERE `employee_id` = $USER_ID;";
		}

		if( $qu_employees_notifications_updt != "" ){
			if (mysqli_query($KONN, $qu_employees_notifications_updt)) {
				$IAM_ARRAY['success'] = true;
				$IAM_ARRAY['message'] = "Succeed";
			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "ERR-11221";
			}
			
		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-121212888";
		}
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));

