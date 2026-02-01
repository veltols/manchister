<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Organization_Chart");

$pageDataController = $POINTER . "get_departments_list";
$addNewDepartmentController = $POINTER . "add_departments_list";
$updateDepartmentController = $POINTER . "update_departments_list";

$pageId = 200;
$subPageId = 400;
include("app/assets.php");




?>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/loader.js"></script>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/html2canvas.min.js"></script>

<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Departments_List", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('departments_list_nav.php');
		?>
	</div>

	<div class="pageOptions">

	</div>
</div>





<div class="binary-tree">

	<div id="chart_div"></div>






</div>

<?php
include("app/footer.php");
?>