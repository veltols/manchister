<?php
//initial_form
$pageDataController = $POINTER . "get_faculty_list";
$addNewFacultyController = $POINTER . 'add_faculty_list';
$editFacultyController = $POINTER . 'update_atp_faculty';
$deleteFacultyController = $POINTER . 'remove_atp_faculty';
?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("Faculty_Details", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
		<?php
			include_once('main_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="addNewFaculty();" style="width: 10% !important;text-align: center;">

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
							<div class="th"><?= lang("faculty_name"); ?></div>
							<div class="th"><?= lang("faculty_type"); ?></div>
							<div class="th"><?= lang("years_experience"); ?></div>
							<div class="th"><?= lang("certificate_name"); ?></div>
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
			btns += '<a onclick="deleteFaculty(' + data[i]["faculty_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-trash"></i>' +
				'	<div class="tooltip"><?= lang("Delete"); ?></div>' +
				'</a>';
			btns += '<a onclick="editFaculty(' + data[i]["faculty_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-edit"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="faculty-' + data[i]["faculty_id"] + '">' +
				'	<div class="td">' + data[i]["faculty_name"] + '</div>' +
				'	<div class="td">' + data[i]["faculty_type_name"] + '</div>' +
				'	<div class="td">' + data[i]["years_experience"] + '</div>' +
				'	<div class="td">' + data[i]["certificate_name"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'		<input type="hidden" value="' + data[i]["faculty_name"] + '" class="faculty_name" >' +
				'		<input type="hidden" value="' + data[i]["faculty_type_id"] + '" class="faculty_type_id" >' +
				'		<input type="hidden" value="' + data[i]["educational_qualifications"] + '" class="educational_qualifications" >' +
				'		<input type="hidden" value="' + data[i]["years_experience"] + '" class="years_experience" >' +
				'		<input type="hidden" value="' + data[i]["certificate_name"] + '" class="certificate_name" >' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("No_records_found"); ?><br><div class="tblBtnsGroup">' +
				'<a onclick="addNewFaculty();" class="tableBtn tableBtnInfo">Add New Faculty</a>' +
				'</div><br></div></div>');
		}

	}
    
	function editFaculty(qId) {
        var faculty_name = $('#faculty-' + qId + ' .faculty_name').val();
        var faculty_type_id = $('#faculty-' + qId + ' .faculty_type_id').val();
        var educational_qualifications = $('#faculty-' + qId + ' .educational_qualifications').val();
        var years_experience = $('#faculty-' + qId + ' .years_experience').val();
        var certificate_name = $('#faculty-' + qId + ' .certificate_name').val();

        $('#editFacultyForm .edit-faculty_id').val(qId);

        $('#editFacultyForm .edit-faculty_name').val(faculty_name);
        $('#editFacultyForm .edit-faculty_type_id').val(faculty_type_id);
        $('#editFacultyForm .edit-educational_qualifications').val(educational_qualifications);
        $('#editFacultyForm .edit-years_experience').val(years_experience);
        $('#editFacultyForm .edit-certificate_name').val(certificate_name);

		showModal('editFacultyModal');
	}
    
	function addNewFaculty() {
		showModal('newFacultyModal');
	}



	

    function deleteFaculty(qId) {
		var aa = confirm('<?= lang("Are_you_sure?", "AAR"); ?>');
		if (aa) {
			if (qId != 0) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $deleteFacultyController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "faculty_id": qId },
					success: function (RES) {
						activeRequest = false;
						end_loader('article');
						thsDt = RES[0];
						if (thsDt['success'] == true) {
							makeSiteAlert('suc', '<?= lang("Faculty_Removed_Successfully", "AAR"); ?>');
							$('#faculty-' + qId).remove();
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
include("../public/app/footer_records.php");
?>
<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->

<?php
include("app/footer.php");
?>



<!-- Modals START -->
<div class="modal" id="editFacultyModal">
	<div class="modalHeader">
		<h1><?= lang("Edit_Faculty", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editFacultyForm">


        <input type="hidden" class="inputer edit-faculty_id" data-name="faculty_id" data-den="0" value="0" data-req="1"
						data-type="hidden">

						
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("faculty_name", "AAR"); ?></label>
					<input type="text" class="inputer edit-faculty_name" data-name="faculty_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("faculty_type", "AAR"); ?></label>
					<select class="inputer edit-faculty_type_id" data-name="faculty_type_id" data-den="0" data-req="1" data-type="text">
							<option value="0"><?= lang("Please_Select"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `faculty_type_id`, `faculty_type_name` FROM  `atps_list_faculties_types` ORDER BY `faculty_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$faculty_type_id = (int) $SEL_REC['faculty_type_id'];
								$faculty_type_name = $SEL_REC['faculty_type_name'];
								?>
								<option value="<?= $faculty_type_id; ?>"><?= $faculty_type_name; ?></option>
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
					<label><?= lang("educational_qualifications", "AAR"); ?></label>
					<textarea type="text" class="inputer edit-educational_qualifications" data-name="educational_qualifications" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->
			 

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("years_of_experience", "AAR"); ?></label>
					<input type="text" class="inputer edit-years_experience onlyNumbers" data-name="years_experience" data-den="" data-req="1"
						data-type="int">
				</div>
			</div>
			<!-- ELEMENT END -->
			 
             
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("certificate_name", "AAR"); ?></label>
					<input type="text" class="inputer edit-certificate_name" data-name="certificate_name" data-den="" data-req="1"
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
						onclick="submitForm('editFacultyForm', '<?= $editFacultyController; ?>');"><?= lang("Update_Faculty", "AAR"); ?></button>
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
<div class="modal" id="newFacultyModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Faculty", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newFacultyForm">



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("faculty_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="faculty_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("faculty_type", "AAR"); ?></label>
					<select class="inputer" data-name="faculty_type_id" data-den="0" data-req="1" data-type="text">
							<option value="0"><?= lang("Please_Select"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `faculty_type_id`, `faculty_type_name` FROM  `atps_list_faculties_types` ORDER BY `faculty_type_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$faculty_type_id = (int) $SEL_REC['faculty_type_id'];
								$faculty_type_name = $SEL_REC['faculty_type_name'];
								?>
								<option value="<?= $faculty_type_id; ?>"><?= $faculty_type_name; ?></option>
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
					<label><?= lang("educational_qualifications", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="educational_qualifications" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->
			 

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("years_of_experience", "AAR"); ?></label>
					<input type="text" class="inputer onlyNumbers" data-name="years_experience" data-den="" data-req="1"
						data-type="int">
				</div>
			</div>
			<!-- ELEMENT END -->
			 
             
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("certificate_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="certificate_name" data-den="" data-req="1"
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
						onclick="submitForm('newFacultyForm', '<?= $addNewFacultyController; ?>');"><?= lang("Add_Faculty", "AAR"); ?></button>
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
    
</script>

<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>


<script>
    
	initForms();
	doOnlyNumbers();
</script>