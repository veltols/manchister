<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Notifications_List");

$pageDataController = $POINTER . "get_notifications_list";


$pageId = 20000;
$subPageId = 20000;
include("app/assets.php");




?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Notifications_List", "AAR"); ?></h1>
	</div>
	<div class="pageOptions">
	</div>
</div>






<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Date"); ?></div>
				<div class="th"><?= lang("Remark"); ?></div>
				<div class="th"><?= lang("Link"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData"></div>
	</div>
</div>




<script>
	async function bindData(data) {

		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>' + data[i]["related_page"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Go_To_Page"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="notification-' + data[i]["notification_id"] + '">' +
				'	<div class="td">' + data[i]["notification_date"] + '</div>' +
				'	<div class="td">' + data[i]["notification_text"] + '</div>' +
				'	<div class="td ">' +
				'		<input type="hidden" class="user_type" value="' + data[i]["user_type"] + '" >' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("You_have_no_new_notifications"); ?><br><div class="tblBtnsGroup">' +
				'' +
				'</div><br></div></div>');
		}

	}

	function addNewNotification() {
		showModal('newNotificationModal');
	}

	function getData(dId, mdId) {
		var notification_code = $('#notification-' + dId + ' .notification_code').text();
		var notification_name = $('#notification-' + dId + ' .notification_name').text();
		var user_type = $('#notification-' + dId + ' .user_type').val();
		$('.edit_notification_id').val(dId);
		$('.edit_main_notification_id').val(mdId);
		$('.edit_notification_code').val(notification_code);
		$('.edit_notification_name').val(notification_name);
		$('.edit_user_type').val(user_type);
		showModal('viewModal');
	}
</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>