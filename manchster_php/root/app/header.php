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


	<div class="userName"><img src="<?= $UPLOADS_DIRECTORY; ?>connect_icon.png" alt=""
			class="iconLogo"><span><?= $EMPLOYEE_NAME; ?></span></div>

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
			<i class="fa-solid fa-sign-out"></i>
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
	$('#notsBadger').hide();
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





		<!-- Link Start -->
		<a href="<?= $POINTER; ?>departments/list/" class="navItem <?php if ($pageId == 200) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-network-wired"></i>
			<span class="navItemText"><?= lang("Organization_Chart", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<!--a href="<?= $DIR_calendar; ?>" class="navItem <?php if ($pageId == 300) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-calendar"></i>
			<span class="navItemText"><?= lang("Calendar", "AAR"); ?></span>
		</a-->
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<!--a href="<?= $DIR_messages; ?>" class="navItem <?php if ($pageId == 400) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-envelope"></i>
			<span class="navItemText"><?= lang("Chats", "AAR"); ?></span>
		</a-->
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<!--a href="<?= $DIR_tasks; ?>" class="navItem <?php if ($pageId == 500) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-list"></i>
			<span class="navItemText"><?= lang("Tasks", "AAR"); ?></span>
		</a-->
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<a href="<?= $DIR_tickets; ?>" class="navItem <?php if ($pageId == 550) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-bookmark"></i>
			<span class="navItemText"><?= lang("Tickets", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<a href="<?= $DIR_assets; ?>" class="navItem <?php if ($pageId == 600) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-briefcase"></i>
			<span class="navItemText"><?= lang("Assets", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<a href="<?= $DIR_users; ?>" class="navItem <?php if ($pageId == 700) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-user"></i>
			<span class="navItemText"><?= lang("Users", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->


		<!-- Link Start -->
		<a href="<?= $DIR_settings; ?>" class="navItem <?php if ($pageId == 800) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-cogs"></i>
			<span class="navItemText"><?= lang("Settings", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->



		<!-- Link Start -->
		<a href="<?= $DIR_users; ?>?feedback=1" class="navItem <?php if ($pageId == 950) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-file"></i>
			<span class="navItemText"><?= lang("Feedback", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->





	</nav>
	<nav class="subMenu">


		<!--a href="<?= $DIR_settings; ?>" class="navItem <?php if ($pageId == 700) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-sitemap"></i>
			<span class="navItemText"><?= lang("Organization", "AAR"); ?></span>
		</a-->


		<!--a href="<?= $DIR_logout; ?>" class="navItem">
			<i class="fa-solid fa-sign-out"></i>
			<span class="navItemText"><?= lang("Logout", "AAR"); ?></span>
		</a-->
	</nav>
</aside>



<!-- ARTICLE START -->
<article id="mainArticle">