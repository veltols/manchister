<script>
	var totalThemes = 0;
</script>

<?php
$qu_m_strategic_plans_themes_sel = "SELECT * FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id ORDER BY `order_no` ASC";
$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
	?>

<?php
if( !isset($viewOnly) ){
?>

<a onclick="showModal('newTheme');" target="_blank" class="dataActionBtn actionBtn"
		style="width: 20%;background: var(--strategy) !important;margin:0;margin-inline-start: 5%;">
		<span class="label"><?= lang("Add_new_Theme/Pillar"); ?></span>
	</a>
<?php
}
?>

	<div class="priorityBoxContainer">

		<?php
		while ($m_strategic_plans_themes_REC = mysqli_fetch_assoc($qu_m_strategic_plans_themes_EXE)) {
			$theme_id = (int) $m_strategic_plans_themes_REC['theme_id'];
			$theme_ref = "" . $m_strategic_plans_themes_REC['theme_ref'];
			$theme_title = "" . $m_strategic_plans_themes_REC['theme_title'];
			$theme_description = "" . $m_strategic_plans_themes_REC['theme_description'];
			$order_no = (int) $m_strategic_plans_themes_REC['order_no'];
			$theme_weight = (int) $m_strategic_plans_themes_REC['theme_weight'];
			$plan_id = (int) $m_strategic_plans_themes_REC['plan_id'];
			$added_by = (int) $m_strategic_plans_themes_REC['added_by'];
			$added_date = "" . $m_strategic_plans_themes_REC['added_date'];

			$remainingObjectivesWeights = 100;

			$qu_sWq_sel = "SELECT SUM(`objective_weight`) FROM  `m_strategic_plans_objectives` WHERE `theme_id` = $theme_id";
			$qu_sWq_EXE = mysqli_query($KONN, $qu_sWq_sel);
			if (mysqli_num_rows($qu_sWq_EXE)) {
				$sWq_DATA = mysqli_fetch_array($qu_sWq_EXE);
				$objective_weights = (int) $sWq_DATA[0];
				$remainingObjectivesWeights = 100 - $objective_weights;
			}
			?>
			<script>
				totalThemes++;
			</script>
			<div class="priorityBox orderedItem order-<?= $order_no; ?>" data-order="<?= $order_no; ?>"
				id="priority-<?= $theme_id; ?>" data-id="<?= $theme_id; ?>" data-weight="<?= $remainingObjectivesWeights; ?>">
				<div class="titler">
					<i onclick="toggleTheme(<?= $theme_id; ?>);" class="fa-solid fa-caret-right opCloser"></i>
					<h1 onclick="toggleTheme(<?= $theme_id; ?>);"><?= $theme_title; ?>
						(<?= $theme_weight; ?> %)</h1>



<?php
if( !isset($viewOnly) ){
?>

					<div class="priorityOpts extraMenu extraMenuHidden" id="em-<?= $theme_id; ?>">
						<div class="mnuItemBtn" onclick="showSmallMnu(<?= $theme_id; ?>);"><i
								class="fa-solid fa-ellipsis-vertical"></i></div>
						<div class="mnuItems">
							<a onclick="moveUp(<?= $theme_id; ?>);"><?= lang("Move_Up"); ?></a>
							<a onclick="moveDown(<?= $theme_id; ?>);"><?= lang("Move_Down"); ?></a>
							<a onclick="addObjective(<?= $theme_id; ?>);"><?= lang("Add_Objective"); ?></a>
							<a onclick="editTheme(<?= $theme_id; ?>);"><?= lang("Edit"); ?></a>
							<a onclick="deleteStrategyItem('priority', <?= $theme_id; ?>);"><?= lang("Delete"); ?></a>
						</div>
					</div>
<?php
}
?>


				</div>
				<div class="priorityBody priorityBodyClosed">
					<?php
					$qu_m_strategic_plans_objectives_sel = "SELECT * FROM  `m_strategic_plans_objectives` WHERE `theme_id` = $theme_id";
					$qu_m_strategic_plans_objectives_EXE = mysqli_query($KONN, $qu_m_strategic_plans_objectives_sel);
					if (mysqli_num_rows($qu_m_strategic_plans_objectives_EXE)) {
						?>

						<?php
						$ff = 0;
						while ($m_strategic_plans_objectives_REC = mysqli_fetch_assoc($qu_m_strategic_plans_objectives_EXE)) {
							$ff++;
							$objective_id = (int) $m_strategic_plans_objectives_REC['objective_id'];
							$gR = $m_strategic_plans_objectives_REC['objective_ref'];
							$gt = $m_strategic_plans_objectives_REC['objective_title'];
							$gd = $m_strategic_plans_objectives_REC['objective_description'];
							$gW = (int) $m_strategic_plans_objectives_REC['objective_weight'];
							$remainingKPIsWeights = 0;

							$qu_sWq_sel = "SELECT SUM(`kpi_weight`) FROM  `m_strategic_plans_kpis` WHERE `objective_id` = $objective_id";
							$qu_sWq_EXE = mysqli_query($KONN, $qu_sWq_sel);
							if (mysqli_num_rows($qu_sWq_EXE)) {
								$sWq_DATA = mysqli_fetch_array($qu_sWq_EXE);
								$kpi_weights = (int) $sWq_DATA[0];
								$remainingKPIsWeights = 100 - $kpi_weights;
							}
							?>

							<div class="tableContainer" id="appsListDtd">
								<div class="table" style="width: 100%;">
									<div class="tableHeader">
										<div class="tr">
											<div class="th"><?= lang("Objective"); ?></div>
											<div class="th">&nbsp;</div>
											<div class="th"><?= lang("Weight"); ?></div>
											<div class="th"><?= lang("Description"); ?></div>
<?php
if( !isset($viewOnly) ){
?>
											<div class="th">&nbsp;</div>
<?php
}
?>
										</div>
									</div>
									<div class="tableBody" id="">

										<div class="tr levelRow objectiveRow" id="objective-<?= $objective_id; ?>"
											data-weight="<?= $remainingKPIsWeights; ?>">
											<div class="td"><?= $gt; ?></div>
											<div class="td">&nbsp;</div>
											<div class="td"><?= $gW; ?> %</div>
											<div class="td"><?= $gd; ?></div>
<?php
if( !isset($viewOnly) ){
?>
											<div class="td">
												<a onclick="addKPI(<?= $objective_id; ?>);"
													class="randomTableBtn randomStrategy"><?= lang("Add_KPI"); ?></a>
												<a onclick="deleteStrategyItem('objective', <?= $objective_id; ?>);"
													class="randomTableBtn randomDanger"><i class="fa-solid fa-trash"></i></a>
											</div>
<?php
}
?>
										</div>

										<?php
										$qu_m_strategic_plans_kpis_sel = "SELECT * FROM  `m_strategic_plans_kpis` WHERE `objective_id` = $objective_id ORDER BY `kpi_id` ASC";
										$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
										if (mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)) {
											while ($m_strategic_plans_kpis_REC = mysqli_fetch_assoc($qu_m_strategic_plans_kpis_EXE)) {
												$kpi_id = (int) $m_strategic_plans_kpis_REC['kpi_id'];
												$kpi_ref = $m_strategic_plans_kpis_REC['kpi_ref'];
												$kpi_title = $m_strategic_plans_kpis_REC['kpi_title'];
												$kpi_description = $m_strategic_plans_kpis_REC['kpi_description'];

												$kpi_weight = (int) $m_strategic_plans_kpis_REC['kpi_weight'];

												$remainingMilestonesWeights = 100;
												$qu_sWq_sel = "SELECT SUM(`milestone_weight`) FROM  `m_strategic_plans_milestones` WHERE `kpi_id` = $kpi_id";
												$qu_sWq_EXE = mysqli_query($KONN, $qu_sWq_sel);
												if (mysqli_num_rows($qu_sWq_EXE)) {
													$sWq_DATA = mysqli_fetch_array($qu_sWq_EXE);
													$milestone_weights = (int) $sWq_DATA[0];
													$remainingMilestonesWeights = 100 - $milestone_weights;
												}
												?>
												<div class="tr levelRow kpiRow" id="kpi-<?= $kpi_id; ?>"
													data-weight="<?= $remainingMilestonesWeights; ?>">
													<div class="td">&nbsp;</div>
													<div class="td"><?= $kpi_title; ?></div>
													<div class="td"><?= $kpi_weight; ?> %</div>
													<div class="td"><?= $kpi_description; ?></div>
<?php
if( !isset($viewOnly) ){
?>
													<div class="td">
														<!--a onclick="addMilestone(<?= $kpi_id; ?>);"
															class="randomTableBtn randomStrategy"><?= lang("Add_Milestone-KPI"); ?></a-->
														<a onclick="deleteStrategyItem('kpi', <?= $kpi_id; ?>);"
															class="randomTableBtn randomDanger">
															<i class="fa-solid fa-trash"></i></a>
													</div>
<?php
}
?>
												</div>
												<?php
												$qu_m_strategic_plans_milestones_sel = "SELECT * FROM  `m_strategic_plans_milestones` WHERE `kpi_id` = $kpi_id";
												$qu_m_strategic_plans_milestones_EXE = mysqli_query($KONN, $qu_m_strategic_plans_milestones_sel);
												if (mysqli_num_rows($qu_m_strategic_plans_milestones_EXE)) {
													while ($m_strategic_plans_milestones_REC = mysqli_fetch_assoc($qu_m_strategic_plans_milestones_EXE)) {
														$milestone_id = (int) $m_strategic_plans_milestones_REC['milestone_id'];
														$milestone_ref = $m_strategic_plans_milestones_REC['milestone_ref'];
														$milestone_title = $m_strategic_plans_milestones_REC['milestone_title'];
														$milestone_weight = $m_strategic_plans_milestones_REC['milestone_weight'];
														$milestone_description = $m_strategic_plans_milestones_REC['milestone_description'];
														?>
														<div class="tr levelRow milestoneRow" id="milestone-<?= $milestone_id; ?>">
															<div class="td">&nbsp;</div>
															<div class="td">- <?= $milestone_title; ?></div>
															<div class="td"><?= $milestone_weight; ?> %</div>
															<div class="td"><?= $milestone_description; ?></div>
<?php
if( !isset($viewOnly) ){
?>
															<div class="td">
																<a onclick="deleteStrategyItem('milestone', <?= $milestone_id; ?>);"
																	class="randomTableBtn randomDanger">
																	<i class="fa-solid fa-trash"></i></a>
															</div>
<?php
}
?>
															
														</div>
														<?php
													}
													?>

													<div class="tr subRow levelRow">
														<div class="td"></div>
														<div class="td"></div>
														<div class="td"></div>
<?php
if( !isset($viewOnly) ){
?>
														<div class="td"></div>
														<div class="td"></div>
<?php
}
?>
													</div>
													<?php
												}

												?>



												<?php
											}
											?>

											<div class="tr subRow subRowLimitedHeight levelRow">
												<div class="td"></div>
												<div class="td"></div>
												<div class="td"></div>
												<div class="td"></div>
												<div class="td"></div>
											</div>
											<?php
										}

										?>
									</div>
								</div>
							</div>
							<?php
						}
						?>

						<?php
					} else {
						?>
						<a onclick="addObjective(<?= $theme_id; ?>);" target="_blank" class="dataActionBtn actionBtn"
							style="width: 20%;background: var(--strategy) !important;margin:0;">
							<span class="label"><?= lang("Add_Objectives"); ?></span>
						</a>
						<?php
					}

					?>
				</div>

			</div>
			<?php
		}
		?>
	</div>
	<?php
} else {
	?>
	<div class="sss" style="width: 100%;text-align: center;">
		<br><br>
		<span><?= lang("No_Themes_have_been_created"); ?></span>
		<br><br>
		<div class="tblBtnsGroup">
			<a onclick="showModal('newTheme');" style="margin: 0 auto;background: var(--strategy) !important;"
				class="tableBtn tableBtnInfo">Add New Theme</a>
		</div>
	</div>
	<?php
}
?>


