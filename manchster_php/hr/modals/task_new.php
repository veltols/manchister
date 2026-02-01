<!-- Modals START -->
<div class="modal" id="newTaskModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Task", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newTaskForm">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("assigned_to", "AAR"); ?></label>
					<select class="inputer new-assigned_to" data-name="assigned_to" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE  ( (`is_deleted` = 0) AND (`is_hidden` = 0)) ORDER BY `employee_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$emp_id = (int) $SEL_REC['employee_id'];
								$namer = $SEL_REC['namer'];
								?>
								<option value="<?= $emp_id; ?>"><?= $namer; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.new-assigned_to').val(<?= $USER_ID; ?>);
			</script>

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("task_title", "AAR"); ?></label>


					<input type="text" class="inputer" data-name="task_title" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Priority", "AAR"); ?></label>
					<select class="inputer" data-name="theme_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `theme_id`, `priority_name` FROM  `sys_list_priorities` ORDER BY `theme_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$theme_id = (int) $SEL_REC['theme_id'];
								$priority_name = $SEL_REC['priority_name'];
								?>
								<option value="<?= $theme_id; ?>"><?= $priority_name; ?></option>

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
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_date", "AAR"); ?></label>
					<input type="text" class="inputer has_date task_assigned_date" data-name="task_assigned_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("due_date", "AAR"); ?></label>
					<input type="text" class="inputer has_future_date task_due_date" data-name="task_due_date"
						data-den="" data-req="1" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("start_time", "AAR"); ?></label>

					<select class="inputer new-start_time" data-name="start_time" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected>--- Please Select---</option>
						<?php
						for ($i = 0; $i <= 24; $i++) {
							$Hour = $i . ':00';
							if ($i < 10) {
								$Hour = '0' . $i . ':00';
							}

							?>
							<option value="<?= $Hour; ?>:00"><?= $Hour; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("end_time", "AAR"); ?></label>

					<select class="inputer new-end_time" data-name="end_time" data-den="0" data-req="1"
						data-type="text">

						<option value="0" selected>--- Please Select---</option>
						<?php
						for ($i = 0; $i <= 24; $i++) {
							$Hour = $i . ':00';
							if ($i < 10) {
								$Hour = '0' . $i . ':00';
							}
							?>
							<option value="<?= $Hour; ?>:00"><?= $Hour; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>

				$('#newTaskModal .new-end_time').val('14:00:00');
			</script>

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newTaskForm', '<?= $POINTER . 'add_tasks_list'; ?>');"><?= lang("Save", "AAR"); ?></button>
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