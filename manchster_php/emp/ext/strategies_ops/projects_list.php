<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Operational_Planning");

$pageDataController = $POINTER . "ext/get_strategies_list";
$addNewStrategyController = $POINTER . "ext/add_strategies_list";
$updateStrategyController = $POINTER . "ext/update_strategies_list";

$pageId = 10003;
$subPageId = 200;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Projects_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		//include('strategies_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" href="<?= $POINTER; ?>ext/strategies/projects_new/"
			style="background: #FFF !important;color: var(--strategy) !important;border: 2px solid;width: 33%;">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("create_new_Project"); ?></div>
		</a>
	</div>
</div>







<?php
$qu_m_operational_projects_sel = "SELECT * FROM  `m_operational_projects` WHERE `department_id` = $empDeptId ORDER BY `project_id` DESC";
$qu_m_operational_projects_EXE = mysqli_query($KONN, $qu_m_operational_projects_sel);
if (mysqli_num_rows($qu_m_operational_projects_EXE)) {
	?>
	<div class="strategyContainer">
		<?php
		while ($m_operational_projects_REC = mysqli_fetch_assoc($qu_m_operational_projects_EXE)) {
			$project_id = (int) $m_operational_projects_REC['project_id'];
			$project_name = "" . $m_operational_projects_REC['project_name'];
			$project_period = "". $m_operational_projects_REC['project_period'];
			$project_start_date = "". $m_operational_projects_REC['project_start_date'];
			$project_end_date = "". $m_operational_projects_REC['project_end_date'];
			$department_id = (int) $m_operational_projects_REC['department_id'];
			$project_status_id = (int) $m_operational_projects_REC['project_status_id'];
			$plan_id = (int) $m_operational_projects_REC['plan_id'];
			$added_by = (int) $m_operational_projects_REC['added_by'];
			$added_date = $m_operational_projects_REC['added_date'];

			$department_name = "";
			$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $department_id";
			$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
			$employees_list_departments_DATA;
			if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
				$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
				$department_name = $employees_list_departments_DATA['department_name'];
			}

			$plan_title = "";
			$qu_m_strategic_plans_sel = "SELECT `plan_title` FROM  `m_strategic_plans` WHERE `plan_id` = $plan_id";
			$qu_m_strategic_plans_EXE = mysqli_query($KONN, $qu_m_strategic_plans_sel);
			if(mysqli_num_rows($qu_m_strategic_plans_EXE)){
				$m_strategic_plans_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_EXE);
				$plan_title = $m_strategic_plans_DATA['plan_title'];
			}
		


			$totThemes = 0;
			$totThemesTxt = '';
			$totObjectives = 0;
			$totObjectivesTxt = '';
			

			//$totalMappedPercentage = calculatePercentage($totalMapped, $totalMilestones);
			$totalMappedPercentage = 0;


			?>
			<div class="strategyBox">
				<div class="titler">
				<h1><?= $project_name; ?></h1>
<?php
if( $project_status_id == 1 ){
?>
					<a href="<?= $POINTER; ?>ext/strategies/view_project/?project_id=<?= $project_id; ?>"
						style="background: var(--strategy) !important;">Manage Project</a>
<?php
} else {
?>
					<a href="<?= $POINTER; ?>ext/strategies/view_project/?project_id=<?= $project_id; ?>"
						style="background: var(--strategy) !important;">Project Overview</a>
<?php
}
?>
				</div>
				<div class="leveler">
					<span class="namer">Status:</span>
<?php
if( $project_status_id == 1 ){
?>
					<span class="valer">Draft</span>
<?php
} else {
?>
					<span class="valer">Published</span>
<?php
}
?>

				</div>
				<div class="leveler">
					<span class="namer">Strategic Plan:</span>
					<span class="valer"><?= $plan_title; ?></span>
				</div>
				<div class="leveler">
					<span class="namer">Department:</span>
					<span class="valer"><?= $department_name; ?></span>
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
		<br><br><br><br><br>
		<span style="font-size:1.6em;"><?= lang("No_projects_have_been_created"); ?></span>
		<br><br>
		<div class=" tblBtnsGroup">
			<a href="<?= $POINTER; ?>ext/strategies/projects_new/"
				style="margin: 0 auto;background: var(--strategy) !important;font-size:1.2em;"
				class="tableBtn tableBtnInfo">Create New
				project</a>
		</div>
	</div>
	<?php
}

?>







<script>
	async function bindData(data) {

		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>ext/strategies/view/?ticket_id=' + data[i]["ticket_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="ticket-' + data[i]["ticket_id"] + '">' +
				'	<div class="td">' + data[i]["ticket_ref"] + '</div>' +
				'	<div class="td">' + data[i]["ticket_subject"] + '</div>' +
				'	<div class="td">' + data[i]["ticket_description"] + '</div>' +
				'	<div class="td">' + data[i]["ticket_added_date"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_to_name"] + '</div>' +
				'	<div class="td">' + data[i]["last_updated"] + '</div>' +
				'	<div class="td">' + data[i]["updated_by"] + '</div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["priority_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["ticket_priority"] + '</span></div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["status_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["ticket_status"] + '</span></div>' +
				'	<div class="td ">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("No_strategic_plans_have_been_created"); ?><br><div class="tblBtnsGroup">' +
				'<a onclick="addNewStrategy();" class="tableBtn tableBtnInfo">Create New strategic plan</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewStrategy() {
		showModal('newStrategyModal');
	}

</script>



<?php
//include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>










<script>
	function afterFormSubmission() {
		closeModal();
		goToFirstPage();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
