<script>

	function loadChatFiles() {
		start_loader('article');
		$.ajax({
			type: 'POST',
			url: '<?= $POINTER; ?>files_messages_list',
			dataType: "JSON",
			data: { 'chat_id': activeChat, 'op': 'files' },
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
</script>
<div class="filesContainer">
	<div class="filesActions">
		<div class="actionItem"
			onclick="$('.file_name_new').val('');$('.uploaded_file_new').val('');showModal('newfileModal');"
			title="<?= lang("Upload_File", "AAR"); ?>">
			<i class="fa-solid fa-upload"></i>
			<span><?= lang("Upload_File", "AAR"); ?></span>
		</div>

		<div class="actionItem" title="<?= lang("Sync_Files", "AAR"); ?>" onclick="loadChatFiles();">
			<i class="fa-solid fa-rotate"></i>
			<span><?= lang("Sync_Files", "AAR"); ?></span>
		</div>
	</div>
	<div class="filesData">



		<div class="tableContainer">
			<div class="table">
				<div class="tableHeader">
					<div class="tr">
						<div class="th tableSpacer">&nbsp;</div>
						<div class="th"><i class="fa-solid fa-file"></i></div>
						<div class="th"><?= lang("Name", "AAR"); ?></div>
						<div class="th"><?= lang("Version", "AAR"); ?></div>
						<div class="th"><?= lang("Uploaded", "AAR"); ?></div>
						<div class="th"><?= lang("Uploaded_By", "AAR"); ?></div>
						<div class="th">&nbsp;</div>
						<div class="th tableSpacer">&nbsp;</div>
					</div>
				</div>
				<div class="tableBody" id="groupFilesList">

					<div class="tr levelRow" id="levelRow-sss">
						<div class="td tableSpacer">&nbsp;</div>
						<div class="td">No Files Attached</div>
					</div>

				</div>
			</div>
		</div>


	</div>
</div>

<script>

	function bindGroupFiles(data) {

		$('#groupFilesList').html('');

		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '<a href="<?= $POINTER; ?>../uploads/' + data[i]["file_path"] + '" target="_blank" class="tableBtn">View</a>';
			btns += '<a href="<?= $POINTER; ?>../uploads/' + data[i]["file_path"] + '" target="_blank" class="tableBtn tableBtnWarning" download="' + data[i]["file_path"] + '">Download</a>';
			var file_name = data[i]["file_name"];

			var fileId = getInt(data[i]["file_id"]);
			var mainFileId = getInt(data[i]["main_file_id"]);
			var fileNewVerionId = fileId;
			var thsClass = '';
			var fileIcon = '<i class="fa-solid fa-file"></i>';
			if (mainFileId != 0) {
				fileNewVerionId = mainFileId;
				thsClass = 'trDarker';
				fileIcon = '';
				file_name = '';
			} else {
				btns += '<a onclick="uploadNewFileVersion(' + fileNewVerionId + ", '" + data[i]["file_name"] + "'" + ');" class="tableBtn tableBtnInfo">Upload New Version</a>';
			}
			var dt = '<div class="tr levelRow ' + thsClass + '" id="levelRow-' + data[i]["file_id"] + '">' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'	<div class="td">' + fileIcon + '</div>' +
				'	<div class="td">' + file_name + '</div>' +
				'	<div class="td">v' + data[i]["file_version"] + '</div>' +
				'	<div class="td">' + data[i]["added_date"] + '</div>' +
				'	<div class="td">' + data[i]["added_name"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				'			' + btns +
				'		</div>' +
				'	</div>' +
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

	function uploadNewFileVersion(file_id, file_name) {
		//newfileVersionModal
		//file_name_at_version
		$('#file_name_at_version').val(file_name);
		$('.file_id_at_version').val(file_id);
		showModal('newfileVersionModal');

	}
</script>