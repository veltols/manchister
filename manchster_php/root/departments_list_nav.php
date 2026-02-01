<a href="<?= $POINTER; ?>departments/list/" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-list"></i><?= lang("departments", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>designations/list/" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-folder-open"></i><?= lang("Designations", "AAR"); ?></span>
	<div class="dec"></div>
</a>


<a href="<?= $POINTER; ?>chart/list/" class="pageNavLink <?php if ($subPageId == 400) {
	  echo 'activePageNavLink';
  } ?>">
	<span><i class="fa-solid fa-network-wired"></i><?= lang("Chart", "AAR"); ?></span>
	<div class="dec"></div>
</a>