<?php

$interCont = $POINTER . 'get_hr_exit_interviews_list';



?>



<div class="tableContainer" id="">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Designation"); ?></div>
				<div class="th"><?= lang("interview_date"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="intDataList"></div>
	</div>
</div>

<script>

	function bindIntData(data) {
		$('#intDataList').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-interview_id">' + data[i]["interview_id"] + '</div>' +
				'	<div class="td row-checkin_date">' + data[i]["designation_name"] + '</div>' +
				'	<div class="td row-checkin_time">' + data[i]["interview_date"] + '</div>' +
				'	<div class="td row-interview_remarks">' + data[i]["interview_remarks"] + '</div>' +
				'</div>';

			$('#intDataList').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#intDataList').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getInterviews() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $interCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindIntData(responser[0].data);
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