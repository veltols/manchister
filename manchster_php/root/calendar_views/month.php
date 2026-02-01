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
?>




<div class="calendarViewDays">
	<div class="cDay"><span class="dayLabel">SUN</span></div>
	<div class="cDay"><span class="dayLabel">MON</span></div>
	<div class="cDay"><span class="dayLabel">TUE</span></div>
	<div class="cDay"><span class="dayLabel">WED</span></div>
	<div class="cDay"><span class="dayLabel">THU</span></div>
	<div class="cDay"><span class="dayLabel">FRI</span></div>
	<div class="cDay"><span class="dayLabel">SAT</span></div>
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
			?>
			<div class="cDay extendedDay notThisMonth" id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayId; ?>">
				<span class="dayLabel"><?= $dayId; ?></span>
				<div class="dayData"></div>
			</div>
			<?php
		}
	}

	// --- Current month days ---
	for ($dayId = 1; $dayId <= $daysInMonth; $dayId++) {
		$thsClass = '';
		if ($dayId == date('d')) {
			$thsClass = 'today';
		}
		?>
		<div class="cDay extendedDay <?= $thsClass; ?>" id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayId; ?>">
			<span class="dayLabel"><?= $dayId; ?></span>
			<div class="dayData"></div>
		</div>
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
			?>
			<div class="cDay extendedDay notThisMonth" id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayId; ?>">
				<span class="dayLabel"><?= $dayId; ?></span>
				<div class="dayData"></div>
			</div>
			<?php
		}
	}
	?>
</div>