<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;


if ($IS_LOGGED == true && $USER_ID != 0) {


	if (
		isset($_POST['file_id']) &&
		isset($_POST['group_id']) &&
		isset($_FILES['uploaded_file'])
	) {





		$file_id = 0;
		$file_path = 'no_data.png';
		$file_id = (int) test_inputs($_POST['file_id']);
		$group_id = (int) test_inputs($_POST['group_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');


		if ($file_id != 0) {

			//count new version no
			$file_version = 2;
			$qu_z_groups_list_files_sel = "SELECT COUNT(`file_id`) FROM  `z_groups_list_files` WHERE ((`group_id` = $group_id) AND (`main_file_id` = $file_id))";
			$qu_z_groups_list_files_EXE = mysqli_query($KONN, $qu_z_groups_list_files_sel);
			if (mysqli_num_rows($qu_z_groups_list_files_EXE)) {
				$z_groups_list_files_DATA = mysqli_fetch_array($qu_z_groups_list_files_EXE);
				$curVersionCount = (int) $z_groups_list_files_DATA[0];
				$file_version = $curVersionCount + 2;
			}
			//get file name
			$file_name = '';
			$qu_name_sel = "SELECT `file_name` FROM  `z_groups_list_files` WHERE ((`group_id` = $group_id) AND (`file_id` = $file_id))";
			$qu_name_EXE = mysqli_query($KONN, $qu_name_sel);
			if (mysqli_num_rows($qu_name_EXE)) {
				$z_name_DATA = mysqli_fetch_array($qu_name_EXE);
				$file_name = "" . $z_name_DATA[0];
			}


			$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg', 'csv', 'doc', 'docx', 'xls', 'xlsx');

			if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']["tmp_name"]) {
				$thsName = $_FILES['uploaded_file']["name"];
				$thsTemp = $_FILES['uploaded_file']["tmp_name"];

				if (!empty($thsName)) {
					$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


					if (in_array($fileExt, $allowFileExt)) {

						//find file type 
						$post_type = '';
						if ($fileExt == 'pdf' || $fileExt == 'csv' || $fileExt == 'doc' || $fileExt == 'docx' || $fileExt == 'xls' || $fileExt == 'xlsx') {
							$post_type = 'document';
						} else if ($fileExt == 'jpg' || $fileExt == 'png' || $fileExt == 'jpeg') {
							$post_type = 'image';
						}
						//upload file to server
						$upload_res = upload_file('uploaded_file', 8000, 'uploads', '../');
						if ($upload_res == true) {
							$file_path = $upload_res;

							//update data

							$atpsListQryIns = "INSERT INTO `z_groups_list_files` (
										`main_file_id`, 
										`file_name`, 
										`file_version`, 
										`file_path`, 
										`added_by`, 
										`added_date`, 
										`group_id` 
								) VALUES ( ?, ?, ?, ?, ?, ?, ? );";
							$ff = '';
							if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
								if (mysqli_stmt_bind_param($atpsListStmtIns, "isisisi", $file_id, $ff, $file_version, $file_path, $added_by, $added_date, $group_id)) {
									if (mysqli_stmt_execute($atpsListStmtIns)) {
										$post_attachment_id = (int) mysqli_insert_id($KONN);
										mysqli_stmt_close(statement: $atpsListStmtIns);

										//insert group message 
										$qu_z_groups_list_posts_ins = "INSERT INTO `z_groups_list_posts` (
											`post_text`, 
											`post_type`, 
											`post_attachment_id`, 
											`group_id`, 
											`added_by`, 
											`added_date` 
										) VALUES (
											'Uploaded new version of $file_name', 
											'" . $post_type . "', 
											'" . $post_attachment_id . "', 
											'" . $group_id . "', 
											'" . $added_by . "', 
											'" . $added_date . "' 
										);";

										if (!mysqli_query($KONN, $qu_z_groups_list_posts_ins)) {
											die("General Error, please contact support");
										}


										$IAM_ARRAY['success'] = true;
										$IAM_ARRAY['message'] = "Succeed";


									} else {
										//Execute failed 
										reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
										$IAM_ARRAY['success'] = false;
										$IAM_ARRAY['message'] = "ERR-12-57";
									}
								} else {
									//bind failed 
									reportError(mysqli_stmt_error($atpsListStmtIns), 'employees_list', $EMPLOYEE_ID);
									$IAM_ARRAY['success'] = false;
									$IAM_ARRAY['message'] = "ERR-12-56";
								}
							} else {
								//prepare failed 
								reportError('z_groups_list_files failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
								$IAM_ARRAY['success'] = false;
								$IAM_ARRAY['message'] = "ERR-12-55";
							}



						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error in file - 574574568";
						}



					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error in file format";
					}











				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "File is Required";
				}


			} else {
				$IAM_ARRAY['success'] = false;
				$IAM_ARRAY['message'] = "Error in file format";
			}














		} else {
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Error in origin file";
		}


	} else {
		//No request
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-12-4556";
	}

















} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


