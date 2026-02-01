<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Communications_List");

$pageDataController = $POINTER . "get_communications_list";
$addNewComRequestController = $POINTER . "add_communications_list";
$updateComRequestController = $POINTER . "update_communications_list";

$pageId = 55006;
$subPageId = 600;
include("app/assets.php");

?>
<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('tickets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink pageLinkWithTxt" onclick="showModal('newComRequestModal');">
			<div class="linkTxt"><?= lang("new_request"); ?></div>
		</a>
	</div>
</div>

<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Type"); ?></div>
				<div class="th"><?= lang("external_party_name"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
				<div class="th"><?= lang("Details"); ?></div>
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
			btns += '<a href="<?= $POINTER; ?>communications/view/?communication_id=' + data[i]["communication_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="service_request-' + data[i]["communication_id"] + '">' +
				'	<div class="td">' + data[i]["communication_code"] + '</div>' +
				'	<div class="td">' + data[i]["communication_type_name"] + '</div>' +
				'	<div class="td">' + data[i]["external_party_name"] + '</div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["status_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["communication_status"] + '</span></div>' +
				'	<div class="td ">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("No_records_found"); ?><br><div class="tblBtnsGroup">' +
				'<a onclick="addNewComRequest();" class="tableBtn tableBtnInfo">Add New Request</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewComRequest() {
		showModal('newComRequestModal');
	}

</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>




<!-- Modals START -->
<div class="modal" id="newComRequestModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Communication_Request", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newComRequestForm">


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("external_party_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="external_party_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Subject", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="communication_subject" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("communication_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="communication_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("information_shared", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="information_shared" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Type", "AAR"); ?></label>
					<select class="inputer" data-name="communication_type_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `communication_type_id`, `communication_type_name` FROM  `m_communications_list_types` ORDER BY `communication_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$idder = (int) $SEL_REC['communication_type_id'];
								$nnn = $SEL_REC['communication_type_name'];
								?>
								<option value="<?= $idder; ?>"><?= $nnn; ?></option>
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
						onclick="submitForm('newComRequestForm', '<?= $addNewComRequestController; ?>');"><?= lang("Send_Request", "AAR"); ?></button>
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
		closeModal();
		goToFirstPage();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
