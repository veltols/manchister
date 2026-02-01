<?php

$leavesCont = $POINTER . 'get_hr_leaves_list';



?>



<div class="tableContainer" id="leavesDatad">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Leave_Type"); ?></div>
				<div class="th"><?= lang("submission_date"); ?></div>
				<div class="th"><?= lang("Leave_Start"); ?></div>
				<div class="th"><?= lang("Leave_End"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Remarks"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="leavesData"></div>
	</div>
</div>

<script>

	function bindLeavesData(data) {
		$('#leavesData').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';
			//btns = '<a href="<?= $POINTER; ?>tokens_financials/prices/view/?record_id=' + data[i]["record_id"] + '" class="tableActionBtn"><?= lang("Details"); ?></a>';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-leave_id">' + data[i]["leave_id"] + '</div>' +
				'	<div class="td row-leave_type">' + data[i]["leave_type"] + '</div>' +
				'	<div class="td row-submission_date">' + data[i]["submission_date"] + '</div>' +
				'	<div class="td row-start_date">' + data[i]["start_date"] + '</div>' +
				'	<div class="td row-end_date">' + data[i]["end_date"] + '</div>' +
				'	<div class="td row-leave_status">' + data[i]["leave_status"] + '</div>' +
				'	<div class="td row-leave_remarks">' + data[i]["leave_remarks"] + '</div>' +
				'</div>';

			$('#leavesData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#leavesData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getLeaves() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $leavesCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindLeavesData(responser[0].data);
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