<a href="<?= $DIR_tickets; ?>?stt=0" class="pageNavLink <?php if ($subPageId == 200) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Tickets_List", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>requests/list/" class="pageNavLink <?php if ($subPageId == 300) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("HR_Requests", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<a href="<?= $POINTER; ?>ss/list/" class="pageNavLink <?php if ($subPageId == 550) {
	  echo 'activePageNavLink';
  } ?>">
	<span><?= lang("Support_Services", "AAR"); ?></span>
	<div class="dec"></div>
</a>

<?php

$qu_m_communications_list_types_sel = "SELECT COUNT(`communication_type_id`) FROM  `m_communications_list_types` WHERE (( `approval_id_1` = 0) OR ( `approval_id_2` = 0))";
$qu_m_communications_list_types_EXE = mysqli_query($KONN, $qu_m_communications_list_types_sel);
if (mysqli_num_rows($qu_m_communications_list_types_EXE)) {
	$m_communications_list_types_DATA = mysqli_fetch_array($qu_m_communications_list_types_EXE);
	$totWrong = (int) $m_communications_list_types_DATA[0];
	if ($totWrong == 0) {
		?>
		<a href="<?= $POINTER; ?>communications/list/" class="pageNavLink <?php if ($subPageId == 600) {
			  echo 'activePageNavLink';
		  } ?>">
			<span><?= lang("Communications", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
		<?php
	}
}

?>





<?php if ($subPageId == 1500) {

	?>
	<a class="pageNavLink activePageNavLink">
		<span><?= lang("View_Ticket_Details", "AAR"); ?></span>
		<div class="dec"></div>
	</a>

	<?php
}
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