<script>

	function deleteStrategyItem(itmtype, itemId) {
		hideAllAmMnus();
		var aa = confirm('This will delete all information related to this ' + itmtype + ', Are you sure?');
		if (aa == true) {
			onCall = true;
			$.ajax({
				type: 'POST',
				url: '<?= $POINTER; ?>ext/delete_strategy_item',
				dataType: "JSON",
				data: { 'item_type': itmtype, 'item_id': itemId },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true) {
						setTimeout(function () {
							window.location.reload();
						}, 400);
					} else {
						makeSiteAlert('err', responser[0].message);
					}
				},
				error: function () {
					onCall = false;
					end_loader('article');
					makeSiteAlert('err', "General Error, please try later !");
				}
			});
		}

	}

	function updateThemesOrder() {
		hideAllAmMnus();
		//collect new order
		var formdata = new FormData();
		$('.orderedItem').each(function () {
			var thsPid = getInt($(this).attr('data-id'));
			var thscurOrder = getInt($(this).attr('data-order'));
			formdata.append('theme_ids[]', thsPid);
			formdata.append('order_nos[]', thscurOrder);
		});

		if (onCall == false) {
			onCall = true;
			$.ajax({
				type: 'POST',
				url: '<?= $POINTER; ?>ext/update_themes_list',
				dataType: "JSON",
				data: formdata,
				processData: false,
				contentType: false,
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true) {
						//apply changes on local view


					} else {
						makeSiteAlert('err', responser[0].message);
					}
				},
				error: function () {
					onCall = false;
					end_loader('article');
					makeSiteAlert('err', "General Error, please try later !");
				}
			});
		}

	}
	function moveUp(pId) {
		var curOrder = getInt($('#priority-' + pId).attr('data-order'));
		var nwOrder = curOrder - 1;
		if (nwOrder > 0) {
			//change view
			$('#priority-' + pId).insertBefore('.order-' + nwOrder);
			//order items
			var nwOrder = 1;
			$('.orderedItem').each(function () {
				var thsPid = getInt($(this).attr('data-id'));
				var thscurOrder = getInt($(this).attr('data-order'));
				$(this).removeClass('order-' + thscurOrder);
				$(this).addClass('order-' + nwOrder);
				$(this).attr('data-order', nwOrder);
				nwOrder++;
			});
			updateThemesOrder();

		}
	}
	function moveDown(pId) {
		var curOrder = getInt($('#priority-' + pId).attr('data-order'));
		var nwOrder = curOrder + 1;

		if (nwOrder > totalThemes) {
			//nwOrder = totalThemes;
			console.log(totalThemes);
		} else {
			//change view
			$('#priority-' + pId).insertAfter('.order-' + nwOrder);
			//order items
			var nwOrder = 1;
			$('.orderedItem').each(function () {
				var thsPid = getInt($(this).attr('data-id'));
				var thscurOrder = getInt($(this).attr('data-order'));
				$(this).removeClass('order-' + thscurOrder);
				$(this).addClass('order-' + nwOrder);
				$(this).attr('data-order', nwOrder);
				nwOrder++;
			});
			updateThemesOrder();
		}


	}
