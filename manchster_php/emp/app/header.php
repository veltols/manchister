<script>
	function start_loader() {
		$('#loader .progresser').css('width', '0%');
		$('#loader').css('display', 'flex');
	}
	function set_loader(d) {
		$('#loader .progresser').css('width', d + '%');
	}
	function end_loader() {
		$('#loader .progresser').css('width', '0%');
		$('#loader').css('display', 'none');
	}
	activeRequest = false;
</script>

<div id="loader"
	style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2); z-index: 999; display: flex; justify-content: center; align-items: center;flex-wrap: wrap;flex-direction: column;">
	<img src="<?= $UPLOADS_DIRECTORY; ?>loader.gif" style="display: block;width: 2%;">
	<div class="progress"
		style="width: 50%;margin: 0 auto;margin-top: 0.5em;display: block;height: 4px;background: #FFF;">
		<div class="progresser" style="height: 4px;background: red;"></div>
	</div>
</div>


<div class="userLiner">


	<img class="patternAtUserLine" src="<?= $UPLOADS_DIRECTORY; ?>patetrn_ul.png" alt="" />

	<div class="userName">
		<img src="<?= $UPLOADS_DIRECTORY; ?>connect_icon.png" alt="" class="iconLogo">
			<span><?= $page_title; ?></span></div>

	<div class="userView">
		<div class="userOptions">
			<a href="<?= $POINTER; ?>notifications/list/">
				<i class="fa-solid fa-bell"></i>
				<div id="notsBadger" class="badger">0</div>
			</a>
			<a href="<?= $DIR_messages; ?>">
				<i class="fa-solid fa-comment"></i>
				<div id="chatsBadger" class="badger">0</div>
			</a>
			<a href="<?= $POINTER; ?>settings/">
				<i class="fa-solid fa-cog"></i>
			</a>
		</div>
		<img class="userImg" title="<?= $EMPLOYEE_NAME; ?>" src="<?= $UPLOADS_DIRECTORY; ?><?= $EMPLOYEE_PICTURE; ?>"
			alt="<?= $EMPLOYEE_NAME; ?>" onclick="toggleUserMenu();">
	</div>
	<div onclick="toggleUserMenu();" id="userSubMnuDarker" class="userSubMnuDarker userSubMnuDarkerHidden"></div>
	<div id="userSubMenu" class="userSubMenu userSubMenuHidden">
		<?php
		$qu_employees_list_staus_sel = "SELECT * FROM  `employees_list_staus` ORDER BY `staus_id` ASC";
		$qu_employees_list_staus_EXE = mysqli_query($KONN, $qu_employees_list_staus_sel);
		if (mysqli_num_rows($qu_employees_list_staus_EXE)) {
			while ($employees_list_staus_REC = mysqli_fetch_assoc($qu_employees_list_staus_EXE)) {
				$idder = (int) $employees_list_staus_REC['staus_id'];
				$namer = $employees_list_staus_REC['staus_name'];
				$ccolor = $employees_list_staus_REC['staus_color'];
				$thsClass = '';
				if ($EMP_STATUS_ID == $idder) {
					$thsClass = 'selectedStt';
				}
				?>
				<a id="usrStt-<?= $idder; ?>" onclick="changeMyStatus(<?= $idder; ?>);"
					class="navItem logoutter <?= $thsClass; ?>">
					<i class="fa-solid fa-circle" style="color: <?= $ccolor; ?>;"></i>
					<span class="navItemText"><?= $namer; ?></span>
				</a>
				<?php
			}
		}
		?>






		<a onclick="toggleUserMenu();showModal('resetPassModal');" class="navItem logoutter">
			<i class="fa-solid fa-lock"></i>
			<span class="navItemText"><?= lang("Change_Password", "AAR"); ?></span>
		</a>

		<a href="<?= $DIR_logout; ?>" class="navItem logoutter">
			<i class="fa-solid fa-sign-out"></i>
			<span class="navItemText"><?= lang("Logout", "AAR"); ?></span>
		</a>
	</div>
