<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Strategies_List");

$pageDataController = $POINTER . "ext/get_strategies_list";
$addNewStrategyController = $POINTER . "ext/add_strategies_list";
$updateStrategyController = $POINTER . "ext/update_strategies_list";

$pageId = 10002;
$subPageId = 200;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Strategic_Plans", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		//include('strategies_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" href="<?= $POINTER; ?>ext/strategies/new/"
			style="background: #FFF !important;color: var(--strategy) !important;border: 2px solid;width: 33%;">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("create_strategic_plan"); ?></div>
		</a>
	</div>
</div>







<?php
$qu_m_strategic_plans_sel = "SELECT * FROM  `m_strategic_plans` ORDER BY `plan_id` DESC";
$qu_m_strategic_plans_EXE = mysqli_query($KONN, $qu_m_strategic_plans_sel);
if (mysqli_num_rows($qu_m_strategic_plans_EXE)) {
	?>
	<div class="strategyContainer">
		<?php
		while ($m_strategic_plans_REC = mysqli_fetch_assoc($qu_m_strategic_plans_EXE)) {
			$plan_id = (int) $m_strategic_plans_REC['plan_id'];
			$plan_title = "" . $m_strategic_plans_REC['plan_title'];
			$plan_period = "". $m_strategic_plans_REC['plan_period'];
			$plan_from = "". $m_strategic_plans_REC['plan_from'];
			$plan_to = "". $m_strategic_plans_REC['plan_to'];
			$plan_level = (int) $m_strategic_plans_REC['plan_level'];
			$plan_status_id = (int) $m_strategic_plans_REC['plan_status_id'];
			$added_by = (int) $m_strategic_plans_REC['added_by'];
			$added_date = $m_strategic_plans_REC['added_date'];

			$department_name = "";
			$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $plan_level";
			$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
			$employees_list_departments_DATA;
			if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
				$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
				$department_name = $employees_list_departments_DATA['department_name'];
			}


			$totThemes = 0;
			$totThemesTxt = '';
			$totObjectives = 0;
			$totObjectivesTxt = '';


			$qu_m_strategic_plans_themes_sel = "SELECT COUNT(`theme_id`) FROM  `m_strategic_plans_themes` WHERE `plan_id` = $plan_id";
			$qu_m_strategic_plans_themes_EXE = mysqli_query($KONN, $qu_m_strategic_plans_themes_sel);
			if (mysqli_num_rows($qu_m_strategic_plans_themes_EXE)) {
				$m_strategic_plans_themes_DATA = mysqli_fetch_array($qu_m_strategic_plans_themes_EXE);
				$totThemes = (int) $m_strategic_plans_themes_DATA[0];
				$totThemesTxt = $totThemes . ' Strategic Theme';
				if ($totThemes > 1) {
					$totThemesTxt = $totThemes . ' Strategic Themes';
				}
			}

			$qu_m_strategic_plans_objectives_sel = "SELECT COUNT(`objective_id`) FROM  `m_strategic_plans_objectives` WHERE `plan_id` = $plan_id";
			$qu_m_strategic_plans_objectives_EXE = mysqli_query($KONN, $qu_m_strategic_plans_objectives_sel);
			if (mysqli_num_rows($qu_m_strategic_plans_objectives_EXE)) {
				$m_strategic_plans_objectives_DATA = mysqli_fetch_array($qu_m_strategic_plans_objectives_EXE);
				$totObjectives = (int) $m_strategic_plans_objectives_DATA[0];
				$totObjectivesTxt = $totObjectives . ' Strategic Objective';
				if ($totObjectives > 1) {
					$totObjectivesTxt = $totObjectives . ' Strategic Objectives';
				}
			}



/*

				$totalMilestones = 0;
				$qu_m_strategic_plans_milestones_sel = "SELECT COUNT(`milestone_id`) FROM  `m_strategic_plans_milestones` WHERE `plan_id` = $plan_id";
				$qu_m_strategic_plans_milestones_EXE = mysqli_query($KONN, $qu_m_strategic_plans_milestones_sel);
				if(mysqli_num_rows($qu_m_strategic_plans_milestones_EXE)){
					$m_strategic_plans_milestones_DATA = mysqli_fetch_array($qu_m_strategic_plans_milestones_EXE);
					$totalMilestones = ( int ) $m_strategic_plans_milestones_DATA[0];
				}




				$totalMapped = 0;
				$qu_m_strategic_plans_internal_maps_sel = "SELECT COUNT(`local_map_id`) FROM  `m_strategic_plans_internal_maps` WHERE `plan_id` = $plan_id";
				$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
				if(mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE)){
					$m_strategic_plans_internal_maps_DATA = mysqli_fetch_array($qu_m_strategic_plans_internal_maps_EXE);
					$totalMapped = ( int ) $m_strategic_plans_internal_maps_DATA[0];
				}
		
*/




			//$totalMappedPercentage = calculatePercentage($totalMapped, $totalMilestones);
			$totalMappedPercentage = 0;


			?>
			<div class="strategyBox">
				<div class="titler">
				<h1><?= $plan_title; ?></h1>
<?php
if( $plan_status_id == 1 ){
?>
					<a href="<?= $POINTER; ?>ext/strategies/view/?plan_id=<?= $plan_id; ?>"
						style="background: var(--strategy) !important;">Manage Strategic Plan</a>
<?php
} else {
?>
					<a href="<?= $POINTER; ?>ext/strategies/view/?plan_id=<?= $plan_id; ?>"
						style="background: var(--strategy) !important;">Plan Overview</a>
<?php
}
?>
				</div>
				<div class="leveler">
					<span class="namer">Status:</span>
<?php
if( $plan_status_id == 1 ){
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
					<span class="namer">Organization:</span>
					<span class="valer"><?= $department_name; ?></span>
				</div>
				<div class="counters">
					<span class=""><?= $totThemesTxt; ?></span>
					<span class="">|</span>
					<span class=""><?= $totObjectivesTxt; ?></span>
				</div>
				<div class="mapper">
					<div class="mapperTitle">Strategic Objectives Mapped to Outcomes</div>
					<div class="mapperKeys">
						<div class="mapperKeyItem">
							<span class="greyBox boxer"></span>
							<span class="itemName">Not Mapped</span>
						</div>
						<div class="mapperKeyItem">
							<span class="primaryBox boxer"></span>
							<span class="itemName">Mapped</span>
						</div>
					</div>
					<div class="mapperBar">
						<div class="mapperBarMapped" style="width: <?= $totalMappedPercentage; ?>%;"></div>
					</div>
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
		<span style="font-size:1.6em;"><?= lang("No_strategic_plans_have_been_created"); ?></span>
		<br><br>
		<div class=" tblBtnsGroup">
			<a href="<?= $POINTER; ?>ext/strategies/new/"
				style="margin: 0 auto;background: var(--strategy) !important;font-size:1.2em;"
				class="tableBtn tableBtnInfo">Create New
				strategic plan</a>
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
