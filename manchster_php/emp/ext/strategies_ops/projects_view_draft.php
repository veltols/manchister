<?php


//get remaining plan weights
$remPlanWeight = 100;

$qu_sWq_sel = "SELECT SUM(`milestone_weight`) FROM  `m_operational_projects_milestones` WHERE `project_id` = $project_id";
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
			<a href="<?= $POINTER; ?>ext/strategies/projects_list/" style="margin-inline-end: 0.3em;"><i
					class="fa-solid fa-arrow-left"></i></a>
			<?= $project_name; ?>
		</h1>
	</div>
	<div class="pageNav">

	</div>

	<div class="pageOptions">

	<a class="pageLink" onclick="publishPlan();"
			style="background: #FFF !important;color: var(--strategy) !important;border: 2px solid;width: 33%;">
			<div class="linkTxt"><?= lang("Publish_Project"); ?></div>
		</a>
	</div>
</div>

<script>
	var isNewMilestoneAdd = false;
	function publishPlan(){
		var aa = confirm('This will publish <?=$project_name; ?>, Are you sure?');
		
		
					alert("Project Publish Failed");
/*
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
					alert("Project Publish Failed");
					
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
		*/
	}

</script>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Project_Overview', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('Plan_Overview', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLinkedKPIs"><?= lang('Linked_KPIs', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getProjectMilestones"><?= lang('Milestones', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('ext/strategies_ops/projects_view/project_details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('ext/strategies_ops/projects_view/plan_details.php');
				?>
			</div>
			
			<div class="tabContent">
				<?php
				include('ext/strategies_ops/projects_view/linked_kpis.php');
				?>
			</div>
			
			<div class="tabContent">
				<?php
				include('ext/strategies_ops/projects_view/project_milestones.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('ext/strategies_ops/projects_view/logs.php');
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
<div class="modal modal-lg" id="newKPIlink">
	<div class="modalHeader">
		<h1><?= lang("new_KPI_link", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newKPIlinkForm">

		
			<!-- ELEMENT START -->
			<div class="col-2 localMapping-projects">
				<div class="formElement">
					<label><?= lang("Project", "AAR"); ?></label>
					<select class="inputer mapping-project_id" data-name="project_id" data-den="0" data-req="1"
						data-type="text">
						<option value="<?= $project_id; ?>"><?= $project_name; ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
		
			<!-- ELEMENT START -->
			<div class="col-2 localMapping-plans">
				<div class="formElement">
					<label><?= lang("Plan", "AAR"); ?></label>
					<select class="inputer mapping-plan_id" data-name="plan_id" data-den="0" data-req="1"
						data-type="text">
						<option value="<?= $plan_id; ?>"><?= $plan_title; ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

		
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
					mappingThemesChanged();
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
			<div class="col-1 localMapping-kpis itemHidden">
				<div class="formElement">
					<label><?= lang("KPI", "AAR"); ?></label>
					<select class="inputer mapping-kpi_id" data-name="kpi_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_strategic_plans_kpis` WHERE ( (`plan_id` = $plan_id) AND (`department_id` = $department_id) )";
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
			 


			<!-- ELEMENT START -->
			<div class="col-1">
			</div>
			<!-- ELEMENT END -->
			 
			


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newKPIlinkForm', '<?= $linkProjectKpiController; ?>');"><?= lang("Link_KPI_to_Project", "AAR"); ?></button>
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
<div class="modal" id="newMilestone">
	<div class="modalHeader">
		<h1><?= lang("Add_new_Milestone", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newMilestoneForm">


	
			<!-- ELEMENT START -->
			<div class="col-1 localMapping-projects">
				<div class="formElement">
					<label><?= lang("Project", "AAR"); ?></label>
					<select class="inputer mapping-project_id" data-name="project_id" data-den="0" data-req="1"
						data-type="text">
						<option value="<?= $project_id; ?>"><?= $project_name; ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Linked_KPI", "AAR"); ?></label>
					<select class="inputer" data-name="kpi_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `m_operational_projects_kpis` WHERE `project_id` = $project_id ORDER BY `linked_kpi_id` ASC";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['linked_kpi_id'];
								$thsKpi_id = (int) $qSel_REC['kpi_id'];

								$nnn = "";
									$qu_m_strategic_plans_kpis_sel = "SELECT `kpi_title` FROM  `m_strategic_plans_kpis` WHERE `kpi_id` = $thsKpi_id";
									$qu_m_strategic_plans_kpis_EXE = mysqli_query($KONN, $qu_m_strategic_plans_kpis_sel);
									if(mysqli_num_rows($qu_m_strategic_plans_kpis_EXE)){
										$m_strategic_plans_kpis_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_kpis_EXE);
										$nnn = $m_strategic_plans_kpis_DATA['kpi_title'];
									}
								?>
								<option value="<?= $thsKpi_id; ?>"><?= $nnn; ?></option>
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
					<label><?= lang("owner", "AAR"); ?></label>
					<select class="inputer newKpi-employee_id" data-name="employee_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_qSel_sel = "SELECT * FROM  `employees_list` WHERE ( `department_id` = $empDeptId ) ORDER BY `first_name` ASC ";
						$qu_qSel_EXE = mysqli_query($KONN, $qu_qSel_sel);
						if (mysqli_num_rows($qu_qSel_EXE)) {
							while ($qSel_REC = mysqli_fetch_assoc($qu_qSel_EXE)) {
								$idd = (int) $qSel_REC['employee_id'];
								$nnn = $qSel_REC['first_name'].' '.$qSel_REC['first_name'];
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
					<label><?= lang("milestone_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="milestone_title" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("milestone_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="milestone_description" data-den="" data-req="1"
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
				<div class="formElement">
					<label><?= lang("Weight", "AAR"); ?> <span>( <i id="rangValuer-1">00</i> % )</span></label>
					<input type="range" min="0" step="5" value="0" max="<?= $remPlanWeight; ?>"
						class="inputer rangeRanger rangeRanger-1" data-range-id="1" data-name="milestone_weight"
						data-den="" data-req="1" data-type="int" list="rangeList">
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
						onclick="isNewMilestoneAdd=true;submitForm('newMilestoneForm', '<?= $addMilestoneController; ?>');"><?= lang("Add_Milestone", "AAR"); ?></button>
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
 




<script>
	function afterFormSubmission() {
		closeModal();
		if( isNewMilestoneAdd == true ){
			clearForm('newMilestoneForm');
			getProjectMilestones();
		}
		setTimeout(function () {
			//5555555555555555555
			//window.location.reload();
		}, 400);
	}

	
	

function deleteProjectItem(itmtype, itemId) {
	hideAllAmMnus();
	var aa = confirm('This will delete all information related to this ' + itmtype + ', Are you sure?');
	if (aa == true) {
		onCall = true;
		$.ajax({
			type: 'POST',
			url: '<?= $POINTER; ?>ext/delete_project_item',
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

</script>
<script>
	$('.rangeRanger').on('change', function () {
		var thsId = getInt($(this).attr('data-range-id'));
		var thsValue = getInt($(this).val());
		$('#rangValuer-' + thsId).text(thsValue);
	});
</script>
<script>
	function mappingThemesChanged(){
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