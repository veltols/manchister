<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Details");
$pageController = $POINTER . 'update_users_list';
$EmployeePageController = $POINTER . 'update_employees_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;


$updateAssetsController = $POINTER . "update_assets_list";

$employee_id = 0;


if (!isset($_GET['employee_id'])) {
	header("location:" . $DIR_atps);
	die();
}

$employee_id = (int) test_inputs($_GET['employee_id']);

if ($employee_id == 0) {
	header("location:" . $DIR_atps);
	die();
}

$pageId = 700;
$subPageId = 20000;
include("app/assets.php");



$first_name = "";
$last_name = "";
$employee_code = "";
$title_id = 0;
$gender_id = 0;
$nationality_id = 0;
$timezone_id = 0;
$employee_email = "";
$employee_picture = "";
$department_id = 0;
$is_deleted = 0;

$is_group = 100;
$is_committee = 100;

$qu_employees_list_sel = "SELECT * FROM  `employees_list` WHERE `employee_id` = $employee_id";
$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);

if (mysqli_num_rows($qu_employees_list_EXE)) {
	$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
	$employee_id = (int) $employees_list_DATA['employee_id'];
	$first_name = $employees_list_DATA['first_name'];
	$last_name = $employees_list_DATA['last_name'];
	$employee_code = $employees_list_DATA['employee_code'];
	$title_id = (int) $employees_list_DATA['title_id'];
	$gender_id = (int) $employees_list_DATA['gender_id'];
	$nationality_id = (int) $employees_list_DATA['nationality_id'];
	$timezone_id = (int) $employees_list_DATA['timezone_id'];
	$employee_email = $employees_list_DATA['employee_email'];
	$employee_picture = $employees_list_DATA['employee_picture'];
	$department_id = (int) $employees_list_DATA['department_id'];
	$is_deleted = (int) $employees_list_DATA['is_deleted'];

	$is_group = (int) $employees_list_DATA['is_group'];
	$is_committee = (int) $employees_list_DATA['is_committee'];
}


$department_name = "";
$department_code = "";
$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $department_id";
$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
	$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
	$department_id = (int) $employees_list_departments_DATA['department_id'];
	$department_name = $employees_list_departments_DATA['department_name'];
	$department_code = $employees_list_departments_DATA['department_code'];
}







?>

<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('users_list_nav.php');
		?>
		<a href="#" class="pageNavLink activePageNavLink">
			<span><i class="fa-regular fa-eye"></i><?= lang("View", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
	</div>

	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" onclick="showModal('reAssignAsset');">
		<i class="fa-solid fa-briefcase"></i>
			<div class="tooltip"><?= lang("Assigna_Asset"); ?></div>
		</a>

		<a class="pageLink hasTooltip" onclick="showModal('permissionModal');">
			<i class="fa-solid fa-box"></i>
			<div class="tooltip"><?= lang("Permissions"); ?></div>
		</a>

	</div>
</div>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $employee_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Department', 'ARR'); ?></div>
			<div class="number"><?= $department_name; ?></div>
		</div>





	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Employee_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getEmployeeAssets"><?= lang('Assets', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="doNothing"><?= lang('Permissions', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('users_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('users_view/assets.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('users_view/services.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('users_view/logs.php');
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
<div class="modal" id="permissionModal">
	<div class="modalHeader">
		<h1><?= lang("Employee_Permissions", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="employeePermsForm">

			<input type="hidden" class="inputer" data-name="employee_id" data-den="0" data-req="1"
				value="<?= $employee_id; ?>" data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Groups_Permission", "AAR"); ?></label>

					<select class="inputer edit_is_group" data-name="is_group" data-den="100" data-req="1"
						data-type="text">
						<option value="100" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<option value="1"><?= lang(data: "Enabled", trans: "AAR"); ?></option>
						<option value="0"><?= lang(data: "Disabled", trans: "AAR"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("committees_Permission", "AAR"); ?></label>

					<select class="inputer edit_is_committee" data-name="is_committee" data-den="100" data-req="1"
						data-type="text">
						<option value="100" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<option value="1"><?= lang(data: "Enabled", trans: "AAR"); ?></option>
						<option value="0"><?= lang(data: "Disabled", trans: "AAR"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.edit_is_group').val('<?= $is_group; ?>');
				$('.edit_is_committee').val('<?= $is_committee; ?>');
			</script>



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Note: User must logout to reflect the permission change", "AAR"); ?></label>

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
						onclick="submitForm('employeePermsForm', '<?= $EmployeePageController; ?>');"><?= lang("Update_Permissions", "AAR"); ?></button>
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
					<label><?= lang("Asset", "AAR"); ?></label>
					<input type="hidden" class="inputer op" data-name="op" data-den="0" data-req="1" value="1"
						data-type="hidden">

					<input type="hidden" class="inputer" data-name="assigned_to" data-den="0" data-req="1"
						value="<?= $employee_id; ?>" data-type="hidden">

					<select class="inputer" data-name="asset_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_ASEl_sel = "SELECT * FROM  `z_assets_list` WHERE `status_id` = 1";
						$qu_ASEl_EXE = mysqli_query($KONN, $qu_ASEl_sel);
						if (mysqli_num_rows($qu_ASEl_EXE)) {
							while ($ASEl_REC = mysqli_fetch_assoc($qu_ASEl_EXE)) {
								$thsId = (int) $ASEl_REC['asset_id'];
								$thsRef = $ASEl_REC['asset_ref'];
								$thsName = $ASEl_REC['asset_name'];
								?>
								<option value="<?= $thsId; ?>"><?= $thsRef; ?> - <?= $thsName; ?></option>
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
						onclick="submitForm('reassignAssetForm', '<?= $updateAssetsController; ?>');"><?= lang("Assign_Asset", "AAR"); ?></button>
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
					<label><?= lang("This will revoke asset assignation from ", "AAR"); ?><?= $first_name; ?>
						<?= $last_name; ?></label>
					<input type="hidden" class="inputer op" data-name="op" data-den="0" data-req="1" value="3"
						data-type="hidden">
					<input type="hidden" class="inputer asset_id_at_revoke" data-name="asset_id" data-den="0"
						data-req="1" value="0" data-type="hidden">
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


<!-- Modals START -->
<div class="modal" id="resetPassModal">
	<div class="modalHeader">
		<h1><?= lang("Reset_User_Password", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="resetEmpForm">





			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("new_password", "AAR"); ?></label>
					<input type="hidden" class="inputer employee_id_at_reset" data-name="employee_id" data-den="0"
						data-req="1" data-type="hidden" value="<?= $employee_id; ?>">
					<input type="hidden" class="inputer employee_id_at_reset" data-name="is_pass" data-den="0"
						data-req="1" data-type="hidden" value="1">
					<input type="text" class="inputer" data-name="employee_pass" data-den="" data-req="1"
						data-type="text_mixed">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
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
						onclick="submitForm('resetEmpForm', '<?= $pageController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
		makeSiteAlert('suc', "User Updated");
		setTimeout(function () {
			window.location.reload();
		}, 500);
	}
</script>
