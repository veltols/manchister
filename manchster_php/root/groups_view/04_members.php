<script>

	function loadGroupMembers() {
		start_loader('article');
		$.ajax({
			type: 'POST',
			url: '<?= $getGroupDataController; ?>',
			dataType: "JSON",
			data: { 'group_id': activeGroup, 'op': 'members' },
			success: function (responser) {
				onCall = false;
				end_loader('article');
				switchTab(4);
				if (responser[0].success == true && responser[0].data) {
					bindGroupMembers(responser[0].data);
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

	function removeGroupMember(rId) {
		var aa = confirm("Are you sure?");
		if (aa == true) {
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $getGroupDataController; ?>',
				dataType: "JSON",
				data: { 'group_id': activeGroup, 'op': 'remove_members', 'record_id': rId },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true) {
						makeSiteAlert('suc', "Member Removed");
						loadGroupMembers();
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

	function promoteGroupMember(rId) {
		var aa = confirm("Are you sure?");
		if (aa == true) {
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $getGroupDataController; ?>',
				dataType: "JSON",
				data: { 'group_id': activeGroup, 'op': 'promote_members', 'record_id': rId },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true) {
						makeSiteAlert('suc', "Member Removed");
						loadGroupMembers();
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
<div class="groupMembers">
	<div class="filesActions">
		<div class="actionItem" style="width:2%;min-width: 1%;">
			<br>
		</div>
		<div class="actionItem" onclick="showModal('newMemberModal');" title="<?= lang("Upload_File", "AAR"); ?>">
			<i class="fa-solid fa-plus"></i>
			<span><?= lang("Add_Member", "AAR"); ?></span>
		</div>

	</div>
	<div class="tableContainer">
		<div class="table">
			<div class="tableHeader">
				<div class="tr">
					<div class="th tableSpacer">&nbsp;</div>
					<div class="th"><?= lang("Name", "AAR"); ?></div>
					<div class="th"><?= lang("Added_Date", "AAR"); ?></div>
					<div class="th"><?= lang("Role", "AAR"); ?></div>
					<div class="th">&nbsp;</div>
					<div class="th tableSpacer">&nbsp;</div>
				</div>
			</div>
			<div class="tableBody" id="groupMembersList">

			</div>
		</div>
	</div>


</div>

<script>

	function bindGroupMembers(data) {

		$('#groupMembersList').html('');

		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var thsRoleId = getInt(data[i]["group_role_id"]);
			var btns = '';
			btns += '<a onclick="removeGroupMember(' + data[i]["record_id"] + ');" class="tableBtn">Remove</a>';
			if (thsRoleId == 2) {
				btns += '<a onclick="promoteGroupMember(' + data[i]["record_id"] + ');" class="tableBtn tableBtnWarning">Promote to Admin</a>';
			}

			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["record_id"] + '">' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'	<div class="td">' + data[i]["member_name"] + '</div>' +
				'	<div class="td">' + data[i]["added_date"] + '</div>' +
				'	<div class="td">' + data[i]["group_role_name"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'</div>';
			$('#groupMembersList').prepend(dt);
			listCount++;
		}
		if (listCount == 0) {
			$('#groupMembersList').html('<div class="tr levelRow" id="levelRow-sss">' +
				'<div class="td tableSpacer">&nbsp;</div>' +
				'<div class="td">No members Added</div>' +
				'</div>');
		}
	}
</script>