</div>
<script>
	function changeMyStatus(nwStt) {
		toggleUserMenu();
		$.ajax({
			type: 'POST',
			url: '<?= $POINTER; ?>update_user_status',
			dataType: "JSON",
			data: { 'new_status': nwStt },
			success: function (responser) {
				if (responser[0].success == true) {
					$('.selectedStt').removeClass('selectedStt');
					$('#usrStt-' + nwStt).addClass('selectedStt');
				} else {
					console.log("General Error, please try later !!");
				}
			},
			error: function () {
				console.log("General Error, please try later !!");
			}
		});
	}
</script>
<script>
	//$('#notsBadger').hide();
	$('#chatsBadger').hide();
</script>
<script>
	function toggleUserMenu() {
		$('#userSubMenu').toggleClass('userSubMenuHidden');
		$('#userSubMnuDarker').toggleClass('userSubMnuDarkerHidden');
	}
</script>

<?php

//get user department
$sthDeptName = 'Apps';
$empDeptId = 0;
$empDeptLineManagerId = 0;
$qu_employees_list_sel = "SELECT `department_id` FROM  `employees_list` WHERE `employee_id` = $USER_ID";
$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
if (mysqli_num_rows($qu_employees_list_EXE)) {
	$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
	$empDeptId = (int) $employees_list_DATA['department_id'];

	$qu_employees_list_departments_sel = "SELECT `department_name`, `line_manager_id` FROM  `employees_list_departments` WHERE `department_id` = $empDeptId";
	$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
	if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
		$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
		$sthDeptName = "" . $employees_list_departments_DATA['department_name'];
		$empDeptLineManagerId = ( int ) $employees_list_departments_DATA['line_manager_id'];
	}
}
?>

<aside id="mainAside">

	<img class="pattern" src="<?= $UPLOADS_DIRECTORY; ?>pattern.png" alt="" />
	<nav class="mainMenu">


		<div class="logoHolder">
			<img src="<?= $UPLOADS_DIRECTORY; ?>logo.png" alt="" class="mainLogoImg">
		</div>
		<?php

		if( $_SESSION['sys_mode'] == 'normal' ){
			include_once('nav_normal.php');
		} else {
			include_once('nav_department.php');
		}
		?>
		
	</nav>

	<nav class="subMenu">
		
<?php

if( $_SESSION['sys_mode'] == 'normal' ){
	?>
		<a id="appsBtn" href="<?=$POINTER; ?>switch_sys_mode/" class="navItem">
			<i class="fa-solid fa-cubes"></i>
			<span class="navItemText"><?= $sthDeptName; ?></span>
		</a>
	<?php
} else {
	?>
		<a id="appsBtn" href="<?=$POINTER; ?>switch_sys_mode/" class="navItem navItemWhite">
			<i class="fa-solid fa-cubes"></i>
			<span class="navItemText"><?= $sthDeptName; ?></span>
		</a>
	<?php
}
?>
		
	</nav>
</aside>

<div id="appsNavDarker" onclick="toggleAppsNav();" class="appsNavDarkerHidden"></div>
<div id="appsNav" class="appsNav appsNavHidden">

	<?php
	if ($IS_GROUP == 1) {
		?>
		<a href="<?= $DIR_groups; ?>" class="appNavItem">
			<i class="fa-solid fa-users"></i>
			<span class="navItemText"><?= lang("Groups", "AAR"); ?></span>
		</a>
		<?php
	}
	?>
	<?php
	if ($IS_COMMITTEE == 1) {
		?>
		<a href="<?= $DIR_groups; ?>?c=1" class="appNavItem">
			<i class="fa-solid fa-users"></i>
			<span class="navItemText"><?= lang("Committees", "AAR"); ?></span>
		</a>
		<?php
	}
	?>


	<!--a href="<?= $POINTER; ?>strategies/list/" class="appNavItem">
		<i class="fa-solid fa-folder-open"></i>
		<span class="navItemText"><?= lang("Strategy", "AAR"); ?></span>
	</a-->

	<a href="<?= $POINTER; ?>ext/atps/list/" class="appNavItem">
		<i class="fa-solid fa-folder"></i>
		<span class="navItemText"><?= lang("ATPs", "AAR"); ?></span>
	</a>


</div>

<script>

	function toggleAppsNav() {
		$('#appsNav').toggleClass('appsNavHidden');
		$('#appsNavDarker').toggleClass('appsNavDarkerHidden');
		$('#appsBtn').toggleClass('navItemSelected');
	}
</script>


<!-- ARTICLE START -->
<article id="mainArticle">