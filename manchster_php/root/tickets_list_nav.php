<a href="<?= $DIR_tickets; ?>?stt=0" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-list"></i><?= lang("all", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $DIR_tickets; ?>?stt=1" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-circle"></i><?= lang("Open", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $DIR_tickets; ?>?stt=2" class="pageNavLink <?php if ($subPageId == 400) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-regular fa-hourglass-half"></i><?= lang("In_Progress", "AAR"); ?></span>
	<div class="dec"></div>
</a>
<a href="<?= $DIR_tickets; ?>?stt=3" class="pageNavLink <?php if ($subPageId == 500) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-check"></i><?= lang("Resolved", "AAR"); ?></span>
	<div class="dec"></div>
</a>




<?php if ($subPageId == 1500) {

	?>
	<a href="#" class="pageNavLink activePageNavLink">
		<span><?= lang("View_Ticket_Details", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
?>