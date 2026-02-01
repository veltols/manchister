<?php
$page_title = $page_description = $page_keywords = $page_author = lang("RPL_Requests");

$pageDataController = $POINTER . "get_qm_rpl_list";

$pageId = 250;
include("app/assets.php");
?>
<!-- MAIN MID VIEW -->
<div class="articleContainer">
	<!-- MAIN MID VIEW -->


	<div class="pageMainTitle">
		<h1><?= $page_title; ?></h1>
		<div class="breadCrumbs">
			<a href="<?= $DIR_dashboard; ?>" class="hasLink"><?= lang('Home', 'ARR'); ?></a>
			<span>\</span>
			<a href="<?= $DIR_rpl; ?>" class="hasLink"><?= lang('rpl', 'ARR'); ?></a>
			<span>\</span>
			<a><?= lang('List', 'ARR'); ?></a>
		</div>
	</div>


	<div class="pageOptions">
		<div class="pageOptionsButtons">
			<!--a class="pageLink hasTooltip" href="<?= $POINTER; ?>rpl/new/?stage=1">
				<i class="fa-solid fa-plus"></i>
				<div class="tooltip"><?= lang("New_Question"); ?></div>
			</a-->

			<div class="vr"></div>

			<a class="pageLink hasTooltip" onclick="changeDataView('data-list-view');" id="data-list-view">
				<i class="fa-solid fa-list"></i>
				<div class="tooltip"><?= lang("show list"); ?></div>
			</a>

			<a class="pageLink hasTooltip" onclick="changeDataView('data-tiles-view');" id="data-tiles-view">
				<i class="fa-solid fa-table-cells"></i>
				<div class="tooltip"><?= lang("show tiles"); ?></div>
			</a>

		</div>
		<div class="pageSearchForm">

			<input type="text" id="pageSearcher" placeholder="<?= lang("Search"); ?>">
			<a class="pageLink pageLinkActive" onclick="searchRecords();">
				<i class="fa-solid fa-magnifying-glass"></i>
			</a>

		</div>
	</div>




	<div class="dataContainer" id="listData"></div>


	<script>
		async function bindData(data) {
			userDefinedClass = await getCurrentDataView();
			$('#listData').html('<?= lang(''); ?>');
			var listCount = 0;

			for (i = 0; i < data.length; i++) {
				var btns = '';
				var thsStt = getInt(data[i]["request_status_id"]);

				if (thsStt == 7) {
					btns += '<a href="<?= $POINTER; ?>qm_rpl/qm_012_initial/?request_id=' + data[i]["request_id"] + '&qm=1"class="dataActionBtn hasTooltip">' +
						'	<i class="fa-solid fa-file"></i>' +
						'	<div class="tooltip"><?= lang("Form_Mapping"); ?></div>' +
						'</a>';
				}


				//btns= '---';
				var dt = '<div class="dataElement ' + userDefinedClass + '" id="levelRow-' + data[i]["request_id"] + '">' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("ATP REF"); ?></div>' +
					'		<div class="value">' + data[i]["atp_ref"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("ATP Name"); ?></div>' +
					'		<div class="value">' + data[i]["atp_name"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("request_date"); ?></div>' +
					'		<div class="value">' + data[i]["request_date"] + '</div>' +
					'	</div>' +
					'	<div class="dataView col-2">' +
					'		<div class="property"><?= lang("status"); ?></div>' +
					'		<div class="value" id="stt-' + data[i]["request_id"] + '">' + data[i]["request_status"] + '</div>' +
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


		function approveRequest(reqId) {
			var aa = confirm('<?= lang("Approve_Atp_Request?", "AAR"); ?>');
			if (aa) {
				if (reqId != 0) {
					activeRequest = true;
					start_loader('article');
					$.ajax({
						url: '<?= $POINTER; ?>approve_rpl_request',
						dataType: "JSON",
						method: "POST",
						data: { "request_id": reqId },
						success: function (RES) {
							activeRequest = false;
							end_loader('article');
							thsDt = RES[0];
							if (thsDt['success'] == true) {
								makeSiteAlert('suc', '<?= lang("request_approved_Successfully", "AAR"); ?>');
								$('#appBtn-' + reqId).remove();
								$('#rejBtn-' + reqId).remove();
								$('#stt-' + reqId).html('<?= lang("Approved", "AAR"); ?>');
							} else {
								alert("Failed to load data - 8787");
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

		function rejectRequest(reqId) {
			var aa = confirm('<?= lang("Reject_Atp_Request?", "AAR"); ?>');
			if (aa) {
				if (reqId != 0) {
					activeRequest = true;
					start_loader('article');
					$.ajax({
						url: '<?= $POINTER; ?>reject_rpl_request',
						dataType: "JSON",
						method: "POST",
						data: { "request_id": reqId },
						success: function (RES) {
							activeRequest = false;
							end_loader('article');
							thsDt = RES[0];
							if (thsDt['success'] == true) {
								makeSiteAlert('suc', '<?= lang("request_rejected_Successfully", "AAR"); ?>');
								$('#appBtn-' + reqId).remove();
								$('#rejBtn-' + reqId).remove();
								$('#stt-' + reqId).html('<?= lang("Rejected", "AAR"); ?>');
							} else {
								alert("Failed to load data - 8787");
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


	<!-- MAIN MID VIEW -->
</div>
<!-- MAIN MID VIEW -->



<?php
include("app/footer.php");
?>