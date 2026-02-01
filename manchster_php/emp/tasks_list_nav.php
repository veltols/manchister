<a href="<?= $DIR_tasks; ?>?view_mode=my_tasks" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("My_Tasks", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $DIR_tasks; ?>?view_mode=others_tasks" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Assigned_to_Others", "AAR"); ?></span>
	<div class="dec"></div>
</a>


<?php
/*
<a href="<?= $DIR_tasks; ?>?view_mode=kanban" class="pageNavLink <?php if ($subPageId == 400) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Kanban", "AAR"); ?></span>
	<div class="dec"></div>
</a>

*/

?>


<?php if ($subPageId == 1502) {

	?>
	<a class="pageNavLink activePageNavLink">
		<span><?= lang("Request_Details", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
?>