<div class="pageHeader">
	<div class="pageNav">
		<a href="<?= $DIR_dashboard; ?>" class="pageNavLink <?php if ($subPageId == 100) {
			  echo 'activePageNavLink';
		  } ?>">
			<span><i class="fa-solid fa-gauge"></i><?= lang("Dashboard", "AAR"); ?></span>
			<div class="dec"></div>
		</a>

		<div style="width: 15%;" class="pageNavLink"></div>

		<a href="<?= $DIR_dashboard; ?>?mode=today" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'today') {
			  echo 'activePageNavLink';
		  } ?>">
			<span><?= lang("Today", "AAR"); ?></span>
			<div class="dec"></div>
		</a>

		<a href="<?= $DIR_dashboard; ?>?mode=this_week" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'this_week') {
			  echo 'activePageNavLink';
		  } ?>">
			<span><?= lang("this_week", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
		<a href="<?= $DIR_dashboard; ?>?mode=this_month" class="pageNavLink pageNavOpt notDash <?php if ($mode == 'this_month') {
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
	</div>

	<form id="pageOptions" class="pageOptions pageOptionsHidden">

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