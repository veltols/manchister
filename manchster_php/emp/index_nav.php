
<div class="welcomeDiv">
	<div class="texter">
		<h1>Hello, <?=$EMPLOYEE_NAME; ?></h1>
		<p>Ready to start your day with some new tasks?</p>
	</div>
	<img src="<?= $UPLOADS_DIRECTORY; ?>char.png" alt="" class="char">
</div>






<?php
/*



<div class="pageHeader">
	<div class="pageNav">
		<a href="<?= $DIR_dashboard; ?>?view=main&mode=<?= $mode; ?>" class="pageNavLink <?php if ($indexView == 'main') {
				echo 'activePageNavLink';
			} ?>">
			<span><?= lang("Dashboard", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
		<a href="<?= $DIR_dashboard; ?>?view=it&mode=<?= $mode; ?>" style="min-width: 10%;" class="pageNavLink <?php if ($indexView == 'it') {
				echo 'activePageNavLink';
			} ?>">
			<span><?= lang("IT", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
		<a href="<?= $DIR_dashboard; ?>?view=hr&mode=<?= $mode; ?>" style="min-width: 10%;" class="pageNavLink <?php if ($indexView == 'hr') {
				echo 'activePageNavLink';
			} ?>">
			<span><?= lang("HR", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
		<a href="<?= $DIR_dashboard; ?>?view=assets&mode=<?= $mode; ?>" style="min-width: 10%;" class="pageNavLink <?php if ($indexView == 'assets') {
				echo 'activePageNavLink';
			} ?>">
			<span><?= lang("Assets", "AAR"); ?></span>
			<div class="dec"></div>
		</a>

		<div style="min-width: 5%;width: 5%;" class="pageNavLink">&nbsp;</div>

		<?php
		if ($indexView != 'main' && $indexView != 'assets') {
			?>
			<a href="<?= $DIR_dashboard; ?>?mode=today&view=<?= $indexView; ?>" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'today') {
					echo 'activePageNavLink';
				} ?>">
				<span><?= lang("Today", "AAR"); ?></span>
				<div class="dec"></div>
			</a>

			<a href="<?= $DIR_dashboard; ?>?mode=this_week&view=<?= $indexView; ?>" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'this_week') {
					echo 'activePageNavLink';
				} ?>">
				<span><?= lang("this_week", "AAR"); ?></span>
				<div class="dec"></div>
			</a>
			<a href="<?= $DIR_dashboard; ?>?mode=this_month&view=<?= $indexView; ?>" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'this_month') {
					echo 'activePageNavLink';
				} ?>">
				<span><?= lang("this_month", "AAR"); ?></span>
				<div class="dec"></div>
			</a>
			<a id="customBtn" onclick="showdateForms(true);" style="curor:pointer;" class="pageNavLink pageNavOpt  <?php if ($mode == 'custom') {
				echo 'activePageNavLink';
			} ?>">
				<span><?= lang("custom", "AAR"); ?></span>
				<div class="dec"></div>
			</a>


			<?php
		}
		?>


	</div>

	<form id="pageOptions" class="pageOptions pageOptionsHidden">

		<input type="hidden" name="view" value="<?= $indexView; ?>" required />
		<input type="hidden" name="mode" value="custom" required />
		<input type="text" class="has_date inputAtPageOpts" name="start_date" id="start_date" value="<?= $startDate; ?>"
			placeholder="Date from" required />
		<input type="text" class="has_date inputAtPageOpts" name="end_date" id="end_date" value="<?= $endDate; ?>"
			placeholder="Date to" required />
		<button type="submit" class="pageLink">
			<div class="linkTxt"><?= lang("update"); ?></div>
		</button>

	</form>
	<!-- ------------------------------------- -->
</div>



*/
?>

<script>
	function showdateForms(clrData) {
		if (clrData) {
			$('#start_date').val('');
			$('#end_date').val('');
		}
		$('#pageOptions').removeClass('pageOptionsHidden');
		$('#customBtn').addClass('activePageNavLink');
		$('.notDash').removeClass('activePageNavLink');
	}
</script>