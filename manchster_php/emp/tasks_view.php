<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Details");
$updateTaskController = $POINTER . 'update_tasks_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;


$task_id = 0;


if (!isset($_GET['task_id'])) {
	header("location:" . $DIR_atps);
	die();
}

$task_id = (int) test_inputs($_GET['task_id']);

if ($task_id == 0) {
	header("location:" . $DIR_atps);
	die();
}

$pageId = 1263;
$subPageId = 200;
include("app/assets.php");



$adder_name = '';
$task_status = '';
$task_progress = 0;




$qu_tasks_list_sel = "SELECT `tasks_list`.*, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `assigned_by`,
									`sys_list_status`.`status_name` AS `task_status`,
									`sys_list_status`.`status_color` AS `status_color`,

									`sys_list_priorities`.`priority_name` AS `task_priority`,
									`sys_list_priorities`.`priority_color` AS `priority_color` 
							FROM  `tasks_list` 
							INNER JOIN `employees_list` ON `tasks_list`.`assigned_by` = `employees_list`.`employee_id` 
							INNER JOIN `sys_list_status` ON `tasks_list`.`status_id` = `sys_list_status`.`status_id` 
							INNER JOIN `sys_list_priorities` ON `tasks_list`.`priority_id` = `sys_list_priorities`.`priority_id` 
							WHERE ( ( `tasks_list`.`task_id` = $task_id ) ) LIMIT 1";




$qu_tasks_list_EXE = mysqli_query($KONN, $qu_tasks_list_sel);
$tasks_list_DATA;
$status_id = 0;
if (mysqli_num_rows($qu_tasks_list_EXE)) {
	$tasks_list_DATA = mysqli_fetch_assoc($qu_tasks_list_EXE);
	$adder_name = "" . $tasks_list_DATA['assigned_by'];
	$task_status = "" . $tasks_list_DATA['task_status'];
	$status_id = (int) $tasks_list_DATA['status_id'];
	$task_progress = (int) $tasks_list_DATA['task_progress'];
}







?>

<div class="pageHeader">
	<div class="pageNav">

	</div>
</div>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $task_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Prioirty', 'ARR'); ?></div>
			<div class="number"><?= $tasks_list_DATA['task_priority']; ?></div>
			<div class="icon"><i style="color:#<?= $tasks_list_DATA['priority_color']; ?>"
					class="fa-solid fa-list-ol"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $tasks_list_DATA['task_status']; ?></div>
			<div class="icon"><i style="color:#<?= $tasks_list_DATA['status_color']; ?>" class="fa-solid fa-check"></i>
			</div>
		</div>




	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Task_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('tasks_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('tasks_view/logs.php');
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
<div class="modal" id="updateTaskModal">
	<div class="modalHeader">
		<h1><?= lang("Update_Task_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="updateTaskForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Status", "AAR"); ?></label>
					<input type="hidden" class="inputer edit_task_id" data-name="task_id" data-den="0" data-req="1"
						data-type="hidden" value="<?= $task_id; ?>">
					<select class="inputer edit_status_id" data-name="status_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `status_id`, `status_name` FROM  `sys_list_status` ORDER BY `status_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$thsIdd = (int) $SEL_REC['status_id'];
								$status_name = $SEL_REC['status_name'];
								?>
								<option value="<?= $thsIdd; ?>"><?= $status_name; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.edit_status_id').val('<?= $status_id; ?>');
			</script>

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remark", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
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
						onclick="submitForm('updateTaskForm', '<?= $updateTaskController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
<div class="modal" id="updateTaskProgressModal">
	<div class="modalHeader">
		<h1><?= lang("Update_Task_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="updateTaskProgressForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("New_Progress", "AAR"); ?> <span>( <i id="rangValuer-1">00</i> % )</span></label>
					<input type="hidden" class="inputer edit_task_id" data-name="task_id" data-den="0" data-req="1"
						data-type="hidden" value="<?= $task_id; ?>">
						<input type="range" min="0" step="1" value="0" max="100"
						class="inputer rangeRanger rangeRanger-1 edit_task_progress" data-range-id="1" data-name="task_progress"
						data-den="" data-req="1" data-type="text">


				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.edit_task_progress').val('<?= $task_progress; ?>');
			</script>

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remark", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
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
						onclick="submitForm('updateTaskProgressForm', '<?= $updateTaskController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
	$('.rangeRanger').on('change', function () {
		var thsId = getInt($(this).attr('data-range-id'));
		var thsValue = getInt($(this).val());
		$('#rangValuer-' + thsId).text(thsValue);
	});
</script>
<script>
	function afterFormSubmission() {
		closeModal();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}



	$('.edit_task_progress').change();
</script>
