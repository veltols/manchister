<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Departments_List");

$pageDataController = $POINTER . "get_departments_list";
$addNewDepartmentController = $POINTER . "add_departments_list";
$updateDepartmentController = $POINTER . "update_departments_list";

$pageId = 200;
$subPageId = 400;
include("app/assets.php");




?>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/loader.js"></script>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/html2canvas.min.js"></script>

<link href="<?= $POINTER; ?>../public/assets/styles/chrt_orgchart.css" rel="stylesheet" media="all">
<link href="<?= $POINTER; ?>../public/assets/styles/chrt_tooltip.css" rel="stylesheet" media="all">
<link href="<?= $POINTER; ?>../public/assets/styles/chrt_util.css" rel="stylesheet" media="all">




<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Departments_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('departments_list_nav.php');
		?>
	</div>

	<div class="pageOptions">

	</div>
</div>


<?php
/*

	<div class="dept">

		<h1>dept01</h1>

		<div class="subDepts">

			<div class="dept">
				<h1>dept01-sub01</h1>
				<div class="subDepts">
					<div class="dep">
						<h1>dept01-sub01-sub02</h1>
					</div>
				</div>
			</div>

			<div class="dept">
				<h1>dept01-sub02</h1>
				<!-- Add sub here -->
			</div >

		</div >


		

				<div class="dept">
					<h1><?=$fggg; ?></h1>
					<div class="subDepts">
						
					</div>
				</div>
*/
?>


<div class="binary-tree">

	<div id="chart_div"></div>



	<div class="newChart">


		<?php
		$qu_mainSEL_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = 0) ) ORDER BY `department_id` ASC";
		$qu_mainSEL_EXE = mysqli_query($KONN, $qu_mainSEL_sel);
		if (mysqli_num_rows($qu_mainSEL_EXE)) {
			while ($mainSEL_REC = mysqli_fetch_assoc($qu_mainSEL_EXE)) {
				$mainDeptId = (int) $mainSEL_REC['department_id'];
				$main_department_id = (int) $mainSEL_REC['main_department_id'];
				$mainDeptName = $mainSEL_REC['department_name'];
				?>

				<div class="dept mainDept">
					<h1 class="lvl-1">
						<div class="topDec"></div><?= $mainDeptName; ?>
					</h1>
					<div class="subDepts sub-lvl-1">
						<?php
						$qu_subSel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $mainDeptId) ) ORDER BY `department_id` ASC";
						$qu_subSel_EXE = mysqli_query($KONN, $qu_subSel_sel);
						if (mysqli_num_rows($qu_subSel_EXE)) {
							while ($subSel_REC = mysqli_fetch_assoc($qu_subSel_EXE)) {
								$subDeptId = (int) $subSel_REC['department_id'];
								$subDeptName = $subSel_REC['department_name'];
								?>
								<div class="dept">
									<h1 class="lvl-2">
										<div class="topDec"></div><?= $subDeptName; ?>
									</h1>
									<div class="subDepts sub-lvl-2">
										<?php
										//check if has more level
										$qu_lv2Sel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $subDeptId) ) ORDER BY `department_id` ASC";
										$qu_lv2Sel_EXE = mysqli_query($KONN, $qu_lv2Sel_sel);
										if (mysqli_num_rows($qu_lv2Sel_EXE)) {
											while ($lv2Sel_REC = mysqli_fetch_assoc($qu_lv2Sel_EXE)) {
												$lv2Id = (int) $lv2Sel_REC['department_id'];
												$lv2Name = $lv2Sel_REC['department_name'];
												?>

												<div class="dept">
													<h1 class="lvl-3">
														<div class="topDec"></div><?= $lv2Name; ?>
													</h1>
													<div class="subDepts sub-lvl-3">
														<?php
														$qu_lv33Sel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $lv2Id) ) ORDER BY `department_id` ASC";
														$qu_lv33Sel_EXE = mysqli_query($KONN, $qu_lv33Sel_sel);
														if (mysqli_num_rows($qu_lv33Sel_EXE)) {
															while ($lv33Sel_REC = mysqli_fetch_assoc($qu_lv33Sel_EXE)) {
																$lv3Id = (int) $lv33Sel_REC['department_id'];
																$lv3Name = $lv33Sel_REC['department_name'];
																?>

																<div class="dept">
																	<h1 class="lvl-4">
																		<div class="topDec"></div><?= $lv3Name; ?>
																	</h1>
																	<div class="subDepts sub-lvl-4">
																		<?php
																		$qu_lv44Sel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $lv3Id) ) ORDER BY `department_id` ASC";
																		$qu_lv44Sel_EXE = mysqli_query($KONN, $qu_lv44Sel_sel);
																		if (mysqli_num_rows($qu_lv44Sel_EXE)) {
																			while ($lv44Sel_REC = mysqli_fetch_assoc($qu_lv44Sel_EXE)) {
																				$lv44SelId = (int) $lv44Sel_REC['department_id'];
																				$lv44SelName = $lv44Sel_REC['department_name'];
																				?>

																				<div class="dept">
																					<h1 class="lvl-5">
																						<div class="topDec"></div><?= $lv44SelName; ?>
																					</h1>
																					<div class="subDepts sub-lvl-5">
																						<?php
																						$qu_lv55Sel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $lv44SelId) ) ORDER BY `department_id` ASC";
																						$qu_lv55Sel_EXE = mysqli_query($KONN, $qu_lv55Sel_sel);
																						if (mysqli_num_rows($qu_lv55Sel_EXE)) {
																							while ($lv55Sel_REC = mysqli_fetch_assoc($qu_lv55Sel_EXE)) {
																								$lv55SelId = (int) $lv55Sel_REC['department_id'];
																								$lv55SelName = $lv55Sel_REC['department_name'];
																								?>

																								<div class="dept">
																									<h1 class="lvl-6">
																										<div class="topDec"></div><?= $lv55SelName; ?>
																									</h1>
																									<div class="subDepts sub-lvl-6">

																									</div>
																								</div>
																								<?php
																							}
																						}
																						?>
																					</div>
																				</div>
																				<?php
																			}
																		}
																		?>
																	</div>
																</div>
																<?php
															}
														}
														?>
													</div>
												</div>
												<?php
											}
										}
										?>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
				<?php
			}
		}
		?>









		<?php
		/*
																																																																				  $qu_mainSEL_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = 0) ) ORDER BY `department_id` ASC";
																																																																				  $qu_mainSEL_EXE = mysqli_query($KONN, $qu_mainSEL_sel);
																																																																				  if (mysqli_num_rows($qu_mainSEL_EXE)) {
																																																																					  while ($mainSEL_REC = mysqli_fetch_assoc($qu_mainSEL_EXE)) {
																																																																						  $mainDeptId = (int) $mainSEL_REC['department_id'];
																																																																						  $main_department_id = (int) $mainSEL_REC['main_department_id'];
																																																																						  $mainDeptName = $mainSEL_REC['department_name'];
																																																																						  ?>

																																																																						  <div class="dept">
																																																																							  <h1><?= $mainDeptName; ?></h1>
																																																																							  <?php
																																																																							  $qu_subSel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $mainDeptId) ) ORDER BY `department_id` ASC";
																																																																							  $qu_subSel_EXE = mysqli_query($KONN, $qu_subSel_sel);
																																																																							  if (mysqli_num_rows($qu_subSel_EXE)) {
																																																																								  while ($subSel_REC = mysqli_fetch_assoc($qu_subSel_EXE)) {
																																																																									  $subDeptId = (int) $subSel_REC['department_id'];
																																																																									  $subDeptName = $subSel_REC['department_name'];
																																																																									  ?>
																																																																									  <div class="subDepts">
																																																																										  <div class="dept">
																																																																											  <h1><?= $subDeptName; ?></h1>
																																																																											  <?php
																																																																											  //check if has more level
																																																																											  $qu_lv2Sel_sel = "SELECT * FROM  `employees_list_departments` WHERE ( (`main_department_id` = $subDeptId) ) ORDER BY `department_id` ASC";
																																																																											  $qu_lv2Sel_EXE = mysqli_query($KONN, $qu_lv2Sel_sel);
																																																																											  if (mysqli_num_rows($qu_lv2Sel_EXE)) {
																																																																													  while ($lv2Sel_REC = mysqli_fetch_assoc($qu_lv2Sel_EXE)) {
																																																																														  $department_id = (int) $lv2Sel_REC['department_id'];
																																																																														  $main_department_id = (int) $lv2Sel_REC['main_department_id'];
																																																																														  $mainDeptName = $lv2Sel_REC['department_name'];
																																																																													  }
																																																																													  ?>
																																																																												  </div>
																																																																												  <?php
																																																																											  }

																																																																											  ?>
																																																																										  </div>
																																																																									  </div>
																																																																									  <?php

																																																																								  }
																																																																							  }

																																																																							  ?>



																																																																						  </div>
																																																																						  <?php
																																																																					  }
																																																																				  }
																																																																		  */
		?>
	</div>


