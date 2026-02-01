<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Tasks_List");

$pageDataController = $POINTER . "get_tasks_list";

$addNewTaskController = $POINTER . 'add_tasks_list';

$pageId = 1000;
$subPageId = 200;
include("app/assets.php");
?>


<div class="pageHeader">
	<div class="pageNav">

	</div>
	<div class="pageOptions">
		<a class="pageLink" onclick="showModal('newTaskModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("new_task"); ?></div>
		</a>
	</div>
</div>


<!--div class="userWelcome">
	<div class="userMessages">
		<h1><?= $page_title; ?></h1>
	</div>

</div-->



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Task"); ?></div>
				<div class="th"><?= lang("Time"); ?></div>
				<div class="th"><?= lang("Due_Date"); ?></div>
				<div class="th"><?= lang("Assigned_Date"); ?></div>
				<div class="th"><?= lang("End_Date"); ?></div>
				<div class="th"><?= lang("Assigned_By"); ?></div>
				<div class="th"><?= lang("Priority"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData"></div>
	</div>
</div>




<script>
	async function bindData(data) {

		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>tasks/view/?task_id=' + data[i]["task_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["task_id"] + '">' +
				'	<div class="td">' + data[i]["task_title"] + '</div>' +
				'	<div class="td">' + data[i]["counted_time"] + '</div>' +
				'	<div class="td">' + data[i]["task_due_date"] + '</div>' +
				'	<div class="td">' + data[i]["task_assigned_date"] + '</div>' +
				'	<div class="td">' + data[i]["task_end_date"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_by"] + '</div>' +
				'	<div class="td"> <span class="badge" style="background:#' + data[i]["priority_color"] + ';">' + data[i]["task_priority"] + '</span></div>' +
				'	<div class="td"> <span class="badge" style="background:#' + data[i]["status_color"] + ';">' + data[i]["task_status"] + '</span></div>' +
				'	<div class="td">' +
				btns +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
		}

	}
</script>




<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>

<?php
include("modals/task_new.php");
?>




<script>
	function afterFormSubmission() {
		window.location.reload();
	}
</script>
