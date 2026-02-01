<?php

$logsController = $POINTER . 'get_atps_cancels';



?>



<div class="tableContainer" id="cancelRequestsd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Submit_date"); ?></div>
				<div class="th"><?= lang("active_date"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="cancelRequests">

		</div>
	</div>
</div>

<script>
	function bindCancelDt(data) {

		$('#cancelRequests').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';


			var cancel_id = data[i]["cancel_id"];



			if (btns == '') {
				btns = 'NA';
			}


			var linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/cancel_request/?atp_id=<?= $atp_id; ?>&cancel_id=' + cancel_id + '" class="tableActionBtn"><?= lang("Action"); ?></a></div>';

			var thsStt = data[i]["cancel_status"];


			var thsBG = 'yellow';
			var thsColor = 'yellow';

			if (thsStt == 'pending') {
				thsBG = 'yellow';
				thsColor = 'black';
			} else if (thsStt == 'pending_approval') {
				thsBG = 'orange';
				thsColor = 'black';
			} else if (thsStt == 'approved') {
				thsBG = 'green';
				thsColor = 'white';
			} else if (thsStt == 'rejected') {
				thsBG = 'red';
				thsColor = 'white';
			}
			thsStt = data[i]["cancel_status"].replace('_', ' ');


			var dt = '<div class="tr levelRow" id="levelRow-' + cancel_id + '">' +
				'	<div class="td">' + data[i]["submission_date"] + '</div>' +
				'	<div class="td">' + data[i]["approved_date"] + '</div>' +
				'	<div class="td"> <span style="background:' + thsBG + ';color:' + thsColor + ';padding: 0.8em;font-weight: bold;text-transform: uppercase;">' + thsStt + '</span></div>' +
				linkBtn +
				'</div>';

			$('#cancelRequests').prepend(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#cancelRequests').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getCancelRequsts() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $logsController; ?>',
				dataType: "JSON",
				data: { 'atp_id': <?= $atp_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindCancelDt(responser[0].data);
					} else {
						makeSiteAlert('err', "General Error, please try later !!");
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