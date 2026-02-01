<?php

$permCont = $POINTER . 'get_hr_permissions_list';



?>



<div class="tableContainer" id="permDataListd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("submission_date"); ?></div>
				<div class="th"><?= lang("Permission_date"); ?></div>
				<div class="th"><?= lang("Permission_Start"); ?></div>
				<div class="th"><?= lang("Permission_End"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Remarks"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="permDataList"></div>
	</div>
</div>

<script>

	function dindPermData(data) {
		$('#permDataList').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-permission_id">' + data[i]["permission_id"] + '</div>' +
				'	<div class="td row-submission_date">' + data[i]["submission_date"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["start_date"] + '</div>' +
				'	<div class="td row-start_time">' + data[i]["start_time"] + '</div>' +
				'	<div class="td row-end_time">' + data[i]["end_time"] + '</div>' +
				'	<div class="td row-permission_status">' + data[i]["permission_status"] + '</div>' +
				'	<div class="td row-permission_remarks">' + data[i]["permission_remarks"] + '</div>' +
				'</div>';

			$('#permDataList').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#permDataList').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getpermissions() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $permCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						dindPermData(responser[0].data);
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