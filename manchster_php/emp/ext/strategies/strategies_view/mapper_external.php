<?php

$EMController = $POINTER . 'ext/get_external_maps';



?>


<script>
	function addNewExternalMap(){
		
		showModal('newExtMap');
	}
</script>
<?php
if( !isset($viewOnly) ){
?>
<a onclick="addNewExternalMap();" target="_blank" class="dataActionBtn actionBtn"
		style="width: 20%;background: var(--strategy) !important;margin:0;margin-inline-start: 5%;">
		<span class="label"><?= lang("Add_new_Mapping"); ?></span>
	</a>
<?php
}
?>
	
	
<div class="tableContainer" id="EMlistDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("External_Entity"); ?></div>
				<div class="th"><?= lang("Theme"); ?></div>
				<div class="th"><?= lang("Objective"); ?></div>
				<div class="th"><?= lang("Description"); ?></div>
				<div class="th"><?= lang("Date"); ?></div>
				<?php
if( !isset($viewOnly) ){
?>
											<div class="th">&nbsp;</div>
<?php
}
?>
			</div>
		</div>
		<div class="tableBody" id="EMlistData"></div>
	</div>
</div>



<script>

	function bindExData(data) {
		$('#EMlistData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["external_entity_name"] + '</div>' +
				'	<div class="td">' + data[i]["theme_title"] + '</div>' +
				'	<div class="td">' + data[i]["objective_title"] + '</div>' +
				'	<div class="td">' + data[i]["map_description"] + '</div>' +
				'	<div class="td"> From (' + data[i]["start_date"] + ') -> To ('+ data[i]["end_date"] + ')</div>' +
<?php
				if( !isset($viewOnly) ){
?>
				'	<div class="td">' + 
				'		<a onclick="deleteStrategyItem(' + "'external_map'" + ', ' + data[i]["record_id"] + ');" class="randomTableBtn randomDanger"><i class="fa-solid fa-trash"></i></a>'
				'	</div>' +
<?php
}
?>

				'</div>';

			$('#EMlistData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#EMlistData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getExternalMaps() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $EMController; ?>',
				dataType: "JSON",
				data: { 'plan_id': <?= $plan_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindExData(responser[0].data);
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