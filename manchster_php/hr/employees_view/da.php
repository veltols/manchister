<?php

$daCont = $POINTER . "get_hr_da_list";



?>



<div class="tableContainer" id="DAListDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("added_date"); ?></div>
				<div class="th"><?= lang("warning"); ?></div>
				<div class="th"><?= lang("type"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Remarks"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="DAListData"></div>
	</div>
</div>

<script>

	function bindDaData(data) {
		$('#DAListData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-da_id">' + data[i]["da_id"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
				'	<div class="td row-da_warning">' + data[i]["da_warning"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["da_type"] + '</div>' +
				'	<div class="td row-end_date">' + data[i]["da_status"] + '</div>' +
				'	<div class="td row-leave_remarks">' + data[i]["da_remark"] + '</div>' +
				'</div>';

			$('#DAListData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#DAListData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getDA() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $daCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindDaData(responser[0].data);
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