<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Employee_Dashboard");


$navId = 1;
$pageId = 150;
include("app/assets.php");

$page_title = $EMPLOYEE_NAME;
?>
<!-- MAIN MID VIEW -->
<div class="articleContainer">
	<!-- MAIN MID VIEW -->
	<div class="mainCalendar">
		<div class="viewSelector">
			<div class="vselect">Day</div>
			<div class="vselect selected">Week</div>
			<div class="vselect">Month</div>
		</div>

		<div class="calendarTitle">
			<div class="monthYear">
				<span class="monthName">May</span>
				<span class="yearName">2025</span>
			</div>
		</div>


		<div class="calendarBody">

			<div class="daysRow">
				<div class="cell"></div>
				<div class="cell">Sun 16</div>
				<div class="cell">Mon 17</div>
				<div class="cell">Tue 18</div>
				<div class="cell">Wed 19</div>
				<div class="cell">Thu 20</div>
				<div class="cell cellToday">Fri 21</div>
			</div>


			<div class="timeRow">
				<div class="cell">08:00 AM</div>
				<div class="cell hasAppointment topper" title="click to view details">Etisalat Visit</div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
			</div>

			<div class="timeRow">
				<div class="cell">09:00 AM</div>
				<div class="cell hasAppointment botter"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
				<div class="cell emptyCell"></div>
			</div>
			<?php
			for ($m = 10; $m <= 16; $m++) {
				$thsT = 'AM';
				$thsC = $m;
				if ($m >= 12) {
					$thsT = 'PM';
					$thsC = $m - 12;
				}
				if ($m == 12) {
					$thsT = 'PM';
					$thsC = 12;
				}
				$thsClock = '' . $thsC . ':00';
				if ($thsC < 10) {
					$thsClock = '0' . $thsC . ':00';
				}

				?>
				<div class="timeRow">
					<div class="cell"><?= $thsClock; ?> 	<?= $thsT; ?></div>
					<div class="cell emptyCell"></div>
					<div class="cell emptyCell"></div>
					<div class="cell emptyCell"></div>
					<div class="cell emptyCell"></div>
					<div class="cell emptyCell"></div>
					<div class="cell emptyCell"></div>
				</div>
				<?php
			}
			?>

		</div>

	</div>


	<!-- MAIN MID VIEW -->
</div>
<!-- MAIN MID VIEW -->



<?php
include("app/footer.php");
?>