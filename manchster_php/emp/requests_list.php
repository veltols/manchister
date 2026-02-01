<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Requests_List");

$pageDataController = $POINTER . "get_requests_list";
$addNewRequestController = $POINTER . "add_requests_list";
$updateRequestController = $POINTER . "update_requests_list";

$pageId = 55006;
$subPageId = 100;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Requests_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('requests_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<!--a class="pageLink" onclick="showModal('newRequestModal');">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("new_request"); ?></div>
		</a-->
	</div>
</div>



<div class="linksIcons">

	<a href="<?= $POINTER; ?>hr_leaves/list/"><i class="fa-solid fa-person-running"></i><?= lang("leaves"); ?></a>

	<a href="<?= $POINTER; ?>hr_permissions/list/"><i class="fa-solid fa-clock"></i><?= lang("Permissions"); ?></a>





	<a href="<?= $POINTER; ?>hr_attendance/list/"><i class="fa-solid fa-calendar"></i><?= lang("Attendance"); ?></a>


	<?php
$qu_hr_documents_types_sel = "SELECT * FROM  `hr_documents_types`";
$qu_hr_documents_types_EXE = mysqli_query($KONN, $qu_hr_documents_types_sel);
if (mysqli_num_rows($qu_hr_documents_types_EXE)) {
	while ($hr_documents_types_REC = mysqli_fetch_assoc($qu_hr_documents_types_EXE)) {
		$idd = (int) $hr_documents_types_REC['document_type_id'];
		$nnn = $hr_documents_types_REC['document_type_name'];
		$iii = $hr_documents_types_REC['document_type_icon'];
		?>
		
		<a href="<?= $POINTER; ?>hr_documents/list/?document_type_id=<?= $idd; ?>"><i class="<?= $iii; ?>"></i><?= $nnn; ?></a>
		<?php
	}
}
?>



</div>




<?php
include("app/footer.php");
?>