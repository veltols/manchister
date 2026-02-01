<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Departments_List");

$pageDataController = $POINTER . "get_departments_list";
$addNewDepartmentController = $POINTER . "add_departments_list";
$updateDepartmentController = $POINTER . "update_departments_list";

$pageId = 200;
$subPageId = 200;
include("app/assets.php");




?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Departments_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('departments_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="showModal('newDepartmentModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("Add_New"); ?></div>
		</a>
	</div>
</div>






<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Code"); ?></div>
				<div class="th"><?= lang("Name"); ?></div>
				<div class="th"><?= lang("Main_Department"); ?></div>
				<div class="th"><?= lang("Line_manager"); ?></div>
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
			btns += '<a onclick="getData(' + data[i]["department_id"] + ', ' + data[i]["main_department_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="department-' + data[i]["department_id"] + '">' +
				'	<div class="td department_code">' + data[i]["department_code"] + '</div>' +
				'	<div class="td department_name">' + data[i]["department_name"] + '</div>' +
				'	<div class="td main_department_name">' + data[i]["main_department_name"] + '</div>' +
				'	<div class="td main_department_name">' + data[i]["line_manager"] + '</div>' +
				'	<div class="td ">' +
				'		<input type="hidden" class="user_type" value="' + data[i]["user_type"] + '" >' +
				'		<input type="hidden" class="line_manager_id" value="' + data[i]["line_manager_id"] + '" >' +
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
				'<a onclick="addNewDepartment();" class="tableBtn tableBtnInfo">Add New Department</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewDepartment() {
		showModal('newDepartmentModal');
	}

	function getData(dId, mdId) {
		var department_code = $('#department-' + dId + ' .department_code').text();
		var department_name = $('#department-' + dId + ' .department_name').text();
		var user_type = $('#department-' + dId + ' .user_type').val();
		var lmManager = $('#department-' + dId + ' .line_manager_id').val();
		$('.edit_department_id').val(dId);
		$('.edit_main_department_id').val(mdId);
		$('.edit_department_code').val(department_code);
		$('.edit_department_name').val(department_name);
		$('.edit_user_type').val(user_type);
		$('.edit_line_manager_id').val(lmManager);
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
		<h1><?= lang("Edit_Department", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editDepartmentForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department_code", "AAR"); ?></label>
					<input type="hidden" class="inputer edit_department_id" data-name="department_id" data-den=""
						data-req="1" data-type="hidden">
					<input type="text" class="inputer edit_department_code" data-name="department_code" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department_name", "AAR"); ?></label>
					<input type="text" class="inputer edit_department_name" data-name="department_name" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->





			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Main_department", "AAR"); ?></label>
					<select class="inputer edit_main_department_id" data-name="main_department_id" data-den=""
						data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Not_Applicable", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments` ORDER BY `department_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$department_id = (int) $SEL_REC['department_id'];
								$department_name = $SEL_REC['department_name'];
								?>
								<option value="<?= $department_id; ?>"><?= $department_name; ?></option>

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
					<label><?= lang("Department_Line_Manager", "AAR"); ?></label>
					<select class="inputer edit_line_manager_id" data-name="line_manager_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
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
						onclick="submitForm('editDepartmentForm', '<?= $updateDepartmentController; ?>');"><?= lang("Update_Department", "AAR"); ?></button>
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
<div class="modal" id="newDepartmentModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Department", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newDepartmentForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department_code", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="department_code" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="department_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Main_department", "AAR"); ?></label>
					<select class="inputer" data-name="main_department_id" data-den="" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Not_Applicable", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments` ORDER BY `department_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$department_id = (int) $SEL_REC['department_id'];
								$department_name = $SEL_REC['department_name'];
								?>
								<option value="<?= $department_id; ?>"><?= $department_name; ?></option>
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
					<label><?= lang("Department_Line_Manager", "AAR"); ?></label>
					<select class="inputer new-line_manager_id" data-name="line_manager_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
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
					<label><?= lang("user_type - only for IT to fill", "AAR"); ?></label>
					<input type="text" class="inputer" value="emp" data-name="user_type" data-den="" data-req="1"
						disabled data-type="text">
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
						onclick="submitForm('newDepartmentForm', '<?= $addNewDepartmentController; ?>');"><?= lang("Create_Department", "AAR"); ?></button>
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
		window.location.reload();
	}
</script>
