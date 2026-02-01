<?php
//initial_form
$pageDataController = $POINTER . "get_qualifications_list";
$addNewQualificationController = $POINTER . 'add_qualifications_list';
$editQualificationController = $POINTER . 'update_atp_qualifications';
$editQualificationFacController = $POINTER . 'map_atp_qualifications';
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
		<!--a class="pageLink" onclick="addNewQualification();" style="width: 10% !important;text-align: center;">

			<div class="linkTxt"><?= lang("Add_new"); ?></div>
		</a-->
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
							<div class="th"><?= lang("Mapped_Faculties"); ?></div>
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
			btns += '<a onclick="editQualificationFaculty(' + data[i]["qualification_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-user-plus"></i>' +
				'	<div class="tooltip"><?= lang("Map_Faculties"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="qualification-' + data[i]["qualification_id"] + '">' +
				'	<div class="td qualification_name">' + data[i]["qualification_name"] + '</div>' +
				'	<div class="td qualification_type">' + data[i]["qualification_type"] + '</div>' +
				'	<div class="td mapped_faculty">' + data[i]["mapped_faculty"] + '</div>' +
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
    
	function editQualificationFaculty(qId) {
        var qualification_name = $('#qualification-' + qId + ' .qualification_name').text();
        $('#editQualificationFacForm .qf-qualification_id').val(qId);
		
        $('#editQualificationFacForm .qf-qualification_name').val(qualification_name);

		showModal('editQualificationFacModal');
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
<div class="modal" id="editQualificationFacModal">
	<div class="modalHeader">
		<h1><?= lang("Map_Faculty_to_Qualification", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editQualificationFacForm">


        <input type="hidden" class="inputer qf-qualification_id" data-name="qualification_id" data-den="0" value="0" data-req="1"
						data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("qualification_name", "AAR"); ?></label>
					<input type="text" class="inputer qf-qualification_name" data-name="qualification_name" data-den="" data-req="1"
						data-type="text" disabled>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Faculty", "AAR"); ?></label>
					<select class="inputer qf-faculty_id" data-name="faculty_id" data-den="0" data-req="1" data-type="text">
					<option value="0"><?= lang("Please_Select"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `faculty_id`, `faculty_name` FROM  `atps_list_faculties` ORDER BY `faculty_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$faculty_id = (int) $SEL_REC['faculty_id'];
								$faculty_name = $SEL_REC['faculty_name'];
								?>
								<option value="<?= $faculty_id; ?>"><?= $faculty_name; ?></option>
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
						onclick="submitForm('editQualificationFacForm', '<?= $editQualificationFacController; ?>');"><?= lang("Map_Qualification", "AAR"); ?></button>
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
    
</script>