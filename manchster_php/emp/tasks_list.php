<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Tasks_List");

$pageDataController = $POINTER . "get_tasks_list";

$addNewTaskController = $POINTER . 'add_tasks_list';



$pageId = 1263;
$subPageId = 200;

$tasksViewMode = isset( $_GET['view_mode'] ) ? "".test_inputs( $_GET['view_mode'] ) : 'my_tasks';
$extra_id = 0;
if( $tasksViewMode == 'others_tasks' ){
	$subPageId = 300;
	$extra_id = 150;
} else if( $tasksViewMode == 'kanban' ){
	$subPageId = 400;
	// include("tasks_kanban.php");
	// die();
}

include("app/assets.php");
?>


<?php
if( $tasksViewMode == 'kanban' ){
?>

<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('tasks_list_nav.php');
		?>
	</div>
	<div class="pageOptions">
		<a class="pageLink hasTooltip" onclick="showModal('newTaskModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("Add_new_task"); ?></div>
		</a>
	</div>
</div>

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

<?php
//--------------------------------------------------------------- END OF KANBAN
} else {
	
?>



<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('tasks_list_nav.php');
		?>
	</div>
	<div class="pageOptions">
		<a class="pageLink hasTooltip" onclick="showModal('newTaskModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("Add_new_task"); ?></div>
		</a>
		<a class="pageLink hasTooltip" onclick="">
			<i class="fa-solid fa-filter"></i>
			<div class="tooltip"><?= lang("Filter"); ?></div>
		</a>
		<a class="pageLink hasTooltip" onclick="">
			<i class="fa-solid fa-sort"></i>
			<div class="tooltip"><?= lang("Sort"); ?></div>
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
				<div class="th"></div>
				<div class="th"><?= lang("Task"); ?></div>
				<div class="th"><?= lang("Time"); ?></div>
				<div class="th"><?= lang("Assigned_Date"); ?></div>
				<div class="th"><?= lang("End_Date"); ?></div>
				<div class="th"><?= lang("last_updated"); ?></div>
<?php
if( $tasksViewMode == 'others_tasks' ){
?>
				<div class="th"><?= lang("Assigned_to"); ?></div>
<?php
} else {
	?>
					<div class="th"><?= lang("updated_by"); ?></div>
	<?php
}
?>
				
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

				

			var btns = '';
			btns = '<div class="extraMenu extraMenuHidden" id="em-' + i + '">';
			btns += '	<div class="mnuItemBtn" onclick="showSmallMnu(' + i + ');"><i class="fa-solid fa-ellipsis-vertical"></i></div>';
			btns += '	<div class="mnuItems">';
			//------------------------------------
				btns += '	<a href="<?= $POINTER; ?>tasks/view/?task_id=' + data[i]["task_id"] + '"><i class="fa-regular fa-eye"></i><?= lang("Details"); ?></a>';
			//------------------------------------
			btns += '	</div>';
			btns += '</div>';

			var tp = getInt(data[i]["time_progress"]);
			var tpBg = 'danger';
			if( tp <= 10 ){
				var tpBg = 'danger';
			} else if( tp > 10 && tp <= 60   ){
				var tpBg = 'warning';
			} else if( tp > 60   ){
				var tpBg = 'success';
			}

			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["task_id"] + '">' +
				'	<div class="td"> <span  title="' + data[i]["task_priority"] + '" class="badgeSquare" style="background:#' + data[i]["priority_color"] + ';"></span></div>' +
				'	<div class="td">' + data[i]["task_title"] + '</div>' +
				'	<div class="td">' +
				'		' + data[i]["counted_time"] + 
				'		<div class="remTimerBar"><div style="width:' + data[i]["time_progress"] + '%; background: var(--' + tpBg + ');"></div></div>' +
				'	</div>' +
				'	<div class="td">' + data[i]["task_assigned_date"] + '</div>' +
				'	<div class="td">' + data[i]["task_end_date"] + '</div>' +
				'	<div class="td">' + data[i]["last_updated"] + '</div>' +

				<?php
				if( $tasksViewMode == 'others_tasks' ){
				?>
				'	<div class="td">' + data[i]["assigned_to_name"] + '</div>' +
				<?php
				} else {
					?>
				'	<div class="td">' + data[i]["updated_by"] + '</div>' +
					<?php
				}
				?>


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
<?php
//end of if kanban
}
?>

