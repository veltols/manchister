<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Edit_Self_Studies");
$pageController = $POINTER . 'ext/update_self_study_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;


$addNewPage = $POINTER . 'ext/add_page_self_study';
$updatePage = $POINTER . 'ext/update_page_self_study';
$deletePage = $POINTER . 'ext/delete_page_self_study';


$study_id = 0;
$view = 1;

if (!isset($_GET['study_id'])) {
	header("location:" . $DIR_dashboard);
	die();
}

$study_id = (int) test_inputs($_GET['study_id']);

$view = isset($_GET['view']) ? (int) test_inputs($_GET['view']) : 1;

if ($study_id == 0) {
	header("location:" . $DIR_dashboard);
	die();
}

 
$pageId = 10004;
$subPageId = 200;
include("app/assets.php");




$study_ref = "";
$study_title = "";
$study_overview = "";
$study_status_id = 0;
$added_by = 0;
$added_date = "";
$qu_m_strategic_studies_sel = "SELECT * FROM  `m_strategic_studies` WHERE `study_id` = $study_id";
$qu_m_strategic_studies_EXE = mysqli_query($KONN, $qu_m_strategic_studies_sel);
if(mysqli_num_rows($qu_m_strategic_studies_EXE)){
	$m_strategic_studies_DATA = mysqli_fetch_assoc($qu_m_strategic_studies_EXE);
	$study_id = ( int ) $m_strategic_studies_DATA['study_id'];
	$study_ref = $m_strategic_studies_DATA['study_ref'];
	$study_title = $m_strategic_studies_DATA['study_title'];
	$study_overview = $m_strategic_studies_DATA['study_overview'];
	$study_status_id = ( int ) $m_strategic_studies_DATA['study_status_id'];
	$added_by = ( int ) $m_strategic_studies_DATA['added_by'];
	$added_date = $m_strategic_studies_DATA['added_date'];
}





?>


<div class="pageHeader" style="margin-bottom:0 !important;">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/self_studies/"><i class="fa-solid fa-arrow-left"></i></a>
			<?= $study_ref.' '.$study_title; ?>
		</h1>
	</div>
	<div class="pageNav">

	</div>

	<div class="pageOptions">
		<br>
	</div>
</div>





<div class="verticalTabs">

	<div class="vtMnu">
		<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>&view=1" <?php if( $view == 1 ){ ?> class="activeMnu" <?php } ?> >
			<?= lang('overview', 'ARR'); ?>
		</a>

		<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>&view=2" <?php if( $view == 2 ){ ?> class="activeMnu" <?php } ?> >
			<?= lang('Introductry_pages', 'ARR'); ?>
		</a>

		<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>&view=3" <?php if( $view == 3 ){ ?> class="activeMnu" <?php } ?> >
			<?= lang('Sections', 'ARR'); ?>
		</a>
		
	</div>

	<div class="vtBody">
		<?php
			if( $view == 1 ){
				include("ext/strategies_self_studies/study_view/overview.php");
			} else if( $view == 2 ){
				include("ext/strategies_self_studies/study_view/introductry.php");
			} else if( $view == 3 ){
				include("ext/strategies_self_studies/study_view/sections.php");
			}
		?>
	</div>

</div>






<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
?>





<script>
	function deletePage(pId, pTitle){
		$('.studyIdAtDelPage').val('<?=$study_id; ?>');
		$('.pageIdAtDelPage').val(pId);
		$('.pageTitleAtEditPage').val(pTitle);
		showModal('deletePage');
	}
	function renamePage(pId, pTitle){
		$('#editPageModalTitle').html('<?=lang("Rename_Page", "AAR"); ?>');
		$('.studyIdAtEditPage').val('<?=$study_id; ?>');
		$('.pageIdAtEditPage').val(pId);
		$('.pageTitleAtEditPage').val(pTitle);
		showModal('editPage');
	}
	function addNewIntroductryPage(pType){
		
		var modalTitle = '<?=lang("New_section_page", "AAR"); ?>';
		if( pType == 'introductry' ){
			modalTitle = '<?=lang("New_introductry_page", "AAR"); ?>';
		}
		$('#newPageModalTitle').html(modalTitle);
		$('.pageTypeAtNewPage').val(pType);
		$('.studyIdAtNewPage').val('<?=$study_id; ?>');
		showModal('newPage');
	}







	function afterFormSubmission() {
		setTimeout(function () {
			window.location.reload();
		}, 100);
	}
</script>
<style>
	#mainArticle {
	padding-bottom: 0em !important;
}
</style>



<!-- Modals START -->
<div class="modal" id="newPage">
	<div class="modalHeader">
		<h1 id="newPageModalTitle"><?= lang("Add_new_page", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newPageForm">

				<input type="hidden" class="inputer pageTypeAtNewPage" data-name="page_type" value="introductry" data-den="0" data-req="1" data-type="hidden">
				<input type="hidden" class="inputer studyIdAtNewPage" data-name="study_id" value="<?=$study_id; ?>" data-den="0" data-req="1" data-type="hidden">
		
		
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("page_title", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="page_title" data-den="" data-req="1"
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
						onclick="submitForm('newPageForm', '<?= $addNewPage; ?>');"><?= lang("Add_Page", "AAR"); ?></button>
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

<!-- Modals START -->
<div class="modal" id="editPage">
	<div class="modalHeader">
		<h1 id="editPageModalTitle"><?= lang("Add_new_page", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="editPageForm">

				<input type="hidden" class="inputer pageTypeAtEditPage" data-name="page_type" value="introductry" data-den="0" data-req="1" data-type="hidden">
				<input type="hidden" class="inputer studyIdAtEditPage" data-name="study_id" value="<?=$study_id; ?>" data-den="0" data-req="1" data-type="hidden">
				<input type="hidden" class="inputer pageIdAtEditPage" data-name="page_id" value="0" data-den="0" data-req="1" data-type="hidden">
		
		
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("page_title", "AAR"); ?></label>
					<input type="text" class="inputer pageTitleAtEditPage" data-name="page_title" data-den="" data-req="1"
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
						onclick="submitForm('editPageForm', '<?= $updatePage; ?>');"><?= lang("Save_Page", "AAR"); ?></button>
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


<!-- Modals START -->
<div class="modal" id="deletePage">
	<div class="modalHeader">
		<h1 id="deletePageModalTitle"><?= lang("delete_page", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="deletePageForm">


			<input type="hidden" class="inputer studyIdAtDelPage" data-name="study_id" value="<?=$study_id; ?>" data-den="0" data-req="1" data-type="hidden">
			<input type="hidden" class="inputer pageIdAtDelPage" data-name="page_id" value="0" data-den="0" data-req="1" data-type="hidden">
	
		
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Are you sure?", "AAR"); ?></label>
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
						onclick="submitForm('deletePageForm', '<?= $deletePage; ?>');"><?= lang("Delete_Page", "AAR"); ?></button>
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


