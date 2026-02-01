<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Training_Providers");

$pageDataController = $POINTER . "ext/get_atps_list";
$addNewAtpController = $POINTER . "ext/add_atps_list";
$updateAtpController = $POINTER . "ext/update_atps_list";

$pageId = 10001;
$subPageId = 200;

$stt = isset( $_GET['stt'] ) ? (int) test_inputs($_GET['stt']) : 0;
if( !checkUserService($pageId) ){
	die("You don't have permission to view this page!");
}

$extra_id = (int) $stt;
if( $extra_id == 0 ){
	$subPageId = 200;
} else if( $extra_id == 1 ){
	$subPageId = 300;
} else if( $extra_id == 3 ){
	$subPageId = 400;
} else if( $extra_id == 4 ){
	$subPageId = 500;
} else if( $extra_id == 5 ){
	$subPageId = 600;
}
include("app/assets.php");








?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= $page_title; ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('atps_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" href="<?= $POINTER; ?>ext/atps/new/" >
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("Add_new"); ?></div>
		</a>
	</div>
</div>





<div class="dataContainer" id="listData"></div>

<script>
		async function bindData(data) {
			userDefinedClass = 'data-tiles-view';
			$('#listData').html('<?= lang(''); ?>');
			var listCount = 0;

			for (i = 0; i < data.length; i++) {
				var btns = '';
				btns += '<a href="<?= $POINTER; ?>ext/atps/view/?atp_id=' + data[i]["atp_id"] + '" class="dataActionBtn hasTooltip">' +
					'	<i class="fa-regular fa-eye"></i>' +
					'	<div class="tooltip"><?= lang("Details"); ?></div>' +
					'</a>';



				if (data[i]["status_id"] == '1') {
					btns += '<a onclick="sendRegEmail(' + data[i]["atp_id"] + ')" id="emBtn-' + data[i]["atp_id"] + '" class="dataActionBtn hasTooltip">' +
						'	<i class="fa-solid fa-envelope"></i>' +
						'	<div class="tooltip"><?= lang("Send_Registration_email"); ?></div>' +
						'</a>';
				}


				//btns= '---';
				var dt = '<div class="dataElement ' + userDefinedClass + '" id="levelRow-' + data[i]["atp_id"] + '">' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("REF"); ?></div>' +
					'		<div class="value">' + data[i]["atp_ref"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("Institution_name"); ?></div>' +
					'		<div class="value">' + data[i]["atp_name"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-3">' +
					'		<div class="property"><?= lang("contact_name"); ?></div>' +
					'		<div class="value">' + data[i]["contact_name"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-3">' +
					'		<div class="property"><?= lang("Institution_email"); ?></div>' +
					'		<div class="value">' + data[i]["atp_email"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-3">' +
					'		<div class="property"><?= lang("Institution_phone"); ?></div>' +
					'		<div class="value">' + data[i]["atp_phone"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-3">' +
					'		<div class="property"><?= lang("added_date"); ?></div>' +
					'		<div class="value">' + data[i]["added_date"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-3">' +
					'		<div class="property"><?= lang("last_updated"); ?></div>' +
					'		<div class="value">' + data[i]["last_updated"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("added_by"); ?></div>' +
					'		<div class="value">' + data[i]["added_by"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2" title="' + data[i]["atp_status_desc"] + '">' +
					'		<div class="property"><?= lang("status"); ?></div>' +
					'		<div class="value" id="stt-' + data[i]["atp_id"] + '">' + data[i]["atp_status"] + '</div>' +
					'	</div>' +
					'	<div class="dataOptions">' +
					btns +
					'	</div>' +
					'</div>';

				$('#listData').append(dt);
				listCount++;
			}

			if (listCount == 0) {
				$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
			}
			changeDataView(currentDataViewClass);
		}


		function sendRegEmail(atpId) {
			var aa = confirm('<?= lang("Send_Email_to_Institution?", "AAR"); ?>');
			if (aa) {
				if (atpId != 0) {
					activeRequest = true;
					start_loader('article');
					$.ajax({
						url: '<?= $POINTER; ?>ext/send_atp_init_email',
						dataType: "JSON",
						method: "POST",
						data: { "atp_id": atpId },
						success: function (RES) {
							activeRequest = false;
							end_loader('article');
							thsDt = RES[0];
							if (thsDt['success'] == true) {
								makeSiteAlert('suc', '<?= lang("Email_Sent_Successfully", "AAR"); ?>');
								$('#emBtn-' + atpId).remove();
								$('#stt-' + atpId).html('<?= lang("Email_Sent", "AAR"); ?>');
							} else {
								makeSiteAlert('err', thsDt['message']);
							}
						},
						error: function (res) {
							activeRequest = false;
							end_loader('article');
							console.log(res);
							alert("Failed to load data");
						}
					});

				}
			}
		}
	</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>
<script>
	$('.panelFooter').hide();
</script>