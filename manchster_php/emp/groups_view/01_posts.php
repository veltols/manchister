<script>
	function loadGroupMessages() {
		switchTab(1);
		start_loader('article');
		$.ajax({
			type: 'POST',
			url: '<?= $getGroupDataController; ?>',
			dataType: "JSON",
			data: { 'group_id': activeGroup, 'op': 'posts' },
			success: function (responser) {
				onCall = false;
				end_loader('article');
				switchTab(1);
				if (responser[0].success == true && responser[0].data) {
					bindGroupPosts(responser[0].data);
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
<div class="groupPostsContainer">

	<div class="messagesContainer" id="groupPosts"></div>


	<div class="sendMessageForm">
		<textarea name="NewMessage" id="newMessageContent"
			placeholder="<?= lang("Type to send a new message", "AAR"); ?>" rows="3"></textarea>
		<div class="sendActions">

			<div class="sendOptions">
				<?php
				/*
							<i class="fa-solid fa-paperclip" onclick="showModal('newfileModal');"
								title="<?= lang("Attach_Document", "AAR"); ?>"></i>
								*/
				?>
			</div>
			<div onclick="sendChatMessage();" class="sendBtn" title="<?= lang("Send", "AAR"); ?>">
				<i class="fa-solid fa-paper-plane"></i>
			</div>
		</div>
	</div>


	<script>
		function sendChatMessage() {
			var mContent = $('#newMessageContent').val();
			if (mContent != '') {
				//send message
				start_loader('article');
				$.ajax({
					type: 'POST',
					url: '<?= $getGroupDataController; ?>',
					dataType: "JSON",
					data: { 'group_id': activeGroup, 'op': 'posts_new', 'post': mContent },
					success: function (responser) {
						onCall = false;
						end_loader('article');
						$('#newMessageContent').val('');
						loadGroupMessages();
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
</div>
<script>

	function bindGroupPosts(data) {

		$('#groupPosts').html('');

		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var post_type = data[i]["post_type"];
			var sender_id = data[i]["sender_id"];
			var dt = '';
			var myClass = '';
			if (sender_id == <?= $USER_ID; ?>) {
				myClass = 'myMessage';
			}
			if (post_type == 'text') {

				dt = '<div class="groupMessage ' + myClass + '">' +
					'	<img class="senderImage" src="<?= $POINTER; ?>../uploads/user.png" alt="Image">' +
					'	<div class="messageContent">' +
					'		<div class="senderNameDate">' +
					'			<div class="senderName"><span>' + data[i]["sender_name"] + '</span></div>' +
					'			<div class="senderDate"><span>' + data[i]["added_date"] + '</span></div>' +
					'		</div>' +
					'		<div class="messageContentText">' + data[i]["post_text"] + '</div>' +
					'	</div>' +
					'</div>';

			} else if (post_type == 'document') {
				dt = '<div class="groupMessage ' + myClass + '">' +
					'	<img class="senderImage" src="<?= $POINTER; ?>../uploads/user.png" alt="Image">' +
					'	<div class="messageContent">' +
					'		<div class="senderNameDate">' +
					'			<div class="senderName"><span>' + data[i]["sender_name"] + '</span></div>' +
					'			<div class="senderDate"><span>' + data[i]["added_date"] + '</span></div>' +
					'		</div>' +
					'		<div class="messageContentText">' + data[i]["post_text"] + '</div>' +
					'		<a href="<?= $POINTER; ?>../uploads/' + data[i]["post_file_path"] + '" target="_blank" class="messageContentDocument">' +
					'			<i class="fa-solid fa-file"></i>' +
					'			<span class="documentName">' + data[i]["post_file_name"] + '</span>' +
					'		</a>' +
					'	</div>' +
					'</div > ';

			} else if (post_type == 'image') {
				dt = '<div class="groupMessage ' + myClass + '">' +
					'	<img class="senderImage" src="<?= $POINTER; ?>../uploads/user.png" alt="Image">' +
					'	<div class="messageContent">' +
					'		<div class="senderNameDate">' +
					'			<div class="senderName"><span>' + data[i]["sender_name"] + '</span></div>' +
					'			<div class="senderDate"><span>' + data[i]["added_date"] + '</span></div>' +
					'		</div>' +
					'		<div class="messageContentText">' + data[i]["post_text"] + '</div>' +
					'		<div class="messageContentImage">' +
					'			<img class="contentImage" src="<?= $POINTER; ?>../uploads/' + data[i]["post_file_path"] + '" alt="Image">' +
					'		</div>' +
					'	</div>' +
					'</div>';
			}


			$('#groupPosts').append(dt);
			listCount++;
		}
		if (listCount == 0) {
			$('#groupPosts').html('<div class="tr levelRow" id="levelRow-sss">' +
				'<div class="td tableSpacer">&nbsp;</div>' +
				'<div class="td">No Messages</div>' +
				'</div>');
		}

		$("#groupPosts").animate({
			scrollTop: $("#groupPosts")[0].scrollHeight
		}, 1500);
	}
</script>