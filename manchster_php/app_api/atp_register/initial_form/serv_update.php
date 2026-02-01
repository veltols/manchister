<?php

$IAM_ARRAY;

$is_renew = 0;

if (isset($_POST['is_renew'])) {
	$is_renew = 1;
}


$RES = false;
$idder = "";
$token_value = $falseToken;
if ($IS_LOGGED == true && $USER_ID != 0) {




		//check if atp has submitted or not
		$totForms = 0;
		$qu_atps_form_init_sel = "SELECT COUNT(`form_id`) AS `form_count` FROM  `atps_form_init` WHERE ((`atp_id` = $USER_ID)  AND (`is_renew` = $is_renew))";
		$qu_atps_form_init_EXE = mysqli_query($KONN, $qu_atps_form_init_sel);
		if (mysqli_num_rows($qu_atps_form_init_EXE)) {
			$atps_form_init_DATA = mysqli_fetch_assoc($qu_atps_form_init_EXE);
			$totForms = (int) $atps_form_init_DATA['form_count'];
		}
		$atp_id = $USER_ID;
		


		if (
			isset($_POST['est_name']) &&
			isset($_POST['est_name_ar']) &&
			isset($_POST['iqa_name']) &&
			isset($_POST['email_address']) &&
			isset($_POST['emirate_id']) &&
			isset($_POST['area_name']) &&
			isset($_POST['street_name']) &&
			isset($_POST['building_name']) &&
			isset($_POST['registration_expiry']) &&
			isset($_POST['atp_category_id'])
		) {


			//STAGE NO 1 ----------------------------------------------------------------



			//todo
			//authorize inputs

			$form_id = 0;
			$added_date = date('Y-m-d H:i:00');
			$est_name = encryptData("" . test_inputs($_POST['est_name']));
			$est_name_ar = encryptData("" . test_inputs($_POST['est_name_ar']));
			$iqa_name = encryptData("" . test_inputs($_POST['iqa_name']));
			$email_address = encryptData("" . test_inputs($_POST['email_address']));
			$registration_no = isset($_POST['registration_no']) ? encryptData("" . test_inputs($_POST['registration_no'])) : '0';
			$emirate_id = encryptData("" . test_inputs($_POST['emirate_id']));
			$area_name = encryptData("" . test_inputs($_POST['area_name']));
			$street_name = encryptData("" . test_inputs($_POST['street_name']));
			$building_name = encryptData("" . test_inputs($_POST['building_name']));
			$registration_expiry = "" . test_inputs($_POST['registration_expiry']);
			$atp_category_id = (int) test_inputs($_POST['atp_category_id']);
			//get city code

			if ($totForms == 0) {
				//--------------------------------------------------------------------------------------------------------------------------
				//insert
				$atpsFormInitQryIns = "INSERT INTO `atps_form_init` (
																	`atp_id`, 
																	`added_date`, 
																	`est_name`, 
																	`est_name_ar`, 
																	`iqa_name`, 
																	`email_address`, 
																	`emirate_id`, 
																	`area_name`, 
																	`street_name`, 
																	`building_name`, 
																	`registration_no`, 
																	`registration_expiry`, 
																	`atp_category_id`, 
																	`is_renew` 
																) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  '$is_renew' );";
				if ($atpsFormInitStmtIns = mysqli_prepare($KONN, $atpsFormInitQryIns)) {
					if (mysqli_stmt_bind_param($atpsFormInitStmtIns, "isssssisssssi", $atp_id, $added_date, $est_name, $est_name_ar, $iqa_name, $email_address, $emirate_id, $area_name, $street_name, $building_name, $registration_no, $registration_expiry, $atp_category_id)) {
						if (mysqli_stmt_execute($atpsFormInitStmtIns)) {
							//$form_id = ( int ) mysqli_insert_id($KONN);
							mysqli_stmt_close(statement: $atpsFormInitStmtIns);

							if (InsertAtpLog("ATP filled stage-1 initial form", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

								$IAM_ARRAY['success'] = true;
								$IAM_ARRAY['message'] = "Succeed";

								//notify user
								/*
								$qu_atps_list_sel = "SELECT `added_by` FROM  `atps_list` WHERE `atp_id` = $atp_id";
								$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
								if(mysqli_num_rows($qu_atps_list_EXE)){
									$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
									$added_by = ( int ) $atps_list_DATA['added_by'];
									notifyUser("Training Provider Submitted Initial Form ", 'ext/atps/list/', $added_by);
								}
						*/



								
							} else {
								reportError('atp log failed - 4584864 ', 'atps_list', $USER_ID);
							}

						} else {
							//Execute failed 
							reportError(mysqli_stmt_error($atpsListStmtIns), 'atps_list', $USER_ID);
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "ERR-12-57";
						}
					} else {
						//bind failed 
						reportError(mysqli_stmt_error($atpsListStmtIns), 'atps_list', $USER_ID);
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "ERR-12-56";
					}
				} else {
					//prepare failed 
					reportError('atps_list failed to prepare stmt ', 'atps_list', $USER_ID);
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "ERR-12-55";
				}
				//--------------------------------------------------------------------------------------------------------------------------
			} else {
				//update
				//--------------------------------------------------------------------------------------------------------------------------

				$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
							`est_name` = ? , 
							`est_name_ar` = ? , 
							`iqa_name` = ? , 
							`email_address` = ? , 
							`registration_no` = ? , 
							`registration_expiry` = ? , 
							`area_name` = ? , 
							`street_name` = ? , 
							`building_name` = ? , 
							`atp_category_id` = ? 
							WHERE ( ( `atp_id` = ? )  AND (`is_renew` = $is_renew)) ";
				if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
					if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "sssssssssii", $est_name, $est_name_ar, $iqa_name, $email_address, $registration_no, $registration_expiry, $area_name, $street_name, $building_name, $atp_category_id, $atp_id)) {
						if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

							if (InsertAtpLog("ATP modified stage-1 initial form", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

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
					reportError('atps_form_init failed to prepare stmt ', "atps_list", $USER_ID);
				}


				mysqli_stmt_close($atpsFormInitStmtUpdt);
				//--------------------------------------------------------------------------------------------------------------------------
			}


		} if (
			isset($_FILES['delivery_plan']) || 
			isset($_FILES['org_chart']) || 
			isset($_FILES['site_plan']) || 
			isset($_FILES['sed_form']) || 
			isset($_FILES['atp_logo'])
		) {
			

				// check logo
				$allowFileExt = array('pdf');
				$allowImgExt = array('jpg', 'png', 'jpeg');
				if (isset($_FILES['atp_logo']) && $_FILES['atp_logo']["tmp_name"]) {
					$thsName = $_FILES['atp_logo']["name"];
					$thsTemp = $_FILES['atp_logo']["tmp_name"];

					if (!empty($thsName)) {


						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowImgExt)) {

							//upload file to server

							$upload_res = upload_file('atp_logo', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
														`atp_logo` = ? 
														WHERE ( ( `atp_id` = ? ) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "si", $filePath, $atp_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded Logo", $atp_id, 'atps_list', $USER_ID, 'R&C')) {
														$qu_atps_list_updt = "UPDATE  `atps_list` SET 
																			`atp_logo` = '".$filePath."'
																			WHERE `atp_id` = $atp_id;";

														if(!mysqli_query($KONN, $qu_atps_list_updt)){
															die('Error in logo update');
														}


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
								$IAM_ARRAY['message'] = "Error in file - atp_logo";
							}



						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error in file format - atp_logo";
						}
					}
				}
