<?php
$page_title = $page_description = $page_keywords = $page_author = lang("System_Lists");

$pageDataController = $POINTER . "get_users_list";
$addNewUserController = $POINTER . 'add_users_list';


$pageId = 800;
$subPageId = 100;


$subPageId = isset($_GET['sub']) ? (int) test_inputs($_GET['sub']) : 300;
$stt = isset($_GET['stt']) ? "" . test_inputs($_GET['stt']) : 'tc';

if ($stt == 'tc') {
	$subPageId = 100;
} else if ($stt == 'ac') {
	$subPageId = 200;
} else if ($stt == 'lt') {
	$subPageId = 300;
}
include("app/assets.php");


include("settings/" . $stt . ".php");

?>

<script>
	function afterFormSubmission() {
		closeModal();
		goToFirstPage();
		window.location.reload();
	}
</script>
