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
for ($hour = 8; $hour <= 15; $hour++) {


	$hourView = '' . $hour . ':00';
	if ($hour < 10) {
		$hourView = '0' . $hour . ':00';
	}
	?>
	<div class="calendarViewDays weekCalendar">
		<div class="hourHeader"><?= $hourView; ?></div>
		<?php
		for ($dayId = 0; $dayId < 7; $dayId++) {
			$timestamp = strtotime("+$dayId days", strtotime($weekStart));
			$dayName = date("l", $timestamp);   // Full day name (e.g., Monday)
			$dayDate = date("Y-m-d", $timestamp); // Date in YYYY-MM-DD
			$dayDateView = date("d", $timestamp); // Date in DD
	
			// Mark today
	

			?>

			<div class="cDay extendedDay dayInWeek"
				id="<?= $currentYear; ?>-<?= $currentMonth; ?>-<?= $dayId; ?>-<?= $hour; ?>">

			</div>
			<?php
		}
		?>
	</div>

	<?php

}
?>