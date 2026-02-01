<?php

$reqListController = $POINTER . 'get_info_request_list';



?>



<h3><?= lang("Forms"); ?></h3>
<div class="tableContainer" id="appsListDtd" style="width: 95%;">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Application"); ?></div>
				<!--div class="th"><?= lang("status"); ?></div-->
				<!--div class="th"><?= lang("submission_start"); ?></div-->
				<!--div class="th"><?= lang("submitted_date"); ?></div-->
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt2"></div>
	</div>
</div>
<div><br><br><br><br></div>
<h3><?= lang("Information_Request"); ?></h3>
<div class="tableContainer" id="appsListDtd02" style="width: 95%;">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("request_date"); ?></div>
				<div class="th"><?= lang("request_By"); ?></div>
				<div class="th"><?= lang("response_date"); ?></div>
				<div class="th"><?= lang("status"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="reqList"></div>
		<div class="tableBody">
			<div class="tr">
				<div class="td"><a href="<?= $POINTER; ?>atps/new_info_request/?atp_id=<?= $atp_id; ?>"
						class="tableActionBtn"><?= lang("New_Request"); ?></a></div>
			</div>
		</div>
	</div>
</div>
<div><br><br><br><br></div>

<script>
	function bindthsAppsData2(data) {

		$('#reqList').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {

			var thsStt = data[i]["request_status"];
			var thsBG = 'yellow';
			var thsColor = 'yellow';
			var thsStatus = 'NA';

			if (thsStt == 'pending_submission') {
				thsBG = 'yellow';
				thsColor = 'black';
				thsStatus = 'Pending';
			} else if (thsStt == 'submitted') {
				thsBG = 'green';
				thsColor = 'white';
				thsStatus = 'Submitted';
			}

			var dt = '';
			var linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view_info_request/?request_id=' + data[i]["request_id"] + '&atp_id=<?= $atp_id; ?>" class="tableActionBtn"><?= lang("View"); ?></a></div>';
			dt = '<div class="tr levelRow" id="levelRow-' + data[i]["request_id"] + '">' +
				'	<div class="td">' + data[i]["request_date"] + '</div>' +
				'	<div class="td">' + data[i]["added_by"] + '</div>' +
				'	<div class="td">' + data[i]["response_date"] + '</div>' +
				'	<div class="td"> <span style="background:' + thsBG + ';color:' + thsColor + ';padding: 0.8em;font-weight: bold;text-transform: uppercase;">' + thsStatus + '</span></div>' +
				linkBtn +
				'</div>';

			$('#reqList').prepend(dt);
			listCount++;


		}



	}

	function loadForms() {
		$('#appsListDt2').html('');
		appName = 'Internal Remote Activity Feedback Form';
		linker = '006';
		var linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		var dt = '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';
		appName = 'Evidence Collection Log';
		linker = '007';
		var linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-2">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		$('#appsListDt2').prepend(dt);
	}

	function getApps2() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $reqListController; ?>',
				dataType: "JSON",
				data: { 'atp_id': <?= $atp_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindthsAppsData2(responser[0].data);
						loadForms();
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