<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;


if ($IS_LOGGED == true && $USER_ID != 0) {


	if (
		isset($_POST['file_name']) &&
		isset($_POST['group_id']) &&
		isset($_FILES['uploaded_file'])
	) {





		$file_id = 0;
		$file_name = "" . test_inputs($_POST['file_name']);
		$file_path = 'no_data.png';
		$group_id = (int) test_inputs($_POST['group_id']);

		$added_by = $USER_ID;
		$added_date = date('Y-m-d H:i:00');



		$qu_z_groups_list_files_sel = "SELECT * FROM  `z_groups_list_files` WHERE ((`file_name` = '$file_name') AND (`group_id` = $group_id))";
		$qu_z_groups_list_files_EXE = mysqli_query($KONN, $qu_z_groups_list_files_sel);
		$z_groups_list_files_DATA;
		if (mysqli_num_rows($qu_z_groups_list_files_EXE) > 0) {
			//error
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "Error in file - Name duplicated";
		} else {
			//continue


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
										`file_name`, 
										`file_path`, 
										`added_by`, 
										`added_date`, 
										`group_id` 
								) VALUES ( ?, ?, ?, ?, ? );";

							if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
								if (mysqli_stmt_bind_param($atpsListStmtIns, "ssisi", $file_name, $file_path, $added_by, $added_date, $group_id)) {
									if (mysqli_stmt_execute($atpsListStmtIns)) {
										$post_attachment_id = (int) mysqli_insert_id($KONN);
										mysqli_stmt_close(statement: $atpsListStmtIns);

										//insert group message 
										$qu_z_groups_list_posts_ins = "INSERT INTO `z_groups_list_posts` (
											`post_type`, 
											`post_attachment_id`, 
											`group_id`, 
											`added_by`, 
											`added_date` 
										) VALUES (
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