</script>


<script>
	function toggleTheme(pId) {
		hideAllAmMnus();
		$('#priority-' + pId + ' .priorityBody').toggleClass('priorityBodyClosed');
		$('#priority-' + pId + ' .opCloser').toggleClass('fa-caret-right');
		$('#priority-' + pId + ' .opCloser').toggleClass('fa-caret-down');
	}
	function editTheme(pId) {
		hideAllAmMnus();
		var remAllWeights = getInt(<?= $remPlanWeight; ?>);
		start_loader();
		//get data from server

		if (pId != 0) {
			//get data from server
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $POINTER; ?>ext/get_themes_list',
					dataType: "JSON",
					method: "POST",
					data: { "theme_id": pId },
					success: function (RES) {
						var itm = RES[0];
						activeRequest = false;
						end_loader('article');
						if (itm['success'] == true) {
							var pData = itm['data'];
							$('.edit-theme_id').val(pData[0].theme_id);
							$('.edit-theme_title').val(pData[0].theme_title);
							$('.edit-theme_description').val(pData[0].theme_description);
							var thsWeight = getInt(pData[0].theme_weight);

							var totWeight = thsWeight + remAllWeights;
							$('.edit-theme_weight').attr('max', totWeight);
							$('.edit-theme_weight').val(thsWeight);
							$('#rangValuer-11').text(thsWeight);
							showModal('editThemeModal');
						}
					},
					error: function () {
						activeRequest = false;
						end_loader('article');
						alert("Err-327657");
					}
				});
			}
		}

	}
	function addObjective(pId) {
		hideAllAmMnus();
		var remWght = getInt($('#priority-' + pId).attr('data-weight'));
		$('.objectives-theme_id').val(pId);
		$('.objectives-objective_weight').attr('max', remWght);
		$('.objectives-objective_weight').val(0);
		$('.objectives-objective_weight').change();
		showModal('newObjective');
	}
	function addKPI(gId) {
		hideAllAmMnus();
		var remWght = getInt($('#objective-' + gId).attr('data-weight'));
		$('.kpis-objective_id').val(gId);
		$('.kpis-kpi_weight').attr('max', remWght);
		$('.kpis-kpi_weight').val(0);
		$('.kpis-kpi_weight').change();
		showModal('newKPI');
	}
	function addMilestone(tId) {
		hideAllAmMnus();
		var remWght = getInt($('#kpi-' + tId).attr('data-weight'));
		$('.milestones-kpi_id').val(tId);
		$('.milestones-milestone_weight').attr('max', remWght);
		$('.milestones-milestone_weight').val(0);
		$('.milestones-milestone_weight').change();
		showModal('newMilestone');
	}

</script>


