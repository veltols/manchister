<?php

$logsController = $POINTER . 'get_employee_assets';



?>



<div class="tableContainer" id="assetssListDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th tableSpacer">&nbsp;</div>
				<div class="th"><?= lang("asset_ref"); ?></div>
				<div class="th"><?= lang("Asset"); ?></div>
				<div class="th"><?= lang("SKU"); ?></div>
				<div class="th"><?= lang("Serial"); ?></div>
				<div class="th"><?= lang("expiry_date"); ?></div>
				<div class="th"><?= lang("category"); ?></div>
				<div class="th"><?= lang("Assigned_Date"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
				<div class="th tableSpacer">&nbsp;</div>
			</div>
		</div>
		<div class="tableBody" id="assetssListData"></div>
	</div>
</div>

<script>

	function reassignAsset(aId) {
		$('.asset_id_at_revoke').val(aId);
		showModal('assetStatusModal');
	}
	function bindAssetData(data) {
		$('#assetssListData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			btns += '<a onclick="reassignAsset(' + data[i]["asset_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-times"></i>' +
				'	<div class="tooltip"><?= lang("Revoke_Assignation"); ?></div>' +
				'</a>';
			btns += '<a href="<?= $POINTER; ?>assets/view/?asset_id=' + data[i]["asset_id"] + '" class="dataActionBtn tableBtnWarning hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Go_to_Asset_details"); ?></div>' +
				'</a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'	<div class="td">' + data[i]["asset_ref"] + '</div>' +
				'	<div class="td">' + data[i]["asset_name"] + '</div>' +
				'	<div class="td">' + data[i]["asset_sku"] + '</div>' +
				'	<div class="td">' + data[i]["asset_serial"] + '</div>' +
				'	<div class="td">' + data[i]["expiry_date"] + '</div>' +
				'	<div class="td">' + data[i]["category_name"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_date"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'</div>';

			$('#assetssListData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#assetssListData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getEmployeeAssets() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $logsController; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindAssetData(responser[0].data);
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