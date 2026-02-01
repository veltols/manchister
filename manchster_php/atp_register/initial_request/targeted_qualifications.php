<?php
//initial_form
$pageDataController = $POINTER . "get_qualifications_list";
$addNewQualificationController = $POINTER . 'add_qualifications_list';
$editQualificationController = $POINTER . 'update_atp_qualifications';
?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("targeted_qualifications", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
		<?php
			include_once('main_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="addNewQualification();" style="width: 10% !important;text-align: center;">

			<div class="linkTxt"><?= lang("Add_new"); ?></div>
		</a>
	</div>
</div>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->





			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("qualification_name"); ?></div>
							<div class="th"><?= lang("qualification_type"); ?></div>
							<div class="th"><?= lang("qualification_category"); ?></div>
							<div class="th"><?= lang("emirates_level"); ?></div>
							<div class="th"><?= lang("qulaification_credits"); ?></div>
							<div class="th"><?= lang("mode_of_delivery"); ?></div>
							<div class="th"><i class="fa-solid fa-cog"></i></div>
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
			btns += '<a onclick="deleteQualification(' + data[i]["qualification_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-trash"></i>' +
				'	<div class="tooltip"><?= lang("Delete"); ?></div>' +
				'</a>';
			btns += '<a onclick="editQualification(' + data[i]["qualification_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-edit"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="qualification-' + data[i]["qualification_id"] + '">' +
				'	<div class="td qualification_name">' + data[i]["qualification_name"] + '</div>' +
				'	<div class="td qualification_type">' + data[i]["qualification_type"] + '</div>' +
				'	<div class="td qualification_category">' + data[i]["qualification_category"] + '</div>' +
				'	<div class="td emirates_level">' + data[i]["emirates_level"] + '</div>' +
				'	<div class="td qulaification_credits">' + data[i]["qulaification_credits"] + '</div>' +
				'	<div class="td mode_of_delivery">' + data[i]["mode_of_delivery"] + '</div>' +
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
				'<a onclick="addNewQualification();" class="tableBtn tableBtnInfo">Add New Qualification</a>' +
				'</div><br></div></div>');
		}

	}
    
	function editQualification(qId) {
        var qualification_type = $('#qualification-' + qId + ' .qualification_type').text();
        var qualification_name = $('#qualification-' + qId + ' .qualification_name').text();
        var qualification_category = $('#qualification-' + qId + ' .qualification_category').text();
        var emirates_level = $('#qualification-' + qId + ' .emirates_level').text();
        var qulaification_credits = $('#qualification-' + qId + ' .qulaification_credits').text();
        var mode_of_delivery = $('#qualification-' + qId + ' .mode_of_delivery').text();

        $('#editQualificationForm .edit-qualification_id').val(qId);

        $('#editQualificationForm .edit-qualification_type').val(qualification_type);
        $('#editQualificationForm .edit-qualification_name').val(qualification_name);
        $('#editQualificationForm .edit-qualification_category').val(qualification_category);
        $('#editQualificationForm .edit-emirates_level').val(emirates_level);
        $('#editQualificationForm .edit-qulaification_credits').val(qulaification_credits);
        $('#editQualificationForm .edit-mode_of_delivery').val(mode_of_delivery);

		showModal('editQualificationModal');
	}
    
	function addNewQualification() {
		showModal('newQualificationModal');
	}
</script>


<?php
include("../public/app/footer_records.php");
?>
<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->

<?php
include("app/footer.php");
?>



<!-- Modals START -->
<div class="modal" id="editQualificationModal">
	<div class="modalHeader">
		<h1><?= lang("Edit_Qualification", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editQualificationForm">


        <input type="hidden" class="inputer edit-qualification_id" data-name="qualification_id" data-den="0" value="0" data-req="1"
						data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_name", "AAR"); ?></label>
					<input type="text" class="inputer edit-qualification_name" data-name="qualification_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_type", "AAR"); ?></label>
					<select class="inputer edit-qualification_type" data-name="qualification_type" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="Principal"><?= lang(data: "Principal"); ?></option>
							<option value="Award"><?= lang(data: "Award"); ?></option>
							<option value="CBMC"><?= lang(data: "CBMC"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_category", "AAR"); ?></label>
					<select class="inputer edit-qualification_category" data-name="qualification_category" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="National">National</option>
							<option value="Recognized_Program">Recognized Program</option>
							<option value="International_Qualification">International Qualification</option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_Emirates Level", "AAR"); ?></label>
					<select class="inputer edit-emirates_level" data-name="emirates_level" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<?php
							for ($i = 1; $i <= 8; $i++) {
								?>
								<option value="<?= $i; ?>"><?= $i; ?></option>
							<?php
							}
							?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qulaification_credits", "AAR"); ?></label>
					<input type="text" class="inputer edit-qulaification_credits" data-name="qulaification_credits" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("mode_of_delivery", "AAR"); ?></label>
					<select class="inputer edit-mode_of_delivery" data-name="mode_of_delivery" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="Online">Online</option>
							<option value="Onsite">Onsite</option>
							<option value="Blended">Blended</option>
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
						onclick="submitForm('editQualificationForm', '<?= $editQualificationController; ?>');"><?= lang("Update_Qualification", "AAR"); ?></button>
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
<div class="modal" id="newQualificationModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Qualification", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newQualificationForm">



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="qualification_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_type", "AAR"); ?></label>
					<select class="inputer" data-name="qualification_type" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="Principal"><?= lang(data: "Principal"); ?></option>
							<option value="Award"><?= lang(data: "Award"); ?></option>
							<option value="CBMC"><?= lang(data: "CBMC"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_category", "AAR"); ?></label>
					<select class="inputer" data-name="qualification_category" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="National">National</option>
							<option value="Recognized_Program">Recognized Program</option>
							<option value="International_Qualification">International Qualification</option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_Emirates Level", "AAR"); ?></label>
					<select class="inputer" data-name="emirates_level" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<?php
							for ($i = 1; $i <= 8; $i++) {
								?>
								<option value="<?= $i; ?>"><?= $i; ?></option>
							<?php
							}
							?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qulaification_credits", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="qulaification_credits" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("mode_of_delivery", "AAR"); ?></label>
					<select class="inputer" data-name="mode_of_delivery" data-den="100" data-req="1" data-type="text">
							<option value="100"><?= lang("Please_Select"); ?></option>
							<option value="Online">Online</option>
							<option value="Onsite">Onsite</option>
							<option value="Blended">Blended</option>
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
						onclick="submitForm('newQualificationForm', '<?= $addNewQualificationController; ?>');"><?= lang("Add_Qualification", "AAR"); ?></button>
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
        /*
		setTimeout(function () {
			window.location.reload();
		}, 400);
        */
	}
    

    function deleteQualification(qId) {
						var aa = confirm('<?= lang("Are_you_sure?", "AAR"); ?>');
						if (aa) {
							if (qId != 0) {
								activeRequest = true;
								start_loader('article');
								$.ajax({
									url: '<?= $POINTER; ?>remove_atp_qualification',
									dataType: "JSON",
									method: "POST",
									data: { "qualification_id": qId },
									success: function (RES) {
										activeRequest = false;
										end_loader('article');
										thsDt = RES[0];
										if (thsDt['success'] == true) {
											makeSiteAlert('suc', '<?= lang("Qualification_Removed_Successfully", "AAR"); ?>');
											$('#qualification-' + qId).remove();
										} else {
											alert("Failed to load data - 5454");
										}
									},
									error: function (res) {
										activeRequest = false;
										end_loader('article');
										console.log(res);
										alert("Failed to load data");
									}
								});

							}
						}
					}
</script>

<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>


<script>
    
</script>