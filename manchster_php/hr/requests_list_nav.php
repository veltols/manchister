<a href="<?= $POINTER; ?>requests/list/" class="pageNavLink <?php if ($subPageId == 100) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("All", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>hr_leaves/list/" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Leaves", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>hr_permissions/list/" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Permissions", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $POINTER; ?>hr_da/list/" class="pageNavLink <?php if ($subPageId == 400) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Disciplinary_actions", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $POINTER; ?>hr_attendance/list/" class="pageNavLink <?php if ($subPageId == 500) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Attendance", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $POINTER; ?>hr_exit_interviews/list/" class="pageNavLink <?php if ($subPageId == 600) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Exit_interview", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $POINTER; ?>hr_performance/list/" class="pageNavLink <?php if ($subPageId == 700) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Performance", "AAR"); ?></span>
	<div class="dec"></div>
</a>