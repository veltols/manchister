<?php
$IAM_ARRAY;
$RES = false;
$idder = "";
$token_value = $falseToken;
$IAM_ARRAY['data'] = array();

$primaryKey = "group_id";
$tableName = "z_groups_list";
$currentPage = 1;
$recordsPerPage = 10;
$COND = "";
$op = '';

if ($IS_LOGGED == true && $USER_ID != 0) {

	if (isset($_REQUEST['op']) && isset($_REQUEST['group_id'])) {
		$op = "" . test_inputs($_REQUEST['op']);
		$group_id = (int) test_inputs($_REQUEST['group_id']);
		if ($op == 'details') {
			include_once('g_details.php');
		} else if ($op == 'files') {
			include_once('g_files.php');
		} else if ($op == 'members') {
			include_once('g_members.php');
		} else if ($op == 'remove_members') {
			include_once('g_members_remove.php');
		} else if ($op == 'promote_members') {
			include_once('g_members_promote.php');
		} else if ($op == 'posts') {
			include_once('g_posts.php');
		} else if ($op == 'posts_new') {
			include_once('g_posts_new.php');
		}

	} else {
		$IAM_ARRAY['success'] = false;
		$IAM_ARRAY['message'] = "ERR-2234344";
	}









} else {
	$IAM_ARRAY['success'] = false;
	$IAM_ARRAY['message'] = "ERR-564654";
}





header('Content-Type: application/json');
echo json_encode(array($IAM_ARRAY));


