<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Policies_and_procedures");

//hr_documents
$pageDataController = $POINTER . "get_hr_documents_list";
$addNewController = $POINTER . 'add_hr_documents_list';
$updateController = $POINTER . 'update_hr_documents_list';


$document_type_id = isset($_GET['document_type_id']) ? (int) test_inputs($_GET['document_type_id']) : 1;

$extra_id = $document_type_id;



$page_title = 'HR Documents';

$pageId = 55006;
$subPageId = $document_type_id;
include("app/assets.php");
?>




<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= $page_title; ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('requests_list_nav.php');
		?>
	</div>

	<div class="pageOptions">

	</div>
</div>




<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("System_ID"); ?></div>
				<div class="th"><?= lang("document_title"); ?></div>
				<div class="th"><?= lang("document_description"); ?></div>
				<div class="th"><?= lang("document_attachment"); ?></div>
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
			var btns = '<a href="<?= $POINTER; ?>../uploads/' + data[i]["document_attachment"] + '" target="_blank" class="tableBtn">View</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["document_id"] + '">' +
				'	<div class="td">' + data[i]["document_id"] + '</div>' +
				'	<div class="td">' + data[i]["document_title"] + '</div>' +
				'	<div class="td">' + data[i]["document_description"] + '</div>' +
				'	<div class="td">' +
				btns +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
		}

	}
	function getData(cId) {
		var category_name = $('#dataRow-' + cId + ' .category_name').text();
		var category_alert_days = $('#dataRow-' + cId + ' .category_alert_days').text();
		$('.document_id_at_edit').val(cId);
		$('.category_name_at_edit').val(category_name);
		$('.category_alert_days_at_edit').val(category_alert_days);
		showModal('viewModal');
	}
</script>





<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>








<script>
	function afterFormSubmission() {
		closeModal();
		goToFirstPage();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
