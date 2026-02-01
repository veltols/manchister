<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Dashboard");




$pageId = 100;
$subPageId = 100;
include("app/assets.php");


$indexView = isset($_GET['view']) ? test_inputs($_GET['view']) : 'main';

$mode = isset($_GET['mode']) ? test_inputs($_GET['mode']) : 'today';
$startDate = isset($_GET['start_date']) ? test_inputs($_GET['start_date']) : date('Y-m-1');
$endDate = isset($_GET['end_date']) ? test_inputs($_GET['end_date']) : date('Y-m-30');

if ($mode == 'today') {
	$startDate = date('Y-m-d 00:00:00');
	$endDate = date('Y-m-d 23:59:59');
} else if ($mode == 'this_week') {

	$monday = strtotime('next Monday -1 week');
	$monday = date('w', $monday) == date('w') ? strtotime(date("Y-m-d", $monday) . " +7 days") : $monday;
	$sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
	$startDate = date("Y-m-d 00:00:00", $monday);
	$endDate = date("Y-m-d 23:59:59", $sunday);
} else if ($mode == 'this_month') {
	$totalDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	$startDate = date('Y-m-1 00:00:00');
	$endDate = date("Y-m-$totalDays  23:59:59");
} else if ($mode == 'custom') {

	$startDate = isset($_GET['start_date']) ? test_inputs($_GET['start_date']) : date('Y-m-d');
	$endDate = isset($_GET['end_date']) ? test_inputs($_GET['end_date']) : date('Y-m-d');

}









if ($indexView == 'main') {
	include("index_main.php");
} else if ($indexView == 'it') {
	include("index_it.php");
} else if ($indexView == 'hr') {
	include("index_hr.php");
} else if ($indexView == 'assets') {
	include("index_assets.php");
}




?>




<script>
	function afterFormSubmission() {
		window.location.reload();
	}
</script>


<script>
	<?php
	if ($mode == 'custom') {
		?>
		showdateForms(false);
		<?php

	}
	?>
</script>



<?php
$is_pass = 0;
$qu_employees_list_sel = "SELECT `is_pass` FROM  `employees_list` WHERE `employee_id` = $USER_ID";
$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
if (mysqli_num_rows($qu_employees_list_EXE)) {
	$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
	$is_pass = (int) $employees_list_DATA['is_pass'];
}
if ($is_pass == 0) {
	?>
	<script>
		showModal('resetPassModal');
	</script>
	<?php
}
?>