<?php


//get remaining plan weights
$remPlanWeight = 100;

$qu_sWq_sel = "SELECT SUM(`theme_weight`) FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id";
$qu_sWq_EXE = mysqli_query($KONN, $qu_sWq_sel);
if (mysqli_num_rows($qu_sWq_EXE)) {
	$sWq_DATA = mysqli_fetch_array($qu_sWq_EXE);
	$theme_weight = (int) $sWq_DATA[0];
	$remPlanWeight = 100 - $theme_weight;
}

?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/list/" style="margin-inline-end: 0.3em;"><i
					class="fa-solid fa-arrow-left"></i></a>
			<?= $plan_title; ?>
		</h1>
	</div>
	<div class="pageNav">

	</div>

	<div class="pageOptions">

	<a class="pageLink" onclick="publishPlan();"
			style="background: #FFF !important;color: var(--strategy) !important;border: 2px solid;width: 33%;">
			<div class="linkTxt"><?= lang("Publish_plan"); ?></div>
		</a>
	</div>
</div>

<script>
	function publishPlan(){
		var aa = confirm('This will publish <?=$plan_title; ?>, Are you sure?');
		if (aa == true) {
			onCall = true;
			$.ajax({
				type: 'POST',
				url: '<?= $POINTER; ?>ext/publish_strategy_plan',
				dataType: "JSON",
				data: { 'plan_id': <?=$plan_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true) {
						setTimeout(function () {
							window.location.href = "<?= $POINTER; ?>ext/strategies/list/";
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
</script>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Plan_Overview', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('Themes/Pillars', 'ARR'); ?></div>
			<!--div class="tabTitle" extra-action="getlocalMaps"><?= lang('Internal_Mapping', 'ARR'); ?></div-->
			<div class="tabTitle" extra-action="getExternalMaps"><?= lang('External_Mapping', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('ext/strategies/strategies_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('ext/strategies/strategies_view/themes.php');
				?>
			</div>
			<?php
			/*
			<div class="tabContent">
				<?php
				include('ext/strategies/strategies_view/mapper_internal.php');
				?>
			</div>
			*/
			?>
			<div class="tabContent">
				<?php
				include('ext/strategies/strategies_view/mapper_external.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('ext/strategies/strategies_view/logs.php');
				?>
			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>



<!-- Modals START -->
<div class="modal" id="newTheme">
	<div class="modalHeader">
		<h1><?= lang("Add_Theme/Pillar", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newThemeForm">


			<input type="hidden" class="inputer" data-name="plan_id" value="<?= $plan_id; ?>" data-den="0" data-req="1"
				data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Theme/Pillar_Title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="theme_title" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Theme/Pillar_Description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="theme_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-1">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="<?= $remPlanWeight; ?>"
						class="inputer rangeRanger rangeRanger-1" data-range-id="1" data-name="theme_weight"
						data-den="" data-req="1" data-type="text" list="rangeList">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newThemeForm', '<?= $addThemeController; ?>');"><?= lang("Add_Theme", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->




<!-- Modals START -->
<div class="modal" id="editThemeModal">
	<div class="modalHeader">
		<h1><?= lang("Edit_Theme/Pillar", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editThemeForm">


			<input type="hidden" class="inputer edit-theme_id" data-name="theme_id" value="0" data-den="0"
				data-req="1" data-type="hidden">
			<input type="hidden" class="inputer edit-theme_id" data-name="edit_priority" value="1" data-den="0"
				data-req="1" data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Strategic_Theme/Pillar_Title", "AAR"); ?></label>
					<input type="text" class="inputer edit-theme_title" data-name="theme_title" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Strategic_Theme/Pillar_Description", "AAR"); ?></label>
					<textarea type="text" class="inputer edit-theme_description" data-name="theme_description"
						data-den="" data-req="1" data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-11">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="<?= $remPlanWeight; ?>"
						class="inputer edit-theme_weight rangeRanger rangeRanger-11" data-range-id="11"
						data-name="theme_weight" data-den="" data-req="1" data-type="text" list="rangeList">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('editThemeForm', '<?= $POINTER; ?>ext/update_themes_list');"><?= lang("Update_Theme", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->







<?php
/*
<!-- Modals START -->
<div class="modal modal-lg" id="newMapDept">
	<div class="modalHeader">
		<h1><?= lang("new_internal_mapping", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newMapDeptForm">

		
		
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Department", "AAR"); ?></label>
					<select class="inputer mapping-department_id" data-name="department_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `employees_list_departments` ORDER BY `department_name` ASC ";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['department_id'];
								$nnn = $qSel_REC['department_name'];
								?>
								<option value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
		
		
			<!-- ELEMENT START -->
			<div class="col-1 localMapping-themes">
				<div class="formElement">
					<label><?= lang("Theme", "AAR"); ?></label>
					<select class="inputer mapping-theme_id" data-name="theme_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_m_strategic_plans_themes_sel = "SELECT * FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id";
						$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
						if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
							while ($m_strategic_plans_themes_REC = mysqli_fetch_assoc($qu_m_strategic_plans_themes_EXE)) {
								$pId = (int) $m_strategic_plans_themes_REC['theme_id'];
								$pT = $m_strategic_plans_themes_REC['theme_title'];
								?>
								<option value="<?= $pId; ?>"><?= $pT; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			 <script>
				$('.mapping-theme_id').on('change', function(){
					mappingPriotityChanged();
				});
			 </script>
		
			<!-- ELEMENT START -->
			<div class="col-1 localMapping-objectives itemHidden">
				<div class="formElement">
					<label><?= lang("Objective", "AAR"); ?></label>
					<select class="inputer mapping-objective_id" data-name="objective_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_objectives` WHERE `plan_id` = $plan_id";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['objective_id'];
								$nnn = $qSel_REC['objective_title'];
								$thsPid = $qSel_REC['theme_id'];
								?>
								<option class="objective-<?= $thsPid; ?>" value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			 <script>
				$('.mapping-objective_id').on('change', function(){
					mappingObjectiveChanged();
				});
			 </script>
		
			<!-- ELEMENT START -->
			<div class="col-2 localMapping-kpis itemHidden">
				<div class="formElement">
					<label><?= lang("KPI", "AAR"); ?></label>
					<select class="inputer mapping-kpi_id" data-name="kpi_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_kpis` WHERE `plan_id` = $plan_id";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['kpi_id'];
								$nnn = $qSel_REC['kpi_title'];
								$thsGid = $qSel_REC['objective_id'];
								?>
								<option class="kpi-<?= $thsGid; ?>" value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			 <script>
				$('.mapping-kpi_id').on('change', function(){
					mappingKPIChanged();
				});
			 </script>
		
			<!-- ELEMENT START -->
			<div class="col-2 localMapping-milestones itemHidden">
				<div class="formElement">
					<label><?= lang("Milestone-KPI", "AAR"); ?></label>
					<select class="inputer mapping-milestone_id" data-name="milestone_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_milestones` WHERE `plan_id` = $plan_id";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['milestone_id'];
								$nnn = $qSel_REC['milestone_title'];
								$thsTid = $qSel_REC['kpi_id'];
								?>
								<option class="milestone-<?= $thsTid; ?>" value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
			</div>
			<!-- ELEMENT END -->
			 


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date start_date" data-name="start_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_date", "AAR"); ?></label>
					<input type="text" class="inputer has_future_date end_date" data-name="end_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->
			


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newMapDeptForm', '<?= $addlocalMapController; ?>');"><?= lang("Add_Mapping", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
*/
?>









<!-- Modals START -->
<div class="modal modal-lg" id="newExtMap">
	<div class="modalHeader">
		<h1><?= lang("New_External_mapping", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newExtMapForm">

		
		

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Objective", "AAR"); ?></label>
					<select class="inputer" data-name="objective_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_objectives` WHERE `plan_id` = $plan_id ORDER BY `objective_ref` ASC";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['objective_id'];
								$nnn = $qSel_REC['objective_ref'].' '.$qSel_REC['objective_title'];
								$thsPid = $qSel_REC['theme_id'];
								?>
								<option class="objective-<?= $thsPid; ?>" value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			 


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("External_Entity", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="external_entity_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->
			 

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="map_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date start_date" data-name="start_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_date", "AAR"); ?></label>
					<input type="text" class="inputer has_future_date end_date" data-name="end_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->
			


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newExtMapForm', '<?= $addExternalMapController; ?>');"><?= lang("Add_Mapping", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>






<!-- Modals START -->
<div class="modal" id="newObjective">
	<div class="modalHeader">
		<h1><?= lang("Add_new_SO", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newObjectiveForm">




			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Theme/Pillar", "AAR"); ?></label>
					<select class="inputer objectives-theme_id" data-name="theme_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_m_strategic_plans_themes_sel = "SELECT * FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id";
						$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
						if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
							while ($m_strategic_plans_themes_REC = mysqli_fetch_assoc($qu_m_strategic_plans_themes_EXE)) {
								$pId = (int) $m_strategic_plans_themes_REC['theme_id'];
								$pT = $m_strategic_plans_themes_REC['theme_title'];
								?>
								<option value="<?= $pId; ?>"><?= $pT; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("SO_Type", "AAR"); ?></label>
					<select class="inputer objectives-objective_type_id" data-name="objective_type_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_m_strategic_plans_themes_sel = "SELECT * FROM  `m_strategic_plans_objective_types`";
						$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
						if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
							while ($m_strategic_plans_themes_REC = mysqli_fetch_assoc($qu_m_strategic_plans_themes_EXE)) {
								$pId = (int) $m_strategic_plans_themes_REC['objective_type_id'];
								$pT = $m_strategic_plans_themes_REC['objective_type_name'];
								?>
								<option value="<?= $pId; ?>"><?= $pT; ?></option>
								<?php
							}
						}

						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("objective_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="objective_title" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("objective_Description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="objective_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-2">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="0"
						class="inputer objectives-objective_weight rangeRanger rangeRanger-2" data-range-id="2"
						data-name="objective_weight" data-den="" data-req="1" data-type="text" list="rangeList">
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newObjectiveForm', '<?= $addObjectiveController; ?>');"><?= lang("Add_Objective", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->









<!-- Modals START -->
<div class="modal" id="newKPI">
	<div class="modalHeader">
		<h1><?= lang("Add_new_KPI", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newKPIForm">


			<input type="hidden" class="inputer kpis-objective_id" data-name="objective_id" data-den="0" value="0" data-req="1"
				data-type="hidden">


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_code", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="kpi_code" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Data_Source", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="data_source" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="kpi_title" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="kpi_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->



		
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_owner", "AAR"); ?></label>
					<select class="inputer newKpi-department_id" data-name="department_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `employees_list_departments` ORDER BY `department_name` ASC ";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['department_id'];
								$nnn = $qSel_REC['department_name'];
								?>
								<option value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>

		
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_frequncy", "AAR"); ?></label>
					<select class="inputer newKpi-kpi_frequncy_id" data-name="kpi_frequncy_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_kpis_freqs` ORDER BY `kpi_frequncy_name` ASC ";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['kpi_frequncy_id'];
								$nnn = $qSel_REC['kpi_frequncy_name'];
								?>
								<option value="<?= $idd; ?>"><?= $nnn; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("KPI_formula", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="kpi_formula" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-3">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="0"
						class="inputer kpis-kpi_weight rangeRanger rangeRanger-3" data-range-id="3"
						data-name="kpi_weight" data-den="" data-req="1" data-type="text" list="rangeList">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newKPIForm', '<?= $addKPIController; ?>');"><?= lang("Add_KPI", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->





<?php
/*

<!-- Modals START -->
<div class="modal" id="newMilestone">
	<div class="modalHeader">
		<h1><?= lang("Add_new_Milestone-KPI", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newMilestoneForm">


			<input type="hidden" class="inputer milestones-kpi_id" data-name="kpi_id" data-den="0" value="0"
				data-req="1" data-type="hidden">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("milestone_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="milestone_title" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("milestone_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="milestone_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-33">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="0"
						class="inputer milestones-milestone_weight rangeRanger rangeRanger-33" data-range-id="33"
						data-name="milestone_weight" data-den="" data-req="1" data-type="text" list="rangeList">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newMilestoneForm', '<?= $addMilestoneController; ?>');"><?= lang("Add_Milestone", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->
 */
 ?>

<script>
	function afterFormSubmission() {
		closeModal();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
<script>
	$('.rangeRanger').on('change', function () {
		var thsId = getInt($(this).attr('data-range-id'));
		var thsValue = getInt($(this).val());
		$('#rangValuer-' + thsId).text(thsValue);
	});
</script>
<script>
	function mappingPriotityChanged(){
		//localMapping-objectives
		//.mapping-objective_id
		var theme_id = getInt($('.mapping-theme_id').val());
		if( theme_id != 0 ){
			$('.mapping-objective_id option').each( function(){
				$(this).hide();
			} );
			$('.mapping-objective_id .objective-' + theme_id).each( function(){
				$(this).show();
			} );
			$('.mapping-objective_id').val(0);
			$('.localMapping-objectives').removeClass('itemHidden');

			//hide the rest
			$('.localMapping-kpis').addClass('itemHidden');
			$('.localMapping-milestones').addClass('itemHidden');

		} else {
			$('.mapping-objective_id').val(0);
			$('.mapping-kpi_id').val(0);
			$('.mapping-milestone_id').val(0);
			$('.localMapping-objectives').addClass('itemHidden');
			$('.localMapping-kpis').addClass('itemHidden');
			$('.localMapping-milestones').addClass('itemHidden');
		}
	}

	function mappingObjectiveChanged(){
		//localMapping-kpis
		//.mapping-kpi_id
		var objective_id = getInt($('.mapping-objective_id').val());
		if( objective_id != 0 ){
			$('.mapping-kpi_id option').each( function(){
				$(this).hide();
			} );
			$('.mapping-kpi_id .kpi-' + objective_id).each( function(){
				$(this).show();
			} );
			$('.mapping-kpi_id').val(0);
			$('.localMapping-kpis').removeClass('itemHidden');

			//hide the rest
			$('.localMapping-milestones').addClass('itemHidden');

		} else {
			$('.mapping-kpi_id').val(0);
			$('.mapping-milestone_id').val(0);
			$('.localMapping-kpis').addClass('itemHidden');
			$('.localMapping-milestones').addClass('itemHidden');
		}

	}
	function mappingKPIChanged(){
		//localMapping-milestones
		//.mapping-milestone_id

		var kpi_id = getInt($('.mapping-kpi_id').val());
		if( kpi_id != 0 ){
			$('.mapping-milestone_id option').each( function(){
				$(this).hide();
			} );
			$('.mapping-milestone_id .milestone-' + kpi_id).each( function(){
				$(this).show();
			} );
			$('.mapping-milestone_id').val(0);
			$('.localMapping-milestones').removeClass('itemHidden');

		} else {
			$('.mapping-milestone_id').val(0);
			$('.localMapping-milestones').addClass('itemHidden');
		}
	}
</script>



<datalist id="rangeList">
	    <option>10</option>
	    <option>20</option>
	    <option>30</option>
	    <option>40</option>
	    <option>50</option>
	    <option>60</option>
	    <option>70</option>
	    <option>80</option>
	    <option>90</option>
	    <option>100</option>
</datalist>