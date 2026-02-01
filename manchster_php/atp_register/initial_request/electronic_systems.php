<?php
//initial_form
$pageDataController = $POINTER . "get_platform_list";
$addNewPlatformController = $POINTER . 'add_platform_list';
$editPlatformController = $POINTER . 'update_atp_platform';
$deletePlatformController = $POINTER . 'remove_atp_platform';
?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("electronic_systems_and_platforms", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
		<?php
			include_once('main_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="addNewPlatform();" style="width: 10% !important;text-align: center;">

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
							<div class="th"><?= lang("platform_name"); ?></div>
							<div class="th"><?= lang("platform_purpose"); ?></div>
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
			btns += '<a onclick="deletePlatform(' + data[i]["platform_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-trash"></i>' +
				'	<div class="tooltip"><?= lang("Delete"); ?></div>' +
				'</a>';
			btns += '<a onclick="editPlatform(' + data[i]["platform_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-edit"></i>' +
				'	<div class="tooltip"><?= lang("Edit"); ?></div>' +
				'</a>';


			//btns= '---';
			var dt = '<div class="tr levelRow" id="platform-' + data[i]["platform_id"] + '">' +
				'	<div class="td">' + data[i]["platform_name"] + '</div>' +
				'	<div class="td">' + data[i]["platform_purpose"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'		<input type="hidden" value="' + data[i]["platform_name"] + '" class="platform_name" >' +
				'		<input type="hidden" value="' + data[i]["platform_purpose"] + '" class="platform_purpose" >' +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="tr"><div class="td"><br><?= lang("No_records_found"); ?><br><div class="tblBtnsGroup">' +
				'<a onclick="addNewPlatform();" class="tableBtn tableBtnInfo">Add New Platform</a>' +
				'</div><br></div></div>');
		}

	}
    
	function editPlatform(qId) {
        var platform_name = $('#platform-' + qId + ' .platform_name').val();
        var platform_purpose = $('#platform-' + qId + ' .platform_purpose').val();

        $('#editPlatformForm .edit-platform_id').val(qId);

        $('#editPlatformForm .edit-platform_name').val(platform_name);
        $('#editPlatformForm .edit-platform_purpose').val(platform_purpose);

		showModal('editPlatformModal');
	}
    
	function addNewPlatform() {
		showModal('newPlatformModal');
	}



	

    function deletePlatform(qId) {
		var aa = confirm('<?= lang("Are_you_sure?", "AAR"); ?>');
		if (aa) {
			if (qId != 0) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $deletePlatformController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "platform_id": qId },
					success: function (RES) {
						activeRequest = false;
						end_loader('article');
						thsDt = RES[0];
						if (thsDt['success'] == true) {
							makeSiteAlert('suc', '<?= lang("Platform_Removed_Successfully", "AAR"); ?>');
							$('#platform-' + qId).remove();
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
<div class="modal" id="editPlatformModal">
	<div class="modalHeader">
		<h1><?= lang("Edit_Platform", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editPlatformForm">


        <input type="hidden" class="inputer edit-platform_id" data-name="platform_id" data-den="0" value="0" data-req="1"
						data-type="hidden">

						
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("platform_name", "AAR"); ?></label>
					<input type="text" class="inputer edit-platform_name" data-name="platform_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("platform_purpose", "AAR"); ?></label>
					<textarea type="text" class="inputer edit-platform_purpose" data-name="platform_purpose" data-den="" data-req="1"
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
						onclick="submitForm('editPlatformForm', '<?= $editPlatformController; ?>');"><?= lang("Update_Platform", "AAR"); ?></button>
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
<div class="modal" id="newPlatformModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Platform", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newPlatformForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("platform_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="platform_name" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("platform_purpose", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="platform_purpose" data-den="" data-req="1"
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
						onclick="submitForm('newPlatformForm', '<?= $addNewPlatformController; ?>');"><?= lang("Add_Platform", "AAR"); ?></button>
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