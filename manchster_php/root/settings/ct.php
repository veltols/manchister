<?php


$pageDataController = $POINTER . "get_ct_list";
$addNewController = $POINTER . 'add_ct_list';
$updateController = $POINTER . 'update_ct_list';

?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Communications_Categories", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('settings_nav.php');
		?>
	</div>
	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" onclick="showModal('AddNewModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("Add_New"); ?></div>
		</a>
	</div>
</div>




<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Name"); ?></div>
				<div class="th"><?= lang("first_Approval"); ?></div>
				<div class="th"><?= lang("Second_Approval"); ?></div>
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
			btns += '<a onclick="getData(' + data[i]["communication_type_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["communication_type_id"] + '">' +
				'	<div class="td communication_type_name">' + data[i]["communication_type_name"] + '</div>' +
				'	<div class="td">' + data[i]["approval_1_name"] + '</div>' +
				'	<div class="td">' + data[i]["approval_2_name"] + '</div>' +
				'<input type="hidden" class="approval_id_1" value="' + data[i]["approval_id_1"] + '" />' +
				'<input type="hidden" class="approval_id_2" value="' + data[i]["approval_id_2"] + '" />' +
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
		var communication_type_name = $('#dataRow-' + cId + ' .communication_type_name').text();
		var approval_id_1 = getInt($('#dataRow-' + cId + ' .approval_id_1').val());
		var approval_id_2 = getInt($('#dataRow-' + cId + ' .approval_id_2').val());
		$('.communication_type_id_at_edit').val(cId);
		$('.communication_type_name_at_edit').val(communication_type_name);
		$('.approval_id_1_at_edit').val(approval_id_1);
		$('.approval_id_2_at_edit').val(approval_id_2);
		showModal('viewModal');
	}
</script>




<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>



<!-- Modals START -->
<div class="modal" id="viewModal">
	<div class="modalHeader">
		<h1><?= lang("Update_Data", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editForm">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("communication_type_name", "AAR"); ?></label>
					<input type="hidden" class="inputer communication_type_id_at_edit" data-name="communication_type_id"
						data-den="" data-req="1" data-type="hidden">
					<input type="text" class="inputer communication_type_name_at_edit"
						data-name="communication_type_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("approval_1", "AAR"); ?></label>
					<select class="inputer approval_id_1_at_edit" data-name="approval_id_1" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
					<label><?= lang("approval_2", "AAR"); ?></label>
					<select class="inputer approval_id_2_at_edit" data-name="approval_id_2" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
						onclick="submitForm('editForm', '<?= $updateController; ?>');"><?= lang("Update", "AAR"); ?></button>
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
<div class="modal" id="AddNewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="AddNewForm">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("communication_type_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="communication_type_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("approval_1", "AAR"); ?></label>
					<select class="inputer" data-name="approval_id_1" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
					<label><?= lang("approval_2", "AAR"); ?></label>
					<select class="inputer" data-name="approval_id_2" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
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
						onclick="submitForm('AddNewForm', '<?= $addNewController; ?>');"><?= lang("Save", "AAR"); ?></button>
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