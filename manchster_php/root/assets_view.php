<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Asset_Details");
$pageController = $POINTER . 'update_QS';
$submitBtn = lang("Save_asset", "AAR");
$isNewForm = true;

$updateAssetsController = $POINTER . "update_assets_list";

$asset_id = 0;


if (!isset($_GET['asset_id'])) {
	header("location:" . $DIR_assets);
	die();
}

$asset_id = (int) test_inputs($_GET['asset_id']);

if ($asset_id == 0) {
	header("location:" . $DIR_assets);
	die();
}

$pageId = 600;
$subPageId = 200;
include("app/assets.php");



$adder_name = '';
$asset_status = '';




$qu_QS_sel = "SELECT `z_assets_list`.*, 
									CONCAT(`a`.`first_name`, ' ', `a`.`last_name`) AS `added_by`,
									CONCAT(`b`.`first_name`, ' ', `b`.`last_name`) AS `assigned_by`,

									`z_assets_list_status`.`status_name` AS `asset_status`, 
									`z_assets_list_cats`.`category_name` AS `category_name`

							FROM  `z_assets_list` 
							INNER JOIN `employees_list` `a` ON `z_assets_list`.`added_by` = `a`.`employee_id` 
							INNER JOIN `employees_list` `b` ON `z_assets_list`.`assigned_by` = `b`.`employee_id` 
							INNER JOIN `z_assets_list_cats` ON `z_assets_list`.`category_id` = `z_assets_list_cats`.`category_id` 
							INNER JOIN `z_assets_list_status` ON `z_assets_list`.`status_id` = `z_assets_list_status`.`status_id` 
							WHERE ( ( `z_assets_list`.`asset_id` = $asset_id ) ) LIMIT 1";




$qu_QS_EXE = mysqli_query($KONN, $qu_QS_sel);
$QS_DATA;
$status_id = 0;
$asset_ref = '';
if (mysqli_num_rows($qu_QS_EXE)) {
	$QS_DATA = mysqli_fetch_assoc($qu_QS_EXE);
	$adder_name = "" . $QS_DATA['added_by'];
	$asset_status = "" . $QS_DATA['asset_status'];
	$asset_ref = "" . $QS_DATA['asset_ref'];
	$status_id = (int) $QS_DATA['status_id'];
}



$assigned_to = decryptData((int) $QS_DATA['assigned_to']);
$assigned_to_name = 'Not Assigned';
if ($assigned_to != 0) {
	$qu_employees_list_sel = "SELECT CONCAT( `first_name`, ' ', `last_name` ) FROM  `employees_list` WHERE `employee_id` = $assigned_to";
	$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
	if (mysqli_num_rows($qu_employees_list_EXE)) {
		$employees_list_DATA = mysqli_fetch_array($qu_employees_list_EXE);
		$assigned_to_name = "" . $employees_list_DATA[0];
	}

}




?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= $asset_ref; ?> <?= lang("_Details", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		$subPageId = 1500;
		include('assets_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" onclick="reassignAsset();">
			<i class="fa-solid fa-user"></i>
			<div class="tooltip"><?= lang("Assignation"); ?></div>
		</a>
		
		<a class="pageLink hasTooltip" onclick="sttAsset();">
			<i class="fa-solid fa-file"></i>
			<div class="tooltip"><?= lang("Change_Status"); ?></div>
		</a>

	</div>
</div>
<script>

	function reassignAsset() {
		showModal('reAssignAsset');
	}

	function sttAsset() {
		showModal('assetStatusModal');
	}
</script>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $asset_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('asset_ref', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['asset_ref']; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('category_name', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['category_name']; ?></div>
			<div class="icon"><i class="fa-solid fa-list-ol"></i>
			</div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Status', 'ARR'); ?></div>
			<div class="number"><?= $QS_DATA['asset_status']; ?></div>
			<div class="icon"><i class="fa-solid fa-file"></i>
			</div>
		</div>




	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Asset_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getAssigns"><?= lang('Assignation', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('assets_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('assets_view/assign.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('assets_view/logs.php');
				?>
			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>



<!-- Modals START -->
<div class="modal" id="reAssignAsset">
	<div class="modalHeader">
		<h1><?= lang("Reassign_Asset", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="reassignAssetForm">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("ReAssign_to", "AAR"); ?></label>
					<input type="hidden" class="inputer op" data-name="op" data-den="0" data-req="1" value="1"
						data-type="hidden">
					<input type="hidden" class="inputer asset_id_reassign" data-name="asset_id" data-den="0"
						data-req="1" value="<?= $asset_id; ?>" data-type="hidden">
					<select class="inputer" data-name="assigned_to" data-den="" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Not_Assigned", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE  ( (`is_deleted` = 0) AND (`is_hidden` = 0)) ORDER BY `employee_id` ASC";
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
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer assign_remark" data-name="assign_remark" data-den=""
						data-req="1" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('reassignAssetForm', '<?= $updateAssetsController; ?>');"><?= lang("Reassign", "AAR"); ?></button>
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
<div class="modal" id="assetStatusModal">
	<div class="modalHeader">
		<h1><?= lang("Change_Asset_Status", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="sttAssetForm">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("New_Status", "AAR"); ?></label>
					<input type="hidden" class="inputer op" data-name="op" data-den="0" data-req="1" value="2"
						data-type="hidden">
					<input type="hidden" class="inputer" data-name="asset_id" data-den="0" data-req="1"
						value="<?= $asset_id; ?>" data-type="hidden">
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
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer assign_remark" data-name="assign_remark" data-den=""
						data-req="1" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('sttAssetForm', '<?= $updateAssetsController; ?>');"><?= lang("Update_Asset", "AAR"); ?></button>
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
		window.location.reload();
	}
</script>
