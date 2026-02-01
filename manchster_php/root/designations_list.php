<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Designations_List");

$pageDataController = $POINTER . "get_designations_list";
$addNewDesignationController = $POINTER . "add_designations_list";
$updateDesignationController = $POINTER . "update_designations_list";

$pageId = 200;
$subPageId = 300;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Designations_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('departments_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" onclick="showModal('newDesignationModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("Add_New"); ?></div>
		</a>
	</div>
</div>






<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Code"); ?></div>
				<div class="th"><?= lang("Name"); ?></div>
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
			btns += '<a onclick="getData(' + data[i]["designation_id"] + ', ' + data[i]["department_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="designation-' + data[i]["designation_id"] + '">' +
				'	<div class="td designation_code">' + data[i]["designation_code"] + '</div>' +
				'	<div class="td designation_name">' + data[i]["designation_name"] + '</div>' +
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
				'<a onclick="addNewDesignation();" class="tableBtn tableBtnInfo">Add New Designation</a>' +
				'</div><br></div></div>');
		}

	}

	function addNewDesignation() {
		showModal('newDesignationModal');
	}

	function getData(dId, deptId) {
		var designation_code = $('#designation-' + dId + ' .designation_code').text();
		var designation_name = $('#designation-' + dId + ' .designation_name').text();
		$('.edit_designation_id').val(dId);
		$('.edit_designation_code').val(designation_code);
		$('.edit_designation_name').val(designation_name);
		$('.edit_department_id').val(deptId);
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
		<h1><?= lang("Edit_Designation", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editDesignationForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("designation_code", "AAR"); ?></label>
					<input type="hidden" class="inputer edit_designation_id" data-name="designation_id" data-den=""
						data-req="1" data-type="hidden">
					<input type="text" class="inputer edit_designation_code" data-name="designation_code" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("designation_name", "AAR"); ?></label>
					<input type="text" class="inputer edit_designation_name" data-name="designation_name" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<?php
			/*
															<!-- ELEMENT START -->
															<div class="col-1">
																<div class="formElement">
																	<label><?= lang("department", "AAR"); ?></label>
																	<select class="inputer edit_department_id" data-name="department_id" data-den="0" data-req="1"
																		data-type="text">
																		<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
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
															 */
			?>


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
						onclick="submitForm('editDesignationForm', '<?= $updateDesignationController; ?>');"><?= lang("Update_Designation", "AAR"); ?></button>
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
<div class="modal" id="newDesignationModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Designation", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newDesignationForm">




			<input type="hidden" value="100" class="inputer" data-name="designation_code" data-den="" data-req="1"
				data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("designation_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="designation_name" data-den="" data-req="1"
						data-type="text">
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
						onclick="submitForm('newDesignationForm', '<?= $addNewDesignationController; ?>');"><?= lang("Create_Designation", "AAR"); ?></button>
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
