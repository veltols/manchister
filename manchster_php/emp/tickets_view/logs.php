<?php

$logsController = $POINTER . 'get_sys_logs';



?>



<div class="tableContainer" id="listDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("log"); ?></div>
				<div class="th"><?= lang("log_remark"); ?></div>
				<div class="th"><?= lang("date"); ?></div>
				<div class="th"><?= lang("by"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData"></div>
	</div>
</div>

<script>

	function bindData(data) {
		$('#listData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td">' + data[i]["log_action"] + '</div>' +
				'	<div class="td">' + data[i]["log_remark"] + '</div>' +
				'	<div class="td">' + data[i]["log_date"] + '</div>' +
				'	<div class="td">' + data[i]["logged_by"] + '</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getLogs() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $logsController; ?>',
				dataType: "JSON",
				data: { 'related_table': 'support_tickets_list', 'related_id': <?= $ticket_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindData(responser[0].data);
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