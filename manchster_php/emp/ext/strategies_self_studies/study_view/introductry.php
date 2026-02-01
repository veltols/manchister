<?php
$editedPageId  = isset($_GET['page_id']) ? (int) test_inputs($_GET['page_id']) : 0;
if( $editedPageId == 0 ){
?>
<div class="studyPageView">
	<div class="studyViewTitle">
		<div class="textTitle">
			<h1><?=lang("Introductry_overview", "AAR"); ?></h1>
			<p>
				Use this section to include cover pages or introducty statements, you can upload a PDF file to include within the study introductry
			</p>
		</div>
		<div class="actionTitle">
			<a onclick="addNewIntroductryPage('introductry');" class="ationButtons"><?=lang("create_page", "AAR"); ?></a>
			<!--a onclick="addNewIntroductryPage('introductry');" class="ationButtons"><?=lang("upload_PDF", "AAR"); ?></a-->
		</div>
	</div>
	
	<div class="studyViewContent">

		<?php
	$qu_m_strategic_studies_pages_sel = "SELECT * FROM  `m_strategic_studies_pages` 
										WHERE ((`study_id` = $study_id) AND (`page_type` = 'introductry')) ORDER BY `order_no` ASC";
	$qu_m_strategic_studies_pages_EXE = mysqli_query($KONN, $qu_m_strategic_studies_pages_sel);
	if(mysqli_num_rows($qu_m_strategic_studies_pages_EXE)){
		while($m_strategic_studies_pages_REC = mysqli_fetch_assoc($qu_m_strategic_studies_pages_EXE)){
			$page_id = ( int ) $m_strategic_studies_pages_REC['page_id'];
			$page_title = $m_strategic_studies_pages_REC['page_title'];
			$page_content = $m_strategic_studies_pages_REC['page_content'];
			$page_type = $m_strategic_studies_pages_REC['page_type'];
			$order_no = ( int ) $m_strategic_studies_pages_REC['order_no'];
		?>
		
		<div class="viewBoxWithOpts">
			<h3><?=$page_title; ?></h3>
			<div class="viewBoxOpts">
				<a href="<?= $POINTER; ?>ext/strategies/self_studies_view/?study_id=<?= $study_id; ?>&view=2&page_id=<?=$page_id; ?>" title="Edit Page"><i class="fa-solid fa-edit"></i></a>
				<a onclick="renamePage(<?=$page_id; ?>, '<?=$page_title; ?>');" title="Rename"><i class="fa-solid fa-pencil"></i></a>
				<a  onclick="deletePage(<?=$page_id; ?>, '<?=$page_title; ?>');" title="Delete"><i class="fa-solid fa-trash"></i></a>
			</div>
		</div>
		<?php
		}
	}

		?>
	</div>
</div>





<?php
} else {
	
	
	$page_title = "";
	$page_content = "";
	$page_type = "";
	$order_no = 0;
	$added_by = 0;
	$added_date = "";
	$study_id = 0;
$qu_m_strategic_studies_pages_sel = "SELECT * FROM  `m_strategic_studies_pages` WHERE `page_id` = $editedPageId";
$qu_m_strategic_studies_pages_EXE = mysqli_query($KONN, $qu_m_strategic_studies_pages_sel);
if(mysqli_num_rows($qu_m_strategic_studies_pages_EXE)){
	$m_strategic_studies_pages_DATA = mysqli_fetch_assoc($qu_m_strategic_studies_pages_EXE);
	$page_title = $m_strategic_studies_pages_DATA['page_title'];
	$page_content = $m_strategic_studies_pages_DATA['page_content'];
	$page_type = $m_strategic_studies_pages_DATA['page_type'];
	$order_no = ( int ) $m_strategic_studies_pages_DATA['order_no'];
	$added_by = ( int ) $m_strategic_studies_pages_DATA['added_by'];
	$added_date = $m_strategic_studies_pages_DATA['added_date'];
	$study_id = ( int ) $m_strategic_studies_pages_DATA['study_id'];
}

?>
<div class="studyPageView">
	<div class="studyViewTitle">
		<div class="textTitle">
			<h1><?=$page_title; ?></h1>
			<p>
				Use this section to edit the page content
			</p>
		</div>
		<div class="actionTitle">
			<a onclick="submitForm('updateStudyForm', '<?= $updatePage; ?>');" class="ationButtons"><?=lang("Save", "AAR"); ?></a>
		</div>
	</div>
	
	<div class="studyViewContent">
		





	<div class="row pageForm" id="updateStudyForm">
		
	<input type="hidden" class="inputer" data-name="study_id" value="<?=$study_id; ?>" data-den="0" data-req="1"
					data-type="hidden">
	<input type="hidden" class="inputer" data-name="page_id" value="<?=$editedPageId; ?>" data-den="0" data-req="1"
					data-type="hidden">

		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("Page_Content", "AAR"); ?></label>
				<textarea type="text" class="inputer" data-name="page_content" data-den="" rows="20" data-req="1"
					data-type="text"><?=$page_content; ?></textarea>
			</div>
		</div>
		<!-- ELEMENT END -->
		 
		
	</div>

		 


	</div>
</div>
<?php
}
?>
