<?php

$AssignsController = $POINTER . 'get_assets_assigns';



?>



<div class="tableContainer" id="assignListDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("assigned_date"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
				<div class="th"><?= lang("assigned_by"); ?></div>
				<div class="th"><?= lang("assigned_to"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="assignListData"></div>
	</div>
</div>

<script>

	function bindAssignData(data) {
		$('#assignListData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["assigned_date"] + '</div>' +
				'	<div class="td">' + data[i]["assign_remarks"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_by"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_to"] + '</div>' +
				'</div>';

			$('#assignListData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#assignListData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getAssigns() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $AssignsController; ?>',
				dataType: "JSON",
				data: { 'asset_id': <?= $asset_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindAssignData(responser[0].data);
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