<?php


//calendar Settings

// Get today's date
$today = date("Y-m-d");
$currentMonth = date("m");
$currentYear = date("Y");

// Day of the week number (0=Sunday, 6=Saturday)
$dayOfWeek = date("w", strtotime($today));

// Find the start of the week (Sunday)
$weekStart = date("Y-m-d", strtotime("-$dayOfWeek days", strtotime($today)));

$we = strtotime("+6 days", strtotime($weekStart));
$weekEnd = date("Y-m-d", $we);
?>




<div class="calendarViewDays weekCalendar">

	<div class="hourHeader"></div>
	<?php
	// --- Print calendar header ---
	$dayId = 0;
	for ($dayId = 0; $dayId < 7; $dayId++) {
		$timestamp = strtotime("+$dayId days", strtotime($weekStart));
		$dayName = date("l", $timestamp);   // Full day name (e.g., Monday)
		$dayDate = date("Y-m-d", $timestamp); // Date in YYYY-MM-DD
		$dayDateView = date("d", $timestamp); // Date in DD
	
		// Mark today
		$thsClass = '';
		if ($dayDate == $today) {
			$thsClass = 'today';
		}

		?>
		<div class="cDay extendedDay dayInWeek <?= $thsClass; ?>"
			id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayId; ?>">
			<span class="dayLabel"><?= $dayDateView; ?></span>
			<span class="dayName"><?= $dayName; ?></span>
		</div>
		<?php
	}

	?>
</div>


<?php
for ($hour = 6; $hour <= 20; $hour++) {
	$hourView = '' . $hour . ':00';
	$hourViewOnly = '' . $hour . '';
	if ($hour < 10) {
		$hourView = '0' . $hour . ':00';
		$hourViewOnly = '0' . $hour . '';
	}
	?>
	<div class="calendarViewDays weekCalendar">
		<div class="hourHeader"><?= $hourView; ?></div>
		<?php
		for ($dayId = 0; $dayId < 7; $dayId++) {
			$timestamp = strtotime("+$dayId days", strtotime($weekStart));
			$dayName = date("l", $timestamp);   // Full day name (e.g., Monday)
			$dayDate = date("Y-m-d", $timestamp); // Date in YYYY-MM-DD
			$thsYear = date("Y", $timestamp); // Date in YYYY-MM-DD
			$thsMonth = date("m", $timestamp); // Date in YYYY-MM-DD
			$dayDateView = date("d", $timestamp); // Date in DD
	
			// Mark today
			$dayTxt = $dayId . '';
			if ($dayId < 10) {
				$dayTxt = '0' . $dayId . '';
			}
			?>

			<div class="cDay extendedDay dayInWeek" onclick="openTask('<?= $dayDate; ?>', '<?= $hourViewOnly; ?>');"
				id="<?= $thsYear; ?>-<?= $thsMonth; ?>-<?= $dayDateView; ?>-<?= $hourViewOnly; ?>">

				<div class="dayData"></div>
			</div>
			<?php
		}
		?>
	</div>

	<?php

}
?>






<script>

	function doCalendarStuff(stuff) {
		var itm = stuff[0];
		activeRequest = false;
		end_loader('article');
		if (itm['success'] == true) {
			bindTasks(itm['data']);
		}

	}

	//get tasks
	function getTasks() {

		start_loader('article');
		$.ajax({
			url: '<?= $POINTER . "get_tasks_list"; ?>',
			dataType: "JSON",
			method: "POST",
			data: { "date_from": '<?= $weekStart; ?>', "date_to": '<?= $weekEnd; ?>' },
			success: function (RES) {
				end_loader('article');
				doCalendarStuff(RES);
			},
			error: function () {
				activeRequest = false;
				end_loader('article');
				alert("Err-327657");
			}
		});

	}
	async function bindTasks(data) {

		for (i = 0; i < data.length; i++) {
			var daySplit = data[i]["task_assigned_date"].split(' ');
			var thsHourSplit = daySplit[1].split(':');
			var thsHour = thsHourSplit[0];
			var thsYMDH = daySplit[0];
			var thsDayId = thsYMDH + '-' + thsHour + "";
			console.log('ddd====' + thsDayId);
			//btns= '---';
			var dt = '<div style="background:#' + data[i]["priority_color"] + ';" class="dayEvent levelRow" id="levelRow-' + data[i]["task_id"] + '">' +
				'<span></span>' +
				'<span class="eventLabel">' + data[i]["task_title"] + '</span>' +
				'</div>';

			$('#' + thsDayId + ' .dayData').append(dt);
		}

	}

</script>

<script>
	function openTask(dayId, timeId) {
		//newTaskModal
		$('#newTaskModal .task_assigned_date').val(dayId);
		$('#newTaskModal .new-start_time').val(timeId + ':00:00');
		showModal('newTaskModal');
	}
</script>