<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Messages");

$messagesListController = $POINTER . 'get_messages_list';

$chatsListController = $POINTER . 'get_messages_chats';

$addNewChatController = $POINTER . 'add_messages_list';

$sendChatController = $POINTER . 'send_messages_list';

$addNewFileController = $POINTER . 'upload_messages_list';
$addNewFileVerController = $POINTER . 'version_messages_list';

$pageId = 400;
include("app/assets.php");

$page_title = $EMPLOYEE_NAME;
?>
<script>
	var newChatAdded = false;
	var activeChat = 0;

	function reduceChatNotifierBadge(amnt) {
		var curCount = getInt($('#chatsBadger').text());
		var nwCount = curCount - amnt;
		if (nwCount == 0) {
			$('#chatsBadger').hide();
		} else {
			$('#chatsBadger').text(nwCount);
		}
	}

	function bnidChatsData(data) {

		$('#chatsContainer').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var ss = getInt(data[i]["total_unread"]);
			var totNots = '		<div class="itemNotificationsCount">' + data[i]["total_unread"] + '</div>';
			if (ss == 0) {
				totNots = '';
			}

			var dt = '';
			dt = '<div onclick="loadChatMessages(' + data[i]["chat_id"] + ",'" + data[i]["b_name"] + "', '" + data[i]["b_initials"] + "', " + ss + ');" class="groupItem" id="chat-' + data[i]["chat_id"] + '">' +
				'		<img class="itemImage itemImageAtMessages" src="<?= $POINTER; ?>../uploads/' + data[i]["b_picture"] + '" alt="Employee Image">' +
				'		<div class="itemName"><span>' + data[i]["b_name"] + '</span><i>' + data[i]["other_user_status"] + '</i></div>' +
				totNots +

				'		</div>';

			$('#chatsContainer').prepend(dt);
			listCount++;
		}
	}
	function loadChats() {
		start_loader('article');
		$.ajax({
			type: 'POST',
			url: '<?= $messagesListController; ?>',
			dataType: "JSON",
			data: { 'no_data': 0 },
			success: function (responser) {
				onCall = false;
				end_loader('article');
				if (responser[0].success == true && responser[0].data) {
					bnidChatsData(responser[0].data);
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


<!-- Main Groups Container START -->
<div class="groupsContainer">

	<!-- Groups Manager START -->
	<div class="groupsManager">
		<!-- Groups Header START -->
		<div class="gmHeader">
			<span class="titler"><?= lang("My_Inbox", "AAR"); ?></span>
			<span class="searcherBtn">
				<i onclick="showModal('newChatModal');" title="<?= lang("Start_new_Chat", "AAR"); ?>"
					class="fa-solid fa-plus"></i>
			</span>
		</div>
		<!-- Groups Header END -->

		<!-- Groups Lister START -->
		<div class="groupsLister" id="chatsContainer"></div>
		<!-- Groups Lister END -->

	</div>
	<!-- Groups Manager END -->



	<!-- Groups Viewer START -->
	<div class="groupsViewer">
		<!-- Groups Tab Header START -->
		<div class="tabHeader tabHeaderHidden">
			<div class="tabHeaderGroupName">
				<div class="itemImageTxt" id="groupMainInit"><span></span></div>
				<div class="itemName"><span id="groupMainName"></span></div>
			</div>
			<div class="tabHeaderItems">
				<div id="tabHeader-1" onclick="switchTab(1);loadChatMessages(activeChat, sName, sInit);" class="tabItem tabItemActive">
					<span><?= lang("Messages", "AAR"); ?></span>
				</div>
				<div id="tabHeader-2" onclick="loadChatFiles();" class="tabItem">
					<span><?= lang("Files", "AAR"); ?></span>
				</div>

			</div>
		</div>
		<!-- Groups Tab Header END -->

		<!-- Groups Tab Body START -->
		<div class="tabBody">
			<div id="tabBody-0" class="tabBodyItem tabBodyItemActive">
				<br><br>&nbsp;&nbsp;&nbsp;Select a chat to view
			</div>
			<div id="tabBody-1" class="tabBodyItem">
				<?php
				include("messages_view/01_posts.php");
				?>
			</div>
			<div id="tabBody-2" class="tabBodyItem">
				<?php
				include("messages_view/02_files.php");
				?>
			</div>


		</div>
		<!-- Groups Tab Body END -->
	</div>
	<!-- Groups Viewer END -->

</div>
<!-- Main Groups Container END -->



<?php
include("app/footer.php");
?>


<!-- Modals START -->
<!-- Upload New File -->
<div class="modal" id="newfileModal">
	<div class="modalHeader">
		<h1><?= lang("Upload_File", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newFileForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file_name", "AAR"); ?></label>
					<input type="hidden" class="inputer chat-id-service" data-name="chat_id" data-den="0" data-req="1"
						data-type="hidden">
					<input type="text" class="inputer" data-name="file_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="uploaded_file" data-den="" data-req="1"
						data-type="file">
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newFileForm', '<?= $addNewFileController; ?>');newfileAdded=true;"><?= lang("Upload", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->

<!-- Modals START -->
<!-- add New File Version -->
<div class="modal" id="newfileVersionModal">
	<div class="modalHeader">
		<h1><?= lang("Upload_New_File_Version", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newFileVersionForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file_name", "AAR"); ?></label>
					<input type="hidden" class="inputer chat-id-service" data-name="chat_id" data-den="0" data-req="1"
						data-type="hidden">
					<input type="hidden" class="inputer file_id_at_version" data-name="file_id" data-den="0"
						data-req="1" data-type="hidden">
					<input type="text" class="" id="file_name_at_version" data-den="" data-req="1" data-type="text"
						readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("New_Version", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="uploaded_file" data-den="" data-req="1"
						data-type="file">
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newFileVersionForm', '<?= $addNewFileVerController; ?>');newfileAdded=true;"><?= lang("Upload", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->


<!-- Modals START -->
<!-- Start_New_Chat -->
<div class="modal" id="newChatModal">
	<div class="modalHeader">
		<h1><?= lang("Start_New_Chat", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newChatForm">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Employee", "AAR"); ?></label>
					<select class="inputer" data-name="employee_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_employees_list_sel = "SELECT * FROM  `employees_list` WHERE ((`employee_id` <> $USER_ID) AND (`is_deleted` = 0) AND (`is_hidden` = 0))";
						$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
						if (mysqli_num_rows($qu_employees_list_EXE)) {
							while ($employees_list_REC = mysqli_fetch_assoc($qu_employees_list_EXE)) {
								$employee_id = (int) $employees_list_REC['employee_id'];
								$namer = $employees_list_REC['first_name'] . " " . $employees_list_REC['last_name'];
								?>
								<option value="<?= $employee_id; ?>"><?= $namer; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newChatForm', '<?= $addNewChatController; ?>');newChatAdded=true;"><?= lang("Start", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->




<script>
	function afterFormSubmission() {
		if (newChatAdded == true) {
			newChatAdded = false;
			loadChats();
			closeModal();
		} else if (newfileAdded == true) {
			newfileAdded = false;
			loadChatFiles();
			closeModal();
		}
	}
</script>


<script>
	$(".messagesContainer").scrollTop($(".messagesContainer")[0].scrollHeight - 50);



	loadChats();
</script>