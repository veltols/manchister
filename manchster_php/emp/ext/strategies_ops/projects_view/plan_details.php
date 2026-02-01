<div class="strategyPlanView">
	<div class="planVision planElements">
		<h1><?=lang("Vision", "AAR"); ?></h1>
		<p><?= $plan_vision; ?></p>
	</div>
	<div class="planMission planElements">
		<h1><?=lang("Mission", "AAR"); ?></h1>
		<p><?= $plan_mission; ?></p>
	</div>
	<div class="planValues planElements">
		<h1><?=lang("Values", "AAR"); ?></h1>
		<p><?= $plan_values; ?></p>
	</div>


<hr>
	<div class="planThemes">
		<?php
			$qu_m_strategic_plans_themes_sel = "SELECT * FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id ORDER BY `order_no` ASC";
			$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
			if(mysqli_num_rows($qu_m_strategic_plans_themes_EXE)){
				while($m_strategic_plans_themes_REC = mysqli_fetch_assoc($qu_m_strategic_plans_themes_EXE)){
					$theme_id = ( int ) $m_strategic_plans_themes_REC['theme_id'];
					$theme_ref = $m_strategic_plans_themes_REC['theme_ref'];
					$theme_title = $m_strategic_plans_themes_REC['theme_title'];
					$theme_description = $m_strategic_plans_themes_REC['theme_description'];
					$order_no = ( int ) $m_strategic_plans_themes_REC['order_no'];
					$theme_weight = ( int ) $m_strategic_plans_themes_REC['theme_weight'];
					$plan_id = ( int ) $m_strategic_plans_themes_REC['plan_id'];
					$added_by = ( int ) $m_strategic_plans_themes_REC['added_by'];
					$added_date = $m_strategic_plans_themes_REC['added_date'];
				?>
				<div class="themePillar">
					<div class="themeTitle" title="<?=$theme_description; ?>">
						<?=$theme_title; ?><br>( <?=$theme_weight; ?> % )
					</div>

					<div class="themeObjectives">
						<?php
							$qu_m_strategic_plans_objectives_sel = "SELECT * FROM  `m_strategic_plans_objectives` WHERE `theme_id` = $theme_id ORDER BY `objective_id` ASC";
							$qu_m_strategic_plans_objectives_EXE = mysqli_query($KONN, $qu_m_strategic_plans_objectives_sel);
							if(mysqli_num_rows($qu_m_strategic_plans_objectives_EXE)){
								while($m_strategic_plans_objectives_REC = mysqli_fetch_assoc($qu_m_strategic_plans_objectives_EXE)){
									$objective_id = ( int ) $m_strategic_plans_objectives_REC['objective_id'];
									$objective_ref = $m_strategic_plans_objectives_REC['objective_ref'];
									$objective_title = $m_strategic_plans_objectives_REC['objective_title'];
									$objective_description = $m_strategic_plans_objectives_REC['objective_description'];
									$objective_type_id = ( int ) $m_strategic_plans_objectives_REC['objective_type_id'];
									$order_no = ( int ) $m_strategic_plans_objectives_REC['order_no'];
									$objective_weight = ( int ) $m_strategic_plans_objectives_REC['objective_weight'];
									$theme_id = ( int ) $m_strategic_plans_objectives_REC['theme_id'];
									$plan_id = ( int ) $m_strategic_plans_objectives_REC['plan_id'];
									$added_by = ( int ) $m_strategic_plans_objectives_REC['added_by'];
									$added_date = $m_strategic_plans_objectives_REC['added_date'];
								?>
								
								<div class="objective">
									<div class="subthemeTitle" title="<?=$objective_description; ?>">
										<?=$objective_title; ?><br>( <?=$objective_weight; ?> % )
									</div>

									<div class="objectiveKPIs">
										
										<?php
											$qu_m_strategic_plans_kpis_sel = "SELECT * FROM  `m_strategic_plans_kpis` WHERE `objective_id` = $objective_id ORDER BY `kpi_id` ASC";
											$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
											if(mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)){
												while($m_strategic_plans_kpis_REC = mysqli_fetch_assoc($qu_m_strategic_plans_kpis_EXE)){
													$kpi_id = ( int ) $m_strategic_plans_kpis_REC['kpi_id'];
													$kpi_ref = $m_strategic_plans_kpis_REC['kpi_ref'];
													$kpi_code = $m_strategic_plans_kpis_REC['kpi_code'];
													$data_source = $m_strategic_plans_kpis_REC['data_source'];
													$kpi_title = $m_strategic_plans_kpis_REC['kpi_title'];
													$kpi_description = $m_strategic_plans_kpis_REC['kpi_description'];
													$kpi_frequncy_id = $m_strategic_plans_kpis_REC['kpi_frequncy_id'];
													$kpi_formula = $m_strategic_plans_kpis_REC['kpi_formula'];
													$department_id = ( int ) $m_strategic_plans_kpis_REC['department_id'];
													$kpi_progress = ( int ) $m_strategic_plans_kpis_REC['kpi_progress'];
													$order_no = ( int ) $m_strategic_plans_kpis_REC['order_no'];
													$kpi_weight = ( int ) $m_strategic_plans_kpis_REC['kpi_weight'];
													$objective_id = ( int ) $m_strategic_plans_kpis_REC['objective_id'];
													$theme_id = ( int ) $m_strategic_plans_kpis_REC['theme_id'];
													$plan_id = ( int ) $m_strategic_plans_kpis_REC['plan_id'];
													$added_by = ( int ) $m_strategic_plans_kpis_REC['added_by'];
													$added_date = $m_strategic_plans_kpis_REC['added_date'];
												?>
												<div class="objectiveKPIView"><?=$kpi_ref; ?> - <?=$kpi_title; ?></div>
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