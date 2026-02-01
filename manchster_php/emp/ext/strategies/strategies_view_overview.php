<?php
$remPlanWeight = 0;
?>
<div class="pageHeader">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/list/" style="margin-inline-end: 0.3em;"><i
					class="fa-solid fa-arrow-left"></i></a>
			<?= $plan_title; ?>
		</h1>
	</div>
	<div class="pageNav">

	</div>

	<div class="pageOptions" style="height: 1em;"></div>
</div>


<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Plan_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="DrawInsights"><?= lang('Insights', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('Performance', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('themes/Pillars', 'ARR'); ?></div>
			
			<div class="tabTitle" extra-action="getExternalMaps"><?= lang('External_Mapping', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody" style="background: none;border: none;">
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/overview_published.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/performance_published.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/themes_published.php');
				?>
			</div>
			
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/mapper_external.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				$viewOnly = true;
				include('ext/strategies/strategies_view/logs.php');
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











<script>
	function afterFormSubmission() {
		closeModal();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>

