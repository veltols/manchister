<?php


$atp_logo = "no-img.png";
$atpName = "no-img.png";
$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE `atp_id` = $atp_id";
$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
if (mysqli_num_rows($qu_atps_list_EXE)) {
	$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
	$atpName = $atps_list_DATA['atp_name'];
	$atp_logo = $atps_list_DATA['atp_logo'];
}


?>



<div class="formPanel">

	<h1 class="formPanelTitle">
		<img src="<?= $POINTER; ?>../uploads/<?= $atp_logo; ?>"
			style="width: 2em;vertical-align: middle;margin-inline-end: 0.5em;">
		<?= $atpName; ?> -
		<?= lang('Initial Registration Form'); ?>
	</h1>
</div>