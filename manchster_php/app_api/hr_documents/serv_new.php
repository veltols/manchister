<?php

$IAM_ARRAY;

$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {



	if (
		isset($_POST['document_type_id']) &&
		isset($_POST['document_title']) &&
		isset($_POST['document_description'])
	) {


		//todo
		//authorize inputs

		$document_id = 0;
		$document_type_id = "" . test_inputs($_POST['document_type_id']);
		$document_title = "" . test_inputs($_POST['document_title']);
		$document_description = "" . test_inputs($_POST['document_description']);
		$document_attachment = '';

		$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg', 'csv', 'doc', 'docx', 'xls', 'xlsx');

		if (isset($_FILES['document_attachment']) && $_FILES['document_attachment']["tmp_name"]) {
			$thsName = $_FILES['document_attachment']["name"];
			$thsTemp = $_FILES['document_attachment']["tmp_name"];
			if (!empty($thsName)) {
				$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);

				if (in_array($fileExt, $allowFileExt)) {

					//upload file to server
					$upload_res = upload_file('document_attachment', 8000, 'uploads', '../');
					if ($upload_res == true) {
						$document_attachment = $upload_res;
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "Error in file - 574574568";
					}

				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format";
				}

			}

		}

		$atpsListQryIns = "INSERT INTO `hr_documents` (
											`document_type_id`, 
											`document_title`, 
											`document_description`, 
											`document_attachment` 
											) VALUES ( ?, ? , ? , ? );";

		if ($atpsListStmtIns = mysqli_prepare($KONN, $atpsListQryIns)) {
			if (mysqli_stmt_bind_param($atpsListStmtIns, "isss", $document_type_id, $document_title, $document_description, $document_attachment)) {
				if (mysqli_stmt_execute($atpsListStmtIns)) {
					$document_id = (int) mysqli_insert_id($KONN);
					mysqli_stmt_close(statement: $atpsListStmtIns);

					$log_action = 'Document_Added';
					$logger_type = 'employees_list';
					$logged_by = $USER_ID;
					if (InsertSysLog('hr_documents', $document_id, $log_action, '---', $logger_type, $logged_by, 'int')) {
						$IAM_ARRAY['success'] = true;
						$IAM_ARRAY['message'] = "Succeed";
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "log Failed-548";
					}



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
			reportError('hr_documents failed to prepare stmt ', 'employees_list', $EMPLOYEE_ID);
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-55";
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