</div>



<script type="text/javascript">
	/*
	



	</div >
		google.charts.load('current', { packages: ["orgchart"] });
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Name');
		data.addColumn('string', 'Manager');
		data.addColumn('string', 'ToolTip');

		// For each orgchart box, provide the name, manager, and tooltip to show.
		data.addRows([

			<?php

			$qu_mainSEL_sel = "SELECT * FROM  `employees_list_departments` ORDER BY `department_id` ASC";
			$qu_mainSEL_EXE = mysqli_query($KONN, $qu_mainSEL_sel);
			if (mysqli_num_rows($qu_mainSEL_EXE)) {
				while ($mainSEL_REC = mysqli_fetch_assoc($qu_mainSEL_EXE)) {
					$department_id = (int) $mainSEL_REC['department_id'];
					$main_department_id = (int) $mainSEL_REC['main_department_id'];
					$mainDeptName = $mainSEL_REC['department_name'];
					if ($main_department_id == '0') {
						?>
				['<?= $mainDeptName; ?>', '', ''],
					<?php
					} else {
						//get main dept name
						$qu_singSel_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $main_department_id";
						$qu_singSel_EXE = mysqli_query($KONN, $qu_singSel_sel);
						if (mysqli_num_rows($qu_singSel_EXE)) {
							$singSel_DATA = mysqli_fetch_assoc($qu_singSel_EXE);
							$sub_department_id = (int) $singSel_DATA['department_id'];
							$sub_department_name = $singSel_DATA['department_name'];

							?>
						['<?= $mainDeptName; ?>', '<?= $sub_department_name; ?>', ''],
						<?php
						}
					}
				}
			}

			?>

		]);

	// Create the chart.
	var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));

	// Draw the chart, setting the allowHtml option to true for the tooltips.
	chart.draw(data, { 'allowHtml': true });



	}
	*/
</script>





<?php
include("app/footer.php");
?>

<script>//drawChart();</script>