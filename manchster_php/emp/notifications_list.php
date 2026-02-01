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
	
	<div class="pageNav">
		
	</div>
	<div class="pageOptions">
		
		<a class="pageLink pageLinkWithTxt" onclick="markAsRead(0);">
			<div class="linkTxt"><?= lang("Mark_all_read"); ?></div>
		</a>
		<a id="selectorMarker" class="pageLink pageLinkWithTxt" onclick="markSelectedAsRead(0);">
			<div class="linkTxt"><?= lang("Mark_Selected_as_read"); ?></div>
		</a>

	</div>
</div>


<script>
	
	function toggleAllSelect(){
		
		$('.chkSelector').each( function(){
				$(this).click();
			} );

		if( $('.notSelected').length > 0 ){
			$('#selectorMarker').show();
		} else {
			$('#selectorMarker').hide();
		}

	}
	function selectThs( nId ){
		var thsNot = getInt( $('#notification-'+ nId + ' .is_noti_selected').val() );
		var fr = 0;
		if( thsNot == 0 ){
			fr = nId;
			$('#notification-'+ nId + ' .is_noti_selected').addClass('notSelected');
		} else {
			fr = 0;
			$('#notification-'+ nId + ' .is_noti_selected').removeClass('notSelected');
		}
		$('#notification-'+ nId + ' .is_noti_selected').val(fr);
		
		if( $('.notSelected').length > 0 ){
			$('#selectorMarker').show();
		} else {
			$('#selectorMarker').hide();
		}
	}

	$('#selectorMarker').hide();
</script>


<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><input type="checkbox" onchange="toggleAllSelect();"></div>
				<div class="th"><?= lang("Date"); ?></div>
				<div class="th"><?= lang("Remark"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
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
			var is_seen = getInt( data[i]["is_seen"] );

			var btns = '';
			btns += '<a href="<?= $POINTER; ?>' + data[i]["related_page"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Go_To_Page"); ?></div>' +
				'</a>';
			if( is_seen == 0 ){
			btns += '<a onclick="markAsRead(' + data[i]["notification_id"] + ');" class="dataActionBtn markerBtn hasTooltip" id="hider-' + data[i]["notification_id"] + '">' +
				'	<i class="fa-solid fa-check"></i>' +
				'	<div class="tooltip"><?= lang("Mark_as_read"); ?></div>' +
				'</a>';
			}
			
			//btns= '---';
			var dt = '<div class="tr levelRow" id="notification-' + data[i]["notification_id"] + '">' +
				'	<div class="td"><input type="checkbox" class="chkSelector" onchange="selectThs(' + data[i]["notification_id"] + ');">' + 
				' <input type="hidden" class="is_noti_selected" value="0"> ' + 
				'	</div>' +
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
	

	function markSelectedAsRead(nId){
		if($('.notSelected').length > 0){
			var allNots = '';

			$('.notSelected').each( function(){

				var thsId = getInt($(this).val());
				if( thsId != 0 ){
					allNots = thsId + '-' + allNots;
				}
				
			} );
			allNots = allNots.trim();
			allNots = allNots.slice(0, -1);
			if( allNots != '' ){
				$.ajax({
					type: 'POST',
					url: '<?= $POINTER; ?>read_notification_list',
					dataType: "JSON",
					data: { 'notification_id': 0, 'all_notifications' : allNots },
					success: function (responser) {
						if (responser[0].success == true) {
							
							$('.notSelected').each( function(){

								var thsId = getInt($(this).val());
								
								if( thsId == 0 ){
									$('.markerBtn').hide();
								} else {
									$('#hider-' + thsId).hide();
								}
								
							} );


							
							makeSiteAlert('suc', "Marked as read");
							getNewNotifications();

						} else {
							console.log("General Error, please try later !!");
							makeSiteAlert('err', "General Error, please try later !!");
						}
					},
					error: function () {
						console.log("General Error, please try later !!");
						makeSiteAlert('err', "General Error, please try later !!");
					}
				});
			}
		}
		
	
	}

	function markAsRead(nId){
		
		$.ajax({
			type: 'POST',
			url: '<?= $POINTER; ?>read_notification_list',
			dataType: "JSON",
			data: { 'notification_id': nId },
			success: function (responser) {
				if (responser[0].success == true) {
					if( nId == 0 ){
						$('.markerBtn').hide();
					} else {
						$('#hider-' + nId).hide();
					}
					
					makeSiteAlert('suc', "Marked as read");
					getNewNotifications();

				} else {
					console.log("General Error, please try later !!");
					makeSiteAlert('err', "General Error, please try later !!");
				}
			},
			error: function () {
				console.log("General Error, please try later !!");
				makeSiteAlert('err', "General Error, please try later !!");
			}
		});
	
	}
</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>