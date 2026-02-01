<?php


//calendar Settings

// Input: Month and Year (example: September 2025)
$currentMonth = date('m');  // Change to your month
$currentYear = date('Y');

// First day of the given month
$firstDayOfMonth = mktime(hour: 0, minute: 0, second: 0, month: $currentMonth, day: 1, year: $currentYear);

// Number of days in the given month
$daysInMonth = date("t", $firstDayOfMonth);

// Day of the week for the 1st (0=Sunday, 6=Saturday)
$startDay = date("w", $firstDayOfMonth);

// Last day of the given month
$lastDayOfMonth = mktime(0, 0, 0, $currentMonth, $daysInMonth, $currentYear);
$endDay = date("w", $lastDayOfMonth);


$startDaySearch = date("Y-m-d", $firstDayOfMonth);
$endDaySearch = date("Y-m-d", $lastDayOfMonth);

?>




<div class="calendarViewDays">
	<div class="cDay calTh"><span class="dayLabel">SUN</span></div>
	<div class="cDay calTh"><span class="dayLabel">MON</span></div>
	<div class="cDay calTh"><span class="dayLabel">TUE</span></div>
	<div class="cDay calTh"><span class="dayLabel">WED</span></div>
	<div class="cDay calTh"><span class="dayLabel">THU</span></div>
	<div class="cDay calTh"><span class="dayLabel">FRI</span></div>
	<div class="cDay calTh"><span class="dayLabel">SAT</span></div>
	<?php

	// --- Print calendar header ---
	$dayId = 0;

	// --- Previous month's leftover days ---
	if ($startDay > 0) {
		// Get number of days in previous month
		$prevMonth = $currentMonth - 1;
		$prevYear = $currentYear;
		if ($prevMonth == 0) {
			$prevMonth = 12;
			$prevYear--;
		}
		$daysInPrevMonth = date("t", mktime(0, 0, 0, $prevMonth, 1, $prevYear));

		// Print the last needed days of previous month
		for ($dayId = $daysInPrevMonth - $startDay + 1; $dayId <= $daysInPrevMonth; $dayId++) {
			$dayTxt = $dayId . '';
			if ($dayId < 10) {
				$dayTxt = '0' . $dayId . '';
			}
			?>
			<a href="<?= $DIR_calendar; ?>?cal_view=day&today=<?= $prevYear; ?>-<?= $prevMonth; ?>-<?= $dayTxt; ?>"
				class="cDay extendedDay notThisMonth" id="<?= $prevYear; ?>-<?= $prevMonth; ?>-<?= $dayTxt; ?>">
				<span class="dayLabel"><?= $dayTxt; ?></span>
				<div class="dayData"></div>
			</a>
			<?php
		}
	}

	// --- Current month days ---
	for ($dayId = 1; $dayId <= $daysInMonth; $dayId++) {
		$thsClass = '';
		if ($dayId == date('d')) {
			$thsClass = 'today';
		}
		$dayTxt = $dayId . '';
		if ($dayId < 10) {
			$dayTxt = '0' . $dayId . '';
		}
		?>
		<a href="<?= $DIR_calendar; ?>?cal_view=day&today=<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayTxt; ?>"
			class="cDay extendedDay <?= $thsClass; ?>" id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayTxt; ?>">
			<span class="dayLabel"><?= $dayTxt; ?></span>
			<div class="dayData"></div>
		</a>
		<?php
	}

	// --- Next month's leftover days ---
	if ($endDay < 6) {
		$nextMonth = $currentMonth + 1;
		$nextYear = $currentYear;
		if ($nextMonth == 13) {
			$nextMonth = 1;
			$nextYear++;
		}
		$extraDays = 6 - $endDay;
		for ($dayId = 1; $dayId <= $extraDays; $dayId++) {
			$dayTxt = $dayId . '';
			if ($dayId < 10) {
				$dayTxt = '0' . $dayId . '';
			}
			?>
			<a href="<?= $DIR_calendar; ?>?cal_view=day&today=<?= $nextYear; ?>-<?= $nextMonth; ?>-<?= $dayTxt; ?>"
				class="cDay extendedDay notThisMonth" id="<?= $nextYear; ?>-<?= $nextMonth; ?>-<?= $dayTxt; ?>">
				<span class="dayLabel"><?= $dayTxt; ?></span>
				<div class="dayData"></div>
			</a>
			<?php
		}
	}
	?>
</div>






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
			data: { "date_from": '<?= $startDaySearch; ?>', "date_to": '<?= $endDaySearch; ?>' },
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
			var thsDayId = data[i]["day_id"];
			console.log('ddd====' + thsDayId);
			//btns= '---';
			var dt = '<div style="background:#' + data[i]["status_color"] + ';" class="dayEvent levelRow" id="levelRow-' + data[i]["task_id"] + '">' +
				'<span></span>' +
				'<span class="eventLabel">' + data[i]["task_title"] + '</span>' +
				'</div>';

			$('#' + thsDayId + ' .dayData').append(dt);
		}

	}

</script>