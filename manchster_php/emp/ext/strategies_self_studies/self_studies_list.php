<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Strategy_Self_Studies");

$pageDataController = $POINTER . "ext/get_strategies_list";
$addNewStrategyController = $POINTER . "ext/add_strategies_list";
$updateStrategyController = $POINTER . "ext/update_strategies_list";

$pageId = 10004;
$subPageId = 200;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("self_studies_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		//include('strategies_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" href="<?= $POINTER; ?>ext/strategies/self_studies_new/"
			style="background: #FFF !important;color: var(--strategy) !important;border: 2px solid;width: 33%;">
			<i class="fa-solid fa-plus"></i>&nbsp;&nbsp;
			<div class="linkTxt"><?= lang("create_self_study"); ?></div>
		</a>
	</div>
</div>







<?php
$qu_m_strategic_studies_sel = "SELECT * FROM  `m_strategic_studies`  ORDER BY `study_id` DESC";
$qu_m_strategic_studies_EXE = mysqli_query($KONN, $qu_m_strategic_studies_sel);
if (mysqli_num_rows($qu_m_strategic_studies_EXE)) {
	?>
	<div class="strategyContainer">
		<?php
		while ($m_strategic_studies_REC = mysqli_fetch_assoc($qu_m_strategic_studies_EXE)) {
			$study_id = ( int ) $m_strategic_studies_REC['study_id'];
			$study_ref = $m_strategic_studies_REC['study_ref'];
			$study_title = $m_strategic_studies_REC['study_title'];
			$study_overview = $m_strategic_studies_REC['study_overview'];
			$study_status_id = ( int ) $m_strategic_studies_REC['study_status_id'];
			$added_by = ( int ) $m_strategic_studies_REC['added_by'];
			$added_date = $m_strategic_studies_REC['added_date'];
			

			?>
			<div class="strategyBox">
				<div class="titler">
				<h1><?= $study_title; ?></h1>

<?php
if( $study_status_id == 1 ){
?>
					<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>"
						style="background: var(--strategy) !important;">Edit study</a>
<?php
} else {
?>
					<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>"
						style="background: var(--strategy) !important;">study Overview</a>
<?php
}
?>
				</div>
				<div class="leveler">
					<span class="namer">Status:</span>
<?php
if( $study_status_id == 1 ){
?>
					<span class="valer">In progress</span>
<?php
} else {
?>
					<span class="valer">Published</span>
<?php
}
?>

				</div>
				<div class="leveler">
					<span class="namer">Ref:</span>
					<span class="valer"><?= $study_ref; ?></span>
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
		<span style="font-size:1.6em;"><?= lang("No_studies_have_been_created"); ?></span>
		<br><br>
		<div class=" tblBtnsGroup">
			<a href="<?= $POINTER; ?>ext/strategies/self_studies_new/"
				style="margin: 0 auto;background: var(--strategy) !important;font-size:1.2em;"
				class="tableBtn tableBtnInfo">Create New study</a>
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
