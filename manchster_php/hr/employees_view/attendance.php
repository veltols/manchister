<?php

$attCont = $POINTER . 'get_hr_attendance_list';



?>



<div class="tableContainer" id="">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("checkin_date"); ?></div>
				<div class="th"><?= lang("checkin_time"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
				<div class="th"><?= lang("added_date"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="attDataList"></div>
	</div>
</div>

<script>

	function bindAttData(data) {
		$('#attDataList').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-attendance_id">' + data[i]["attendance_id"] + '</div>' +
				'	<div class="td row-checkin_date">' + data[i]["checkin_date"] + '</div>' +
				'	<div class="td row-checkin_time">' + data[i]["checkin_time"] + '</div>' +
				'	<div class="td row-attendance_remarks">' + data[i]["attendance_remarks"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
				'</div>';

			$('#attDataList').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#attDataList').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getAttendance() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $attCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindAttData(responser[0].data);
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