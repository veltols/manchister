<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Property_Details");
$pageController = $POINTER . 'update_properties_list';
$submitBtn = lang("Save_Property", "AAR");
$isNewForm = true;


$atp_id = 0;


if (!isset($_GET['atp_id'])) {
	header("location:" . $DIR_atps);
	die();
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




$qu_atps_list_sel = "SELECT `atps_list`.*, 
									CONCAT(`employees_list`.`first_name`, ' ', `employees_list`.`last_name`) AS `adder_name`,
									`atps_list_status`.`status_name` AS `atp_status`, 
									`sys_countries_cities`.`city_name` AS `atp_emirate`
							FROM  `atps_list` 
							INNER JOIN `employees_list` ON `atps_list`.`added_by` = `employees_list`.`employee_id` 
							INNER JOIN `atps_list_status` ON `atps_list`.`status_id` = `atps_list_status`.`status_id` 
							INNER JOIN `sys_countries_cities` ON `atps_list`.`emirate_id` = `sys_countries_cities`.`city_id` 
							WHERE ( ( `atps_list`.`atp_id` = $atp_id ) ) LIMIT 1";




$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
$atps_list_DATA;
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$adder_name = "" . $atps_list_DATA['adder_name'];
	$atp_status = "" . $atps_list_DATA['atp_status'];
}







?>

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
			<div class="tabTitle" extra-action="getApps"><?= lang('Applications', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getAppsRenew"><?= lang('Renewal_Requests', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getAppsRenew"><?= lang('Qualifications<br>Registration', 'ARR'); ?>
			</div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('Accreditation', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getCancelRequsts"><?= lang('Cancellation', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLearners"><?= lang('Learners_Enrollment', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('atp_view/apps.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('atp_view/renewal.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('atp_view/renewal.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('atp_view/accreditation.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('atp_view/cancelation.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('atp_view/learner_enrollment.php');
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
	getApps();
</script>