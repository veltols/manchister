<?php

$lMController = $POINTER . 'ext/get_project_milestones';



?>


<script>
	function addNewMilestone(){
		
		//$('.mapping-theme_id').change();
		

		showModal('newMilestone');
	}
</script>
<?php
if( !isset($viewOnly) ){
?>
<a onclick="addNewMilestone();" class="dataActionBtn actionBtn"
		style="width: 20%;background: var(--strategy) !important;margin:0;margin-inline-start: 5%;">
		<span class="label"><?= lang("Add_new_Milestone"); ?></span>
	</a>
<?php
}
?>
	
	
<div class="tableContainer" id="pmListDCd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("KPI"); ?></div>
				<div class="th"><?= lang("Milestone"); ?></div>
				<div class="th"><?= lang("Descriptrion"); ?></div>
				<div class="th"><?= lang("Owner"); ?></div>
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
		<div class="tableBody" id="pmListDC"></div>
	</div>
</div>



<script>

	function bindMPData(data) {
		console.log('sss');
		$('#pmListDC').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["kpi_title"] + '</div>' +
				'	<div class="td">' + data[i]["milestone_title"] + '</div>' +
				'	<div class="td">' + data[i]["milestone_description"] + '</div>' +
				'	<div class="td">' + data[i]["added_employee"] + '</div>' +
				'	<div class="td"> From (' + data[i]["start_date"] + ') -> To ('+ data[i]["end_date"] + ')</div>' +
<?php
				if( !isset($viewOnly) ){
?>
				'	<div class="td">' + 
				'		<a onclick="deleteProjectItem(' + "'milestone'" + ', ' + data[i]["milestone_id"] + ');" class="randomTableBtn randomDanger"><i class="fa-solid fa-trash"></i></a>'
				'	</div>' +
<?php
}
?>

				'</div>';

			$('#pmListDC').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#pmListDC').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getProjectMilestones() {
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
						bindMPData(responser[0].data);
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