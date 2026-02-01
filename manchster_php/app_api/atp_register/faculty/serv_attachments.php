<?php

$IAM_ARRAY;


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {

	$atp_id = $USER_ID;


	//check request
	if (isset($_POST['faculty_id'])) {

		$faculty_id = (int) test_inputs($_POST['faculty_id']);
		




		if (
			isset($_FILES['faculty_cv']) || 
			isset($_FILES['faculty_certificate'])
		) {
			

			
				
//-------------------------------------------------------------------------------------

				$allowFileExt = array('pdf', 'jpg', 'png', 'jpeg');

				if (isset($_FILES['faculty_cv']) && $_FILES['faculty_cv']["tmp_name"]) {
					$thsName = $_FILES['faculty_cv']["name"];
					$thsTemp = $_FILES['faculty_cv']["tmp_name"];

					if (!empty($thsName)) {
						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('faculty_cv', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_list_faculties` SET 
														`faculty_cv` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`faculty_id` = ?) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "sii", $filePath, $atp_id, $faculty_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded Faculty CV", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

												$IAM_ARRAY['success'] = true;
												$IAM_ARRAY['message'] = "Succeed";

											} else {
												reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
											}

										} else {
											//Execute failed 
											reportError(mysqli_stmt_error($atpsFormInitStmtUpdt), "atps_list", $USER_ID);
										}
									} else {
										//bind failed 
										reportError(mysqli_stmt_error($atpsFormInitStmtUpdt), "atps_list", $USER_ID);
									}
								} else {
									//prepare failed 
									reportError('atps_form_init failed to prepare stmt - 35 ', "atps_list", $USER_ID);
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
						$IAM_ARRAY['message'] = "faculty_cv is Required";
					}


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format - faculty_cv";
				}

				if (isset($_FILES['faculty_certificate']) && $_FILES['faculty_certificate']["tmp_name"]) {
					$thsName = $_FILES['faculty_certificate']["name"];
					$thsTemp = $_FILES['faculty_certificate']["tmp_name"];

					if (!empty($thsName)) {
						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('faculty_certificate', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_list_faculties` SET 
														`faculty_certificate` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`faculty_id` = ?) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "sii", $filePath, $atp_id, $faculty_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded Faculty Certificate", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

												$IAM_ARRAY['success'] = true;
												$IAM_ARRAY['message'] = "Succeed";

											} else {
												reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
											}

										} else {
											//Execute failed 
											reportError(mysqli_stmt_error($atpsFormInitStmtUpdt), "atps_list", $USER_ID);
										}
									} else {
										//bind failed 
										reportError(mysqli_stmt_error($atpsFormInitStmtUpdt), "atps_list", $USER_ID);
									}
								} else {
									//prepare failed 
									reportError('atps_form_init failed to prepare stmt - 35 ', "atps_list", $USER_ID);
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
						$IAM_ARRAY['message'] = "faculty_certificate is Required";
					}


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format - faculty_certificate";
				}

//-------------------------------------------------------------------------------------








		} else {
			//No request for stage no1
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-4556";
		}








	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-200";
	}







} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


