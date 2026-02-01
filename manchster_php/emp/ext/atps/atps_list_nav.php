
<a href="<?= $POINTER; ?>ext/atps/list/?stt=00" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("All", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>ext/atps/list/?stt=1" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Pending", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>ext/atps/list/?stt=3" class="pageNavLink <?php if ($subPageId == 400) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Accredited", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>ext/atps/list/?stt=4" class="pageNavLink <?php if ($subPageId == 500) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Renewal", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>ext/atps/list/?stt=5" class="pageNavLink <?php if ($subPageId == 600) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Expired", "AAR"); ?></span>
	<div class="dec"></div>
</a>





<?php
if ($subPageId == 2000) {
	?>
	<a class="pageNavLink activePageNavLink">
		<span><?= lang("Add_New", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
?>

<?php
if ($subPageId == 2500) {
	?>
	<a class="pageNavLink activePageNavLink">
		<span><?= lang("Details", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
?>