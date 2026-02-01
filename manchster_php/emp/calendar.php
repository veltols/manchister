<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Calendar");


$navId = 1;
$pageId = 9898;
$subPageId = 150;
include("app/assets.php");

$page_title = $EMPLOYEE_NAME;

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



$thsView = isset($_GET['cal_view']) ? test_inputs($_GET['cal_view']) : 'month';

$MonthName = date('l');

if ($thsView == 'day') {
	$MonthName = date('l');
} else if ($thsView == 'week') {
	$MonthName = date('F').', '.$currentYear;
} else if ($thsView == 'month') {
	$MonthName = date('F').', '.$currentYear;
}

?>



<!-- MAIN MID VIEW -->

<div class="mainCalendar">
	<div class="calendarSummary">
		<h3><?=$MonthName; ?></h3>
		<div class="calendarViewSelector">
			<a class="<?php if ($thsView == 'day') {
				echo 'viewSelected';
			} ?>" href="<?= $DIR_calendar; ?>?cal_view=day">
				<span><?= lang("Day", "AAR"); ?></span>
			</a>
			<a class="<?php if ($thsView == 'week') {
				echo 'viewSelected';
			} ?>" href="<?= $DIR_calendar; ?>?cal_view=week">
				<span><?= lang("Week", "AAR"); ?></span>
			</a>
			<a class="<?php if ($thsView == 'month') {
				echo 'viewSelected';
			} ?>" href="<?= $DIR_calendar; ?>?cal_view=month">
				<span><?= lang("Month", "AAR"); ?></span>
			</a>
		</div>
	</div>
	<div class="calendarView">

		<?php

		include("calendar_views/$thsView.php");
		?>
	</div>
</div>
<!-- MAIN MID VIEW -->


<?php
include("app/footer.php");
?>


<script>
	getTasks();
</script>


<?php
include("modals/task_new.php");
?>


<script>
	function afterFormSubmission() {
		window.location.reload();
	}
</script>
