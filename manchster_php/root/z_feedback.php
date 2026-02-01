<?php

$pageId = 950;
include("app/assets.php");
?>

<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('users_list_nav.php');
		?>
	</div>
	<div class="pageOptions">

		<a class="pageLink" onclick="tableToExcel('excelTbl', 'Portal Feedback', 'sys_feedback');">
			<i class="fa-solid fa-file"></i>
			<div class="linkTxt"><?= lang("Export"); ?></div>
		</a>
	</div>
</div>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("ID"); ?></div>
				<div class="th"><?= lang("Employee"); ?></div>
				<div class="th"><?= lang("Date"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData">
			<?php
			$qu_feedback_forms_answers_sel = "SELECT * FROM  `feedback_forms_answers`";
			$qu_feedback_forms_answers_EXE = mysqli_query($KONN, $qu_feedback_forms_answers_sel);
			if (mysqli_num_rows($qu_feedback_forms_answers_EXE)) {
				while ($feedback_forms_answers_REC = mysqli_fetch_assoc($qu_feedback_forms_answers_EXE)) {
					$record_id = (int) $feedback_forms_answers_REC['record_id'];
					$form_id = (int) $feedback_forms_answers_REC['form_id'];
					$a1 = $feedback_forms_answers_REC['a1'];
					$a2 = $feedback_forms_answers_REC['a2'];
					$a3 = $feedback_forms_answers_REC['a3'];
					$a4 = $feedback_forms_answers_REC['a4'];
					$a5 = $feedback_forms_answers_REC['a5'];
					$a6 = $feedback_forms_answers_REC['a6'];
					$a7 = $feedback_forms_answers_REC['a7'];
					$a8 = $feedback_forms_answers_REC['a8'];
					$a9 = $feedback_forms_answers_REC['a9'];
					$a10 = $feedback_forms_answers_REC['a10'];
					$a11 = $feedback_forms_answers_REC['a11'];
					$a12 = $feedback_forms_answers_REC['a12'];
					$a13 = $feedback_forms_answers_REC['a13'];
					$a14 = $feedback_forms_answers_REC['a14'];
					$a15 = $feedback_forms_answers_REC['a15'];
					$a16 = $feedback_forms_answers_REC['a16'];
					$a17 = $feedback_forms_answers_REC['a17'];

					$employee_id = 0;
					$added_date = "";
					$employeeName = "";


					$qu_feedback_forms_sel = "SELECT `employee_id`, `added_date` FROM  `feedback_forms` WHERE `form_id` = $form_id";
					$qu_feedback_forms_EXE = mysqli_query($KONN, $qu_feedback_forms_sel);
					if (mysqli_num_rows($qu_feedback_forms_EXE)) {
						$feedback_forms_DATA = mysqli_fetch_assoc($qu_feedback_forms_EXE);
						$employee_id = (int) $feedback_forms_DATA['employee_id'];
						$added_date = '' . $feedback_forms_DATA['added_date'];
					}


					$qu_employees_list_sel = "SELECT `first_name`, `second_name` FROM  `employees_list` WHERE `employee_id` = $employee_id";
					$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
					if (mysqli_num_rows($qu_employees_list_EXE)) {
						$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
						$employeeName = $employees_list_DATA['first_name'] . ' ' . $employees_list_DATA['second_name'];
					}



					?>
					<div class="tr levelRow">
						<div class="td"><?= $form_id; ?></div>
						<div class="td"><?= $employeeName; ?></div>
						<div class="td"><?= $added_date; ?></div>
						<?php

						for ($i = 1; $i <= 17; $i++) {
							$thsAnswer = $feedback_forms_answers_REC['a' . $i];
							?>
							<!--div class="td"><?= $thsAnswer; ?></div-->
							<?php
						}

						?>
					</div>


					<?php
				}
			}

			?>
		</div>
	</div>
</div>




<script>
	async function bindData(data) {
/*
		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>users/view/?employee_id=' + data[i]["employee_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["employee_id"] + '">' +
				'	<div class="td">' + data[i]["employee_no"] + '</div>' +
				'	<div class="td">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td">' + data[i]["employee_email"] + '</div>' +
				'	<div class="td">' + data[i]["department_name"] + '</div>' +
				'	<div class="td">' + data[i]["designation_name"] + '</div>' +
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
*/
	}
</script>



<div style="display:none;">

	<table class="table" id="excelTbl">
		<thead class="tableHeader">
			<tr class="tr">
				<th class="th"><?= lang("Employee"); ?></th>
				<th class="th"><?= lang("Date"); ?></th>

				<th class="th"><?= lang("User-friendliness", "AAR"); ?></th>
				<th class="th"><?= lang("Visual appeal", "AAR"); ?></th>
				<th class="th"><?= lang("Login and logout credentials", "AAR"); ?></th>
				<th class="th"><?= lang("Did you encouter any technical issues while using the portal?", "AAR"); ?>
				</th>
				<th class="th"><?= lang("If yes, please explain", "AAR"); ?></th>
				<th class="th"><?= lang("Calendar Rating", "AAR"); ?></th>
				<th class="th"><?= lang("Calendar Comment", "AAR"); ?></th>
				<th class="th"><?= lang("Chat Rating", "AAR"); ?></th>
				<th class="th"><?= lang("Chat Comment", "AAR"); ?></th>
				<th class="th"><?= lang("Tasks Rating", "AAR"); ?></th>
				<th class="th"><?= lang("Tasks Comment", "AAR"); ?></th>
				<th class="th"><?= lang("IT Tickets Rating", "AAR"); ?></th>
				<th class="th"><?= lang("IT Tickets Comment", "AAR"); ?></th>
				<th class="th"><?= lang("HR Requests Rating", "AAR"); ?></th>
				<th class="th"><?= lang("HR Requests Comment", "AAR"); ?></th>
				<th class="th"><?= lang("What new feature(s) would you like to be added to the portal", "AAR"); ?>
				</th>
				<th class="th"><?= lang("Notes", "AAR"); ?></th>


			</tr>
		</thead>
		<tbody class="tableBody" id="listData">
			<?php
			$qu_feedback_forms_answers_sel = "SELECT * FROM  `feedback_forms_answers`";
			$qu_feedback_forms_answers_EXE = mysqli_query($KONN, $qu_feedback_forms_answers_sel);
			if (mysqli_num_rows($qu_feedback_forms_answers_EXE)) {
				while ($feedback_forms_answers_REC = mysqli_fetch_assoc($qu_feedback_forms_answers_EXE)) {
					$record_id = (int) $feedback_forms_answers_REC['record_id'];
					$form_id = (int) $feedback_forms_answers_REC['form_id'];
					$a1 = $feedback_forms_answers_REC['a1'];
					$a2 = $feedback_forms_answers_REC['a2'];
					$a3 = $feedback_forms_answers_REC['a3'];
					$a4 = $feedback_forms_answers_REC['a4'];
					$a5 = $feedback_forms_answers_REC['a5'];
					$a6 = $feedback_forms_answers_REC['a6'];
					$a7 = $feedback_forms_answers_REC['a7'];
					$a8 = $feedback_forms_answers_REC['a8'];
					$a9 = $feedback_forms_answers_REC['a9'];
					$a10 = $feedback_forms_answers_REC['a10'];
					$a11 = $feedback_forms_answers_REC['a11'];
					$a12 = $feedback_forms_answers_REC['a12'];
					$a13 = $feedback_forms_answers_REC['a13'];
					$a14 = $feedback_forms_answers_REC['a14'];
					$a15 = $feedback_forms_answers_REC['a15'];
					$a16 = $feedback_forms_answers_REC['a16'];
					$a17 = $feedback_forms_answers_REC['a17'];

					$employee_id = 0;
					$added_date = "";
					$employeeName = "";


					$qu_feedback_forms_sel = "SELECT `employee_id`, `added_date` FROM  `feedback_forms` WHERE `form_id` = $form_id";
					$qu_feedback_forms_EXE = mysqli_query($KONN, $qu_feedback_forms_sel);
					if (mysqli_num_rows($qu_feedback_forms_EXE)) {
						$feedback_forms_DATA = mysqli_fetch_assoc($qu_feedback_forms_EXE);
						$employee_id = (int) $feedback_forms_DATA['employee_id'];
						$added_date = '' . $feedback_forms_DATA['added_date'];
					}


					$qu_employees_list_sel = "SELECT `first_name`, `second_name` FROM  `employees_list` WHERE `employee_id` = $employee_id";
					$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
					if (mysqli_num_rows($qu_employees_list_EXE)) {
						$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
						$employeeName = $employees_list_DATA['first_name'] . ' ' . $employees_list_DATA['second_name'];
					}
					?>
					<tr class="tr levelRow">
						<td class="td"><?= $employeeName; ?></td>
						<td class="td"><?= $added_date; ?></td>
						<?php
						for ($i = 1; $i <= 17; $i++) {
							$thsAnswer = $feedback_forms_answers_REC['a' . $i];
							?>
							<td class="td"><?= $thsAnswer; ?></td>
							<?php
						}

						?>
					</tr>


					<?php
				}
			}

			?>
		</tbody>
	</table>
</div>

<?php
include("app/footer.php");
?>

