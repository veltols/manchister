<?php

$lMController = $POINTER . 'ext/get_local_maps';



?>


<script>
	function addNewLocalMap(){
		$('.mapping-theme_id').val(0);
		$('.mapping-objective_id').val(0);
		$('.mapping-kpi_id').val(0);
		$('.mapping-milestone_id').val(0);


		$('.localMapping-objectives').addClass('itemHidden');
		$('.localMapping-kpis').addClass('itemHidden');
		$('.localMapping-milestones').addClass('itemHidden');
		
		//$('.mapping-theme_id').change();
		

		showModal('newMapDept');
	}
</script>
<?php
if( !isset($viewOnly) ){
?>
<a onclick="addNewLocalMap();" target="_blank" class="dataActionBtn actionBtn"
		style="width: 20%;background: var(--strategy) !important;margin:0;margin-inline-start: 5%;">
		<span class="label"><?= lang("Add_new_Map"); ?></span>
	</a>
<?php
}
?>
	
	
<div class="tableContainer" id="LMlistDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Department"); ?></div>
				<div class="th"><?= lang("Theme"); ?></div>
				<div class="th"><?= lang("Objective"); ?></div>
				<div class="th"><?= lang("KPI"); ?></div>
				<div class="th"><?= lang("Milestone-KPI"); ?></div>
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
		<div class="tableBody" id="LMlistData"></div>
	</div>
</div>



<script>

	function bindlmData(data) {
		$('#LMlistData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["department_name"] + '</div>' +
				'	<div class="td">' + data[i]["theme_title"] + '</div>' +
				'	<div class="td">' + data[i]["objective_title"] + '</div>' +
				'	<div class="td">' + data[i]["kpi_title"] + '</div>' +
				'	<div class="td">' + data[i]["milestone_title"] + '</div>' +
				'	<div class="td"> From (' + data[i]["start_date"] + ') -> To ('+ data[i]["end_date"] + ')</div>' +
<?php
				if( !isset($viewOnly) ){
?>
				'	<div class="td">' + 
				'		<a onclick="deleteStrategyItem(' + "'local_map'" + ', ' + data[i]["local_map_id"] + ');" class="randomTableBtn randomDanger"><i class="fa-solid fa-trash"></i></a>'
				'	</div>' +
<?php
}
?>

				'</div>';

			$('#LMlistData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#LMlistData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getlocalMaps() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $lMController; ?>',
				dataType: "JSON",
				data: { 'plan_id': <?= $plan_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindlmData(responser[0].data);
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