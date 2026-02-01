<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Atp_Details");
$pageController = $POINTER . 'update_properties_list';
$submitBtn = lang("Save_Property", "AAR");
$isNewForm = true;


$atp_id = 0;
$tab = 100;


if (!isset($_GET['atp_id'])) {
	header("location:" . $DIR_atps);
	die();
}

if (isset($_GET['tab'])) {
	$tab = (int) test_inputs($_GET['tab']);
}

$atp_id = (int) test_inputs($_GET['atp_id']);



if ($atp_id == 0) {
	header("location:" . $DIR_atps);
	die();
}

$pageId = 500;
include("app/assets.php");





$atp_ref = "";
$atp_name = "";
$atp_name_ar = "";
$contact_name = "";
$atp_email = "";
$atp_phone = "";
$emirate_id = 0;
$added_by = 0;
$added_date = "";
$status_id = 0;
$is_draft = 0;
$stage_no = 0;

$adder_name = '';
$atp_status = '';


$atp_emirate = 'AD';

$qu_atps_list_sel = "SELECT `atps_list`.*, 
									CONCAT(`employees_list`.`first_name$lang_db`, ' ', `employees_list`.`last_name$lang_db`) AS `adder_name`,
									`atps_list_status`.`status_name$lang_db` AS `atp_status`
							FROM  `atps_list` 
							INNER JOIN `employees_list` ON `atps_list`.`added_by` = `employees_list`.`employee_id` 
							INNER JOIN `atps_list_status` ON `atps_list`.`status_id` = `atps_list_status`.`status_id` 
							WHERE ( ( `atps_list`.`atp_id` = $atp_id ) ) LIMIT 1";




$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
$atps_list_DATA;
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$adder_name = "" . $atps_list_DATA['adder_name'];
	$atp_status = "" . $atps_list_DATA['atp_status'];
}

$atps_list_DATA['atp_emirate'] = 'AD';


?>
<script>
	var tab = <?= $tab; ?>;
</script>
<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->

	<div class="viewPageHeading">
		<div class="viewPageTitle">
			<span><?= $atps_list_DATA['atp_emirate']; ?></span>
			<?= $atps_list_DATA['atp_name']; ?>
		</div>
		<div class="viewPageData">


			<div class="viewData">
				<div class="property"><?= lang('REF', 'ARR'); ?></div>
				<div class="value"><?= $atps_list_DATA['atp_ref']; ?></div>
			</div>

			<div class="viewData">
				<div class="property"><?= lang('added_by', 'ARR'); ?></div>
				<div class="value"><?= $adder_name; ?> <?= lang('on', 'ARR'); ?> <?= $atps_list_DATA['added_date']; ?>
				</div>
			</div>

			<div class="viewData">
				<div class="property"><?= lang('status', 'ARR'); ?></div>
				<div class="value"><?= $atps_list_DATA['atp_status']; ?></div>
			</div>





		</div>
	</div>


	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="number"><?= $atp_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
		</div>




	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="getApps2"><?= lang('Visit_Planner', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('EQA_Visit', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="loadForms02"><?= lang('Interim', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">

			<div class="tabContent">
				<?php
				$thsTab = 101;
				include('atp_view/internal.php');
				?>
			</div>

			<div class="tabContent">
				<?php
				$thsTab = 102;
				include('atp_view/visit.php');
				?>
			</div>

			<div class="tabContent">
				<?php
				$thsTab = 103;
				include('atp_view/interim.php');
				?>
			</div>

			<div class="tabContent">
				<?php
				include('atp_view/logs.php');
				?>
			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>






<script>
	function doNothing() { }
	//getApps2();


</script>