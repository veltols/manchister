<a href="<?= $DIR_assets; ?>" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("List", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $DIR_assets; ?>?stt=1" class="pageNavLink <?php if ($subPageId == 250) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("About_to_expire", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $DIR_assets; ?>?stt=2" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Expired_Assets", "AAR"); ?></span>
	<div class="dec"></div>
</a>




<?php if ($subPageId == 1500) {

	?>
	<a href="#" class="pageNavLink activePageNavLink">
		<span><?= lang("Details", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
?>