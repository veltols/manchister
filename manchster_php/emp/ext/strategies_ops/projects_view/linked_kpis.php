<?php

$lMController = $POINTER . 'ext/get_linked_kpis';



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
		

		showModal('newKPIlink');
	}
</script>
<?php
if( !isset($viewOnly) ){
?>
<a onclick="addNewLocalMap();" class="dataActionBtn actionBtn"
		style="width: 20%;background: var(--strategy) !important;margin:0;margin-inline-start: 5%;">
		<span class="label"><?= lang("link_KPI"); ?></span>
	</a>
<?php
}
?>
	
	
<div class="tableContainer" id="KPListData22d">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Plan"); ?></div>
				<div class="th"><?= lang("Theme"); ?></div>
				<div class="th"><?= lang("Objective"); ?></div>
				<div class="th"><?= lang("KPI"); ?></div>
<?php
if( !isset($viewOnly) ){
?>
											<div class="th">&nbsp;</div>
<?php
}
?>
			</div>
		</div>
		<div class="tableBody" id="KPListData22"></div>
	</div>
</div>



<script>

	function bindlmDataD(data) {
		$('#KPListData22').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["plan_title"] + '</div>' +
				'	<div class="td">' + data[i]["theme_title"] + '</div>' +
				'	<div class="td">' + data[i]["objective_title"] + '</div>' +
				'	<div class="td">' + data[i]["kpi_title"] + '</div>' + 
<?php
				if( !isset($viewOnly) ){
?>
				'	<div class="td">' + 
				'		<a onclick="deleteProjectItem(' + "'linked_kpi'" + ', ' + data[i]["linked_kpi_id"] + ');" class="randomTableBtn randomDanger"><i class="fa-solid fa-trash"></i></a>'
				'	</div>' +
<?php
}
?>

				'</div>';

			$('#KPListData22').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#KPListData22').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getLinkedKPIs() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $lMController; ?>',
				dataType: "JSON",
				data: { 'project_id': <?= $project_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindlmDataD(responser[0].data);
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