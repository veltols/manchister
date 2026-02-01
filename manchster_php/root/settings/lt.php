<?php


$pageDataController = $POINTER . "get_lt_list";
$addNewController = $POINTER . 'add_lt_list';
$updateController = $POINTER . 'update_lt_list';

?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Leaves_Types", "AAR"); ?></h1>
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
				<div class="th"><?= lang("ID"); ?></div>
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
			btns += '<a onclick="getData(' + data[i]["leave_type_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["leave_type_id"] + '">' +
				'	<div class="td">' + data[i]["leave_type_id"] + '</div>' +
				'	<div class="td leave_type_name">' + data[i]["leave_type_name"] + '</div>' +
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
		var leave_type_name = $('#dataRow-' + cId + ' .leave_type_name').text();
		$('.leave_type_id_at_edit').val(cId);
		$('.leave_type_name_at_edit').val(leave_type_name);
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
					<label><?= lang("leave_type_name", "AAR"); ?></label>
					<input type="hidden" class="inputer leave_type_id_at_edit" data-name="leave_type_id" data-den=""
						data-req="1" data-type="hidden">
					<input type="text" class="inputer leave_type_name_at_edit" data-name="leave_type_name" data-den=""
						data-req="1" data-type="text">
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
					<label><?= lang("leave_type_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="leave_type_name" data-den="" data-req="1"
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