//-------------------------------------------------------------------------------------

				// check sed_form
				if (isset($_FILES['sed_form']) && $_FILES['sed_form']["tmp_name"]) {
					$thsName = $_FILES['sed_form']["name"];
					$thsTemp = $_FILES['sed_form']["tmp_name"];

					if (!empty($thsName)) {
						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('sed_form', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
														`sed_form` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "si", $filePath, $atp_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded legal status", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

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
								$IAM_ARRAY['message'] = "Error in file - sed_form";
							}



						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error in file format - sed_form";
						}
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "sed_form is Required";
					}
				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format";
				}
//-------------------------------------------------------------------------------------

				// check site_plan
				if (isset($_FILES['site_plan']) && $_FILES['site_plan']["tmp_name"]) {
					$thsName = $_FILES['site_plan']["name"];
					$thsTemp = $_FILES['site_plan']["tmp_name"];

					if (!empty($thsName)) {
						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('site_plan', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
														`site_plan` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "si", $filePath, $atp_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded Commitment letter", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

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
								$IAM_ARRAY['message'] = "Error in file - site_plan";
							}



						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error in file format - site_plan";
						}
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "site plan is Required";
					}
				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format - site plan";
				}
//-------------------------------------------------------------------------------------


				// check org_chart
				if (isset($_FILES['org_chart']) && $_FILES['org_chart']["tmp_name"]) {
					$thsName = $_FILES['org_chart']["name"];
					$thsTemp = $_FILES['org_chart']["tmp_name"];

					if (!empty($thsName)) {


						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('org_chart', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
														`org_chart` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "si", $filePath, $atp_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded NOC", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

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
								$IAM_ARRAY['message'] = "Error in file - org_chart";
							}



						} else {
							$IAM_ARRAY['success'] = false;
							$IAM_ARRAY['message'] = "Error in file format - org_chart";
						}
						
					} else {
						$IAM_ARRAY['success'] = false;
						$IAM_ARRAY['message'] = "org_chart is Required";
					}


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format 2- org_chart";
				}

//-------------------------------------------------------------------------------------

				$allowFileExt = array('pdf');

				if (isset($_FILES['delivery_plan']) && $_FILES['delivery_plan']["tmp_name"]) {
					$thsName = $_FILES['delivery_plan']["name"];
					$thsTemp = $_FILES['delivery_plan']["tmp_name"];

					if (!empty($thsName)) {
						$fileExt = pathinfo("../uploads/" . basename($thsName), PATHINFO_EXTENSION);


						if (in_array($fileExt, $allowFileExt)) {

							//upload file to server

							$upload_res = upload_file('delivery_plan', 8000, 'uploads', '../');
							if ($upload_res == true) {
								$filePath = $upload_res;

								//update data
								$atpsFormInitQryUpdt = "UPDATE  `atps_form_init` SET 
														`delivery_plan` = ? 
														WHERE ( ( `atp_id` = ? ) AND (`is_renew` = $is_renew) ) ";
								if ($atpsFormInitStmtUpdt = mysqli_prepare($KONN, $atpsFormInitQryUpdt)) {
									if (mysqli_stmt_bind_param($atpsFormInitStmtUpdt, "si", $filePath, $atp_id)) {
										if (mysqli_stmt_execute($atpsFormInitStmtUpdt)) {

											if (InsertAtpLog("ATP uploaded trade license", $atp_id, 'atps_list', $USER_ID, 'R&C')) {

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
						$IAM_ARRAY['message'] = "delivery plan is Required";
					}


				} else {
					$IAM_ARRAY['success'] = false;
					$IAM_ARRAY['message'] = "Error in file format - delivery_plan";
				}

//-------------------------------------------------------------------------------------








		} else {
			//No request for stage no1
			$IAM_ARRAY['success'] = false;
			$IAM_ARRAY['message'] = "ERR-12-4556";
		}








} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-100";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


