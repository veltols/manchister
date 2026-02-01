<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Tasks_List");

$pageDataController = $POINTER . "get_tasks_list";

$addNewTaskController = $POINTER . 'add_tasks_list';

$pageId = 100;
$subPageId = 200;
include("app/assets.php");
?>


<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('index_nav.php');
		?>
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
				<div class="th"><?= lang("Due_Date"); ?></div>
				<div class="th"><?= lang("Assigned_Date"); ?></div>
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
				'	<div class="td">' + data[i]["task_due_date"] + '</div>' +
				'	<div class="td">' + data[i]["task_assigned_date"] + '</div>' +
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



<!-- Modals START -->
<div class="modal" id="newTaskModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Task", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newTaskForm">


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("task_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="assigned_to" data-den="" data-req="1"
						data-type="hidden" value="<?= $USER_ID; ?>">
					<input type="text" class="inputer" data-name="task_title" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Priority", "AAR"); ?></label>
					<select class="inputer" data-name="priority_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `priority_id`, `priority_name` FROM  `sys_list_priorities` ORDER BY `priority_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$priority_id = (int) $SEL_REC['priority_id'];
								$priority_name = $SEL_REC['priority_name'];
								?>
								<option value="<?= $priority_id; ?>"><?= $priority_name; ?></option>

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
					<label><?= lang("task_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="task_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("due_date", "AAR"); ?></label>
					<input type="text" class="inputer has_future_date task_due_date" data-name="task_due_date"
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
						onclick="submitForm('newTaskForm', '<?= $addNewTaskController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
		goToFirstPage();
		window.location.reload();
	}
</script>
