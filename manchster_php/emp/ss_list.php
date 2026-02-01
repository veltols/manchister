<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Support_Services_List");

$pageDataController = $POINTER . "get_ss_list";
$addNewServiceRequestController = $POINTER . "add_ss_list";
$updateServiceRequestController = $POINTER . "update_ss_list";

$pageId = 55006;
$subPageId = 550;
include("app/assets.php");




?>



<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('tickets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink pageLinkWithTxt" onclick="showModal('newServiceRequestModal');">
			<div class="linkTxt"><?= lang("new_request"); ?></div>
		</a>
	</div>
</div>

<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Description"); ?></div>
				<div class="th"><?= lang("last_updated"); ?></div>
				<div class="th"><?= lang("updated_by"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
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
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>ss/view/?ss_id=' + data[i]["ss_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="service_request-' + data[i]["ss_id"] + '">' +
				'	<div class="td">' + data[i]["ss_ref"] + '</div>' +
				'	<div class="td">' + data[i]["ss_description"] + '</div>' +
				'	<div class="td">' + data[i]["last_updated"] + '</div>' +
				'	<div class="td">' + data[i]["updated_by"] + '</div>' +
				'	<div class="td"> <span style="min-width:1em;display: block;background:#' + data[i]["status_color"] + ';color:#FFF;padding: 0.5em;font-weight: bold;text-transform: uppercase;">' + data[i]["ss_status"] + '</span></div>' +
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
				'<a onclick="addNewServiceRequest();" class="tableBtn tableBtnInfo">Add New ServiceRequest</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewServiceRequest() {
		showModal('newServiceRequestModal');
	}

</script>



<?php
include("../public/app/footer_records.php");
?>



<?php
include("app/footer.php");
?>




<!-- Modals START -->
<div class="modal" id="newServiceRequestModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Service_Request", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newServiceRequestForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Request_Type", "AAR"); ?></label>
					<select class="inputer" data-name="category_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `category_id`, `category_name` FROM  `ss_list_cats` ORDER BY `category_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$category_id = (int) $SEL_REC['category_id'];
								$category_name = $SEL_REC['category_name'];
								?>
								<option value="<?= $category_id; ?>"><?= $category_name; ?></option>
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
				<div class="formElement">
					<label><?= lang("service_request_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="ss_description" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Attachment", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="ss_attachment" data-den="" data-req="1"
						data-type="file">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Send_To", "AAR"); ?></label>
					<select class="inputer new-sent_to_id" data-name="sent_to_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php

						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE  ( (`employee_id` <> $USER_ID) AND (`is_deleted` = 0) AND (`is_hidden` = 0)) ORDER BY `employee_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$emp_id = (int) $SEL_REC['employee_id'];
								$namer = $SEL_REC['namer'];
								?>
								<option value="<?= $emp_id; ?>"><?= $namer; ?></option>
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
						onclick="submitForm('newServiceRequestForm', '<?= $addNewServiceRequestController; ?>');"><?= lang("Send_Request", "AAR"); ?></button>
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
