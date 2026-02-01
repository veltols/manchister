<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Ticket_Details");
$pageController = $POINTER . 'update_QS';
$submitBtn = lang("Save_ticket", "AAR");
$isNewForm = true;


$ticket_id = 0;


if (!isset($_GET['ticket_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$ticket_id = (int) test_inputs($_GET['ticket_id']);

if ($ticket_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}

$pageId = 55006;
$subPageId = 200;
include("app/assets.php");



$adder_name = '';
$ticket_status = '';




$qu_QS_sel = "SELECT `support_tickets_list`.*, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `added_by`,
									`support_tickets_list_status`.`status_name` AS `ticket_status`,
									`support_tickets_list_status`.`status_color` AS `status_color`,

									`support_tickets_list_cats`.`category_name` AS `category_name`,

									`sys_list_priorities`.`priority_name` AS `ticket_priority`,
									`sys_list_priorities`.`priority_color` AS `priority_color` 
							FROM  `support_tickets_list` 
							INNER JOIN `employees_list` ON `support_tickets_list`.`added_by` = `employees_list`.`employee_id` 
							INNER JOIN `support_tickets_list_status` ON `support_tickets_list`.`status_id` = `support_tickets_list_status`.`status_id` 
							INNER JOIN `support_tickets_list_cats` ON `support_tickets_list`.`category_id` = `support_tickets_list_cats`.`category_id` 
							INNER JOIN `sys_list_priorities` ON `support_tickets_list`.`priority_id` = `sys_list_priorities`.`priority_id` 
							WHERE ( ( `support_tickets_list`.`ticket_id` = $ticket_id ) ) LIMIT 1";




$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
$QS_DATA;
$status_id = 0;
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_assoc($qu_QS_EXE);
	$adder_name = "" . $QS_DATA['added_by'];
	$ticket_status = "" . $QS_DATA['ticket_status'];
	$status_id = (int) $QS_DATA['status_id'];
}







?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Tickets_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		$subPageId = 1500;
		include('tickets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">

	</div>
</div>


<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $ticket_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('ticket_ref', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_ref']; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Prioirty', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_priority']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['priority_color']; ?>" class="fa-solid fa-list-ol"></i>
			</div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['ticket_status']; ?></div>
			<div class="icon"><i style="color:#<?= $QS_DATA['status_color']; ?>" class="fa-solid fa-file"></i>
			</div>
		</div>




	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Ticket_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('tickets_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('tickets_view/logs.php');
				?>
			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>