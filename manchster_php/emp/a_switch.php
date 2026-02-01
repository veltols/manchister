<?php


if( isset( $_SESSION['sys_mode'] ) ){
	if( $_SESSION['sys_mode'] == 'normal' ){
		$_SESSION['sys_mode'] = 'department';
	} else {
		$_SESSION['sys_mode'] = 'normal';
	}
} else {
	$_SESSION['sys_mode'] = 'normal';
}


header('location:' .$POINTER.'dashboard/');
die();
?>
