<script>
/*
	function loadGroupFiles() {
		start_loader('article');
		$.ajax({
			type: 'POST',
			url: '<?= $getGroupDataController; ?>',
	dataType: "JSON",
		data: { 'group_id': activeGroup, 'op': 'files' },
	success: function (responser) {
		onCall = false;
		end_loader('article');
		switchTab(2);
		if (responser[0].success == true && responser[0].data) {
			bindGroupFiles(responser[0].data);
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
	*/
</script>
<div class="filesContainer">
	<div class="filesActions">


		<div class="actionItem" title="<?= lang("Refresh", "AAR"); ?>" onclick="">
			<i class="fa-solid fa-rotate"></i>
			<span><?= lang("Refresh", "AAR"); ?></span>
		</div>
	</div>
	<div class="filesData">



		<div class="tableContainer">
			<div class="table">
				<div class="tableHeader">
					<div class="tr">
						<div class="th tableSpacer">&nbsp;</div>
						<div class="th"><?= lang("Approval", "AAR"); ?></div>
						<div class="th"><?= lang("Date", "AAR"); ?></div>
						<div class="th"><?= lang("Note", "AAR"); ?></div>
						<div class="th"><?= lang("Action", "AAR"); ?></div>
						<div class="th">&nbsp;</div>
						<div class="th tableSpacer">&nbsp;</div>
					</div>
				</div>
				<div class="tableBody" id="groupFilesList">

					<div class="tr levelRow" id="levelRow-sss">
						<div class="td tableSpacer">&nbsp;</div>
						<div class="td">No Data Found</div>
					</div>

				</div>
			</div>
		</div>


	</div>
</div>

<script>
/*
	function bindGroupFiles(data) {

		$('#groupFilesList').html('');

		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["file_id"] + '">' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'	<div class="td"><i class="fa-solid fa-file"></i></div>' +
				'	<div class="td">' + data[i]["file_name"] + '</div>' +
				'	<div class="td">' + data[i]["added_date"] + '</div>' +
				'	<div class="td">' + data[i]["added_name"] + '</div>' +
				'	<div class="td"><a href="<?= $POINTER; ?>../ uploads / ' + data[i]["file_path"] + '" target="_blank" class="tableBtn">View</a></div>' +
	'	<div class="td tableSpacer">&nbsp;</div>' +
		'</div>';

	$('#groupFilesList').prepend(dt);
	listCount++;
		}
	if (listCount == 0) {
		$('#groupFilesList').html('<div class="tr levelRow" id="levelRow-sss">' +
			'<div class="td tableSpacer">&nbsp;</div>' +
			'<div class="td">No Files Attached</div>' +
			'</div>');
	}
	}
	*/
</script>