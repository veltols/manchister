<?php

$logsController = $POINTER . 'get_atps_apps';



?>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Task"); ?></div>
				<div class="th"><?= lang("Description"); ?></div>
				<div class="th"><?= lang("assigned_by"); ?></div>
				<div class="th"><?= lang("assigned_date"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $tasks_list_DATA['task_title']; ?></div>
				<div class="td"><?= $tasks_list_DATA['task_description']; ?></div>
				<div class="td"><?= $tasks_list_DATA['assigned_by']; ?></div>
				<div class="td"><?= $tasks_list_DATA['task_assigned_date']; ?></div>
			</div>

		</div>
	</div>
</div>



	<div class="tableContainer" id="appsListDtd">
		<div class="table">
			<div class="tableHeader">
				<div class="tr">
					<div class="th"><?= lang("Update_task"); ?></div>
				</div>
			</div>
			<div class="tableBody" id="appsListDt">

				<div class="tr levelRow" id="levelRow-sss">
					<div class="td">

					<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="showModal('updateTaskProgressModal');"
							style="width: 20%;">
							<span class="label"><?= lang("Update Progress"); ?></span>
						</div>

						<br><br>

					<?php
if ($status_id != 4) {
if ($status_id != 3) {
	?>
						<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="showModal('updateTaskModal');"
							style="width: 20%;">
							<span class="label"><?= lang("Update Status"); ?></span>
						</div>

						<?php
}
}
?>


					</div>

				</div>

			</div>
		</div>
	</div>

<script>

	function updateTaskStatus(nwStatus) {
		if (onCall == false) {
			onCall = true;
			var conf = confirm("Are you sure?");
			if (conf) {
				start_loader('article');
				$.ajax({
					type: 'POST',
					url: '<?= $POINTER; ?>update_tasks_list',
					dataType: "JSON",
					data: { 'task_id': <?= $task_id; ?>, 'status_id': nwStatus },
					success: function (responser) {
						onCall = false;
						end_loader('article');
						if (responser[0].success == true) {
							makeSiteAlert('suc', "Task Updated");
							setTimeout(function () {
								window.location.reload();
							}, 1000)
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
	}
</script>