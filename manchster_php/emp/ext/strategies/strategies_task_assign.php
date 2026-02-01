<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Strategies_List");

$pageDataController = $POINTER . "ext/get_strategies_list";
$addNewStrategyController = $POINTER . "ext/add_strategies_list";
$updateStrategyController = $POINTER . "ext/update_strategies_list";

$pageId = 6950;
$subPageId = 200;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Tasks_Assignation", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		//include('strategies_list_nav.php');
		?>
	</div>

	<div class="pageOptions" style="height: 1em;"></div>
</div>


<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Strategy_Plan"); ?></div>
				<div class="th"><?= lang("Milestone-KPI"); ?></div>
				<div class="th"><?= lang("Date"); ?></div>
				<div class="th"><?= lang("Assign_to"); ?></div>
				<div class="th">&nbsp;</div>
			</div>
		</div>
		<div class="tableBody" id="">
			<?php
	$qu_m_strategic_plans_internal_maps_sel = "SELECT `m_strategic_plans_internal_maps`.* ,
														`m_strategic_plans`.`plan_title` AS `plan_title`,
														`m_strategic_plans_themes`.`theme_title` AS `theme_title`,
														`m_strategic_plans_objectives`.`objective_title` AS `objective_title`,
														`m_strategic_plans_kpis`.`kpi_title` AS `kpi_title`,
														`m_strategic_plans_milestones`.`milestone_title` AS `milestone_title`,
														`m_strategic_plans_milestones`.`milestone_description` AS `milestone_description`


												FROM  `m_strategic_plans_internal_maps` 
												INNER JOIN `m_strategic_plans` ON `m_strategic_plans_internal_maps`.`plan_id` = `m_strategic_plans`.`plan_id`
												INNER JOIN `m_strategic_plans_themes` ON `m_strategic_plans_internal_maps`.`theme_id` = `m_strategic_plans_themes`.`theme_id`
												INNER JOIN `m_strategic_plans_objectives` ON `m_strategic_plans_internal_maps`.`objective_id` = `m_strategic_plans_objectives`.`objective_id`
												INNER JOIN `m_strategic_plans_kpis` ON `m_strategic_plans_internal_maps`.`kpi_id` = `m_strategic_plans_kpis`.`kpi_id`
												INNER JOIN `m_strategic_plans_milestones` ON `m_strategic_plans_internal_maps`.`milestone_id` = `m_strategic_plans_milestones`.`milestone_id`

												WHERE  ((`m_strategic_plans_internal_maps`.`is_task_assigned` = 0) AND (`m_strategic_plans_internal_maps`.`department_id` = $empDeptId))";
	$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
	if(mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE)){
		while($dataRec = mysqli_fetch_assoc($qu_m_strategic_plans_internal_maps_EXE)){
			$local_map_id = ( int ) $dataRec['local_map_id'];

			$dater = " ".getDateOnly($dataRec['start_date'])." -> ".getDateOnly($dataRec['end_date']);
			
		?>
		<div class="tr levelRow" id="dataRow-<?=$local_map_id; ?>">
			<div class="td"><?=$dataRec['plan_title']; ?> - <?=$dataRec['theme_title']; ?> - <?=$dataRec['objective_title']; ?> - <?=$dataRec['kpi_title']; ?></div>
			<div class="td"><?=$dataRec['milestone_title']; ?><br><?=$dataRec['milestone_description']; ?></div>
			<div class="td"><?=$dater; ?></div>
			<div class="td">
				<select id="assigned_employee-<?=$local_map_id; ?>" style="display: block;width: 100%;padding: 0.5em;font-family: inherit;font-size: 1.1em;">
<?php
	$qu_employees_list_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ((`department_id` = $empDeptId) AND (`is_deleted` = 0))";
	$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
	if(mysqli_num_rows($qu_employees_list_EXE)){
		?>
		<option value="0" selected disabled><?=lang("Please_Select"); ?></option>
		<?php
		while($employees_list_REC = mysqli_fetch_assoc($qu_employees_list_EXE)){
			$Eid = ( int ) $employees_list_REC['employee_id'];
			$namer = $employees_list_REC['namer'];
		?>
		<option value="<?=$Eid; ?>"><?=$namer; ?></option>
		<?php
		}
	}

?>
				</select>
			</div>
			<div class="td">
				
			<a onclick="assignMapping(<?=$local_map_id; ?>);" class="dataActionBtn hasTooltip">
					<i class="fa-regular fa-save"></i>
					<div class="tooltip"><?= lang("Save"); ?></div>
				</a>

			</div>
		</div>
		
		<?php
		}
	}

			?>
		</div>
	</div>
</div>






<script>
	function assignMapping(localMapId){
		if( localMapId != 0 ){
			var empId = getInt( $('#assigned_employee-' + localMapId).val() );
			if( empId != 0 ){
				var aa = confirm('This will add a task to the selected employee, Are you sure?');
				if (aa == true) {
					onCall = true;
					$.ajax({
						type: 'POST',
						url: '<?= $POINTER; ?>ext/assign_mapping_task',
						dataType: "JSON",
						data: { 'local_map_id': localMapId, 'employee_id': empId },
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
			} else {
				makeSiteAlert('err', "Please Select an Employee");
			}

		}

	}
</script>


<?php
include("app/footer.php");
?>










<script>
	function afterFormSubmission() {
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
