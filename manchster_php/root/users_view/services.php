<?php

$servController = $POINTER . 'save_user_services';



?>



<div class="tableContainer" id="">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Service_name"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
				<div class="th">&nbsp;</div>
			</div>
		</div>
		<div class="tableBody" id="listDataServ">
<?php
	$qu_employees_list_services_sel = "SELECT * FROM  `employees_list_services` ORDER BY `service_id` ASC";
	$qu_employees_list_services_EXE = mysqli_query($KONN, $qu_employees_list_services_sel);
	if(mysqli_num_rows($qu_employees_list_services_EXE)){
		while($employees_list_services_REC = mysqli_fetch_assoc($qu_employees_list_services_EXE)){
			$service_id = ( int ) $employees_list_services_REC['service_id'];
			$service_title = $employees_list_services_REC['service_title'];
			
			$sysVal = 0;
			$qu_employees_services_sel = "SELECT * FROM  `employees_services` WHERE ((`employee_id` = $employee_id) AND (`service_id` = $service_id))";
			$qu_employees_services_EXE = mysqli_query($KONN, $qu_employees_services_sel);
			if(mysqli_num_rows($qu_employees_services_EXE) == 1 ){
				$sysVal = 1;
			}
		?>
			<div class="tr levelRow" id="levelRow-<?=$service_id; ?>">
				<div class="td"><?=$service_title; ?></div>
				<div class="td">
					<select name="ss" id="serv-<?=$service_id; ?>">
						<option value="1" <?php if( $sysVal == 1 ){?> selected<?php } ?>>Enabled</option>
						<option value="0" <?php if( $sysVal == 0 ){?> selected<?php } ?>>Disabled</option>
					</select>
				</div>

				<div class="td">
					<a onclick="saveService(<?=$service_id; ?>);" class="dataActionBtn hasTooltip">	<i class="fa-regular fa-save"></i>	<div class="tooltip">Save Changes</div></a>
				</div>

			</div>
		<?php
		}
	}

?>

		</div>
	</div>
</div>

<script>
	
	function saveService(service_id) {
		var nwVal = getInt($('#serv-' + service_id).val());

		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $servController; ?>',
				dataType: "JSON",
				data: { 'service_id': service_id, 'new_val': nwVal, 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true ) {

						makeSiteAlert('suc', "Service changed");
					} else {
						makeSiteAlert('err', responser[0].message);
					}
				},
				error: function () {
					onCall = false;
					end_loader('article');
					makeSiteAlert('err', "General Error, please try later !");
				}
			});
		}
	}
</script>