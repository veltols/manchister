<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Assets_List");

$pageDataController = $POINTER . "get_assets_list";

$addNewAssetController = $POINTER . 'add_assets_list';
$updateAssetsController = $POINTER . "update_assets_list";

$pageId = 600;
$subPageId = 200;
include("app/assets.php");

$thsStt = isset($_GET['stt']) ? (int) test_inputs($_GET['stt']) : 0;
if ($thsStt == 0) {
	//all
	$subPageId = 200;
	$extra_id = 0;
} else if ($thsStt == 1) {
	//about to expire
	$subPageId = 250;
	$extra_id = 1;
} else if ($thsStt == 2) {
	//expired
	$subPageId = 300;
	$extra_id = 2;
}

?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("assets_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('assets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink hasTooltip" onclick="showModal('newAssetModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("new_asset"); ?></div>
		</a>
	</div>
</div>





<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th tableSpacer">&nbsp;</div>
				<div class="th"><?= lang("asset_ref"); ?></div>
				<div class="th"><?= lang("Asset"); ?></div>
				<div class="th"><?= lang("Serial"); ?></div>
				<div class="th"><?= lang("expiry_date"); ?></div>
				<div class="th"><?= lang("category"); ?></div>
				<div class="th"><?= lang("Assigned_Date"); ?></div>
				<div class="th"><?= lang("Assigned_to"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
				<div class="th tableSpacer">&nbsp;</div>
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
			btns += '<a onclick="reassignAsset(' + data[i]["asset_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-user"></i>' +
				'	<div class="tooltip"><?= lang("Reassign"); ?></div>' +
				'</a>';
			btns = '';
			btns += '<a href="<?= $POINTER; ?>assets/view/?asset_id=' + data[i]["asset_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["asset_id"] + '">' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'	<div class="td">' + data[i]["asset_ref"] + '</div>' +
				'	<div class="td">' + data[i]["asset_name"] + '</div>' +
				'	<div class="td">' + data[i]["asset_serial"] + '</div>' +
				'	<div class="td">' + data[i]["expiry_date"] + '</div>' +
				'	<div class="td">' + data[i]["category_name"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_date"] + '</div>' +
				'	<div class="td">' + data[i]["assigned_to"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'	<div class="td tableSpacer">&nbsp;</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
		}

	}
</script>




<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>




<!-- Modals START -->
<div class="modal" id="newAssetModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Asset", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newAssetForm">


			<input type="hidden" class="inputer asset_sku" data-name="asset_sku" data-den="" data-req="1"
				data-type="hidden" value="132">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("asset_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="asset_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Category", "AAR"); ?></label>
					<select class="inputer" data-name="category_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `category_id`, `category_name` FROM  `z_assets_list_cats` ORDER BY `category_id` ASC";
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
					<label><?= lang("asset_description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="asset_description" data-den="" data-req="1"
						data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->





			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Serial_No", "AAR"); ?></label>
					<input type="text" class="inputer asset_serial" data-name="asset_serial" data-den="" data-req="1"
						data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("purchase_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date purchase_date" data-name="purchase_date" data-den=""
						data-req="0" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("expiry_date", "AAR"); ?></label>
					<input type="text" class="inputer has_future_date expiry_date" data-name="expiry_date" data-den=""
						data-req="0" data-type="date">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("status", "AAR"); ?></label>
					<select class="inputer status_id" data-name="status_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `status_id`, `status_name` FROM  `z_assets_list_status` ORDER BY `status_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$status_id = (int) $SEL_REC['status_id'];
								$status_name = $SEL_REC['status_name'];
								?>
								<option value="<?= $status_id; ?>"><?= $status_name; ?></option>
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
						onclick="submitForm('newAssetForm', '<?= $addNewAssetController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
		window.location.reload();
	}
</script>