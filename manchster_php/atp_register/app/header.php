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
			<?php
			/*
			<a href="<?= $POINTER; ?>notifications/list/">
				<i class="fa-solid fa-bell"></i>
				<div id="notsBadger" class="badger">0</div>
			</a>
			<a href="<?= $POINTER; ?>messages/list/">
				<i class="fa-solid fa-comment"></i>
				<div id="chatsBadger" class="badger">0</div>
			</a>
			<a href="<?= $POINTER; ?>settings/">
				<i class="fa-solid fa-cog"></i>
			</a>
			*/
			?>
		</div>
		<img class="userImg" title="<?= $ATP_NAME; ?>" src="<?= $UPLOADS_DIRECTORY; ?><?= $ATP_PICTURE; ?>"
			alt="<?= $ATP_NAME; ?>" onclick="toggleUserMenu();">
	</div>
	<?php
	/*
	<div onclick="toggleUserMenu();" id="userSubMnuDarker" class="userSubMnuDarker userSubMnuDarkerHidden"></div>
	<div id="userSubMenu" class="userSubMenu userSubMenuHidden">
		<a onclick="toggleUserMenu();showModal('resetPassModal');" class="navItem logoutter">
			<i class="fa-solid fa-lock"></i>
			<span class="navItemText"><?= lang("Change_Password", "AAR"); ?></span>
		</a>

		<a href="<?= $DIR_logout; ?>" class="navItem logoutter">
			<i class="fa-solid fa-sign-out"></i>
			<span class="navItemText"><?= lang("Logout", "AAR"); ?></span>
		</a>
	</div>
	*/
	?>
</div>

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

<aside id="mainAside">

	<img class="pattern" src="<?= $UPLOADS_DIRECTORY; ?>pattern.png" alt="" />
	<nav class="mainMenu">


		<div class="logoHolder">
			<img src="<?= $UPLOADS_DIRECTORY; ?>logo.png" alt="" class="mainLogoImg">
		</div>
		
		
		
		<!-- ------------------------------------------------------ -->
		<!-- Link Start -->
		<a href="<?= $DIR_dashboard; ?>" class="navItem <?php if ($pageId == 100) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-gauge"></i>
			<span class="navItemText"><?= lang("Dashboard", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->
		
		
		<!-- ------------------------------------------------------ -->
		<!-- Link Start -->
		<a href="<?= $POINTER; ?>accreditation/new/" class="navItem <?php if ($pageId == 200) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-briefcase"></i>
			<span class="navItemText"><?= lang("accreditation", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->
		
	</nav>

	<nav class="subMenu">
		
		<a id="appsBtn" href="<?= $DIR_logout; ?>" class="navItem">
			<i class="fa-solid fa-sign-out"></i>
			<span class="navItemText"><?= lang("Logout", "AAR"); ?></span>
		</a>
		
	</nav>
</aside>





<!-- ARTICLE START -->
<article id="mainArticle">