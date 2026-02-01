<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Requests_List");

$pageDataController = $POINTER . "get_requests_list";
$addNewRequestController = $POINTER . "add_requests_list";
$updateRequestController = $POINTER . "update_requests_list";

$pageId = 550;
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

	<a href="<?= $POINTER; ?>hr_leaves/list/"><i class="fa-solid fa-warehouse"></i><?= lang("leaves"); ?></a>

	<a href="<?= $POINTER; ?>hr_permissions/list/"><i
			class="fa-solid fa-mobile-screen-button"></i><?= lang("Permissions"); ?></a>


	<a href="<?= $POINTER; ?>hr_da/list/"><i class="fa-solid fa-shapes"></i><?= lang("Disciplinary_actions"); ?></a>


	<a href="<?= $POINTER; ?>hr_attendance/list/"><i class="fa-solid fa-shapes"></i><?= lang("Attendance"); ?></a>


	<a href="<?= $POINTER; ?>hr_exit_interviews/list/"><i
			class="fa-solid fa-door-open"></i><?= lang("Exit_Interviews"); ?></a>

	<a href="<?= $POINTER; ?>hr_performance/list/"><i class="fa-solid fa-dashboard"></i><?= lang("performance"); ?></a>

	<?php
	/*
			  <a href="<?= $POINTER; ?>settings/phone_keys/"><i
					  class="fa-solid fa-mobile-screen-button"></i><?= lang("leaves_Types"); ?></a>

			  <a href="<?= $POINTER; ?>settings/list/?typo=types"><i
					  class="fa-solid fa-shapes"></i><?= lang("Displenary_actions_Types"); ?></a>

		  */
	?>



</div>




<?php
include("app/footer.php");
?>