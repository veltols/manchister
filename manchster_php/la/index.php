<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Employee_Dashboard");


$navId = 1;
$pageId = 100;
include("app/assets.php");

$page_title = $EMPLOYEE_NAME;
?>
<!-- MAIN MID VIEW -->
<div class="articleContainer">
	<!-- MAIN MID VIEW -->


	<div class="pageMainTitle">
		<h5><?= lang('Hello', 'ARR'); ?>,</h5>
		<h1><?= $page_title; ?></h1>
		<div class="breadCrumbs">
			<a href="<?= $DIR_dashboard; ?>" class="hasLink"><?= lang('Dashboard', 'ARR'); ?></a>
			<span>\</span>
			<a><?= lang('Statistics', 'ARR'); ?></a>
		</div>
	</div>


	<div class="kpiContainer">
		<div class="kpiTitle">
			Profile Statistics
		</div>
		<div class="kpiBox bg-info">
			<div class="colA">
				<div class="dataValue">1<span class="dataUnit"></span></div>
				<div class="dataName">Requests</div>
			</div>
			<div class="colB">
				<div class="dataIcon">
					<i class="fa-solid fa-user"></i>
				</div>
			</div>
		</div>

		<div class="kpiBox bg-info">
			<div class="colA">
				<div class="dataValue">1<span class="dataUnit"></span></div>
				<div class="dataName">ATPs</div>
			</div>
			<div class="colB">
				<div class="dataIcon">
					<i class="fa-solid fa-city"></i>
				</div>
			</div>
		</div>

		<div class="kpiBox bg-info">
			<div class="colA">
				<div class="dataValue">0<span class="dataUnit"></span></div>
				<div class="dataName">pending</div>
			</div>
			<div class="colB">
				<div class="dataIcon">
					<i class="fa-solid fa-street-view"></i>
				</div>
			</div>
		</div>

	</div>

	<!-- MAIN MID VIEW -->
</div>
<!-- MAIN MID VIEW -->



<?php
include("app/footer.php");
?>