<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Calendar");


$navId = 1;
$pageId = 300;
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


?>

<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('index_nav.php');
		?>
	</div>
</div>

<!-- MAIN MID VIEW -->

<div class="mainCalendar">
	<div class="calendarSummary">
		<h3>My Calendar</h3>
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
/*
		start_loader('article');
		$.ajax({
			url: '<?= $POINTER . "get_tasks_list"; ?>',
		dataType: "JSON",
			method: "POST",
				data: { "month": <?= $currentMonth; ?>, "year": <?= $currentYear; ?> },
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
*/
	}
	async function bindTasks(data) {

		for (i = 0; i < data.length; i++) {
			var thsDayId = data[i]["day_id"];

			//btns= '---';
			var dt = '<div style="background:#' + data[i]["priority_color"] + ';" class="dayEvent levelRow" id="levelRow-' + data[i]["task_id"] + '">' +
				'<span></span>' +
				'<span class="eventLabel">' + data[i]["task_title"] + '</span>' +
				'</div>';

			$('#' + thsDayId + ' .dayData').append(dt);
		}

	}

</script>
<!-- MAIN MID VIEW -->


<?php
include("app/footer.php");
?>

<script>
	function afterFormSubmission() {
		closeModal();
		window.location.reload();
	}
</script>

<script>
	getTasks();
</script>