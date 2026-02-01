
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
		<a href="<?= $DIR_tasks; ?>" class="navItem <?php if ($pageId == 1263) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="fa-solid fa-list"></i>
			<span class="navItemText"><?= lang("Tasks", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->
		<?php
		/* 
		<a href="<?= $DIR_dashboard; ?>" class="navItem">
			<i class="fa-solid fa-plus-square"></i>
			<span class="navItemText"><?= lang("Create_Report", "AAR"); ?></span>
		</a>
*/
?>
		<!-- Link Start -->
		<a href="<?= $DIR_tickets; ?>" class="navItem <?php if ($pageId == 55006) { echo 'navItemSelected'; } ?>">
			<i class="fa-solid fa-bookmark"></i>
			<span class="navItemText"><?= lang("Support_Services", "AAR"); ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->
		 
		<a href="<?= $DIR_calendar; ?>" class="navItem <?php if ($pageId == 9898) { echo 'navItemSelected'; } ?>">
			<i class="fa-solid fa-calendar"></i>
			<span class="navItemText"><?= lang("Calendar", "AAR"); ?></span>
		</a>

		<?php
		$qu_feedback_forms_sel = "SELECT * FROM  `feedback_forms` WHERE `employee_id` = $USER_ID";
		$qu_feedback_forms_EXE = mysqli_query($KONN, $qu_feedback_forms_sel);
		if (mysqli_num_rows($qu_feedback_forms_EXE) == 0) {
			?>

			<!-- Link Start -->
			<a href="<?= $POINTER; ?>feedback/" class="navItem">
				<i class="fa-solid fa-file"></i>
				<span class="navItemText"><?= lang("Feedback", "AAR"); ?></span>
			</a>
			<!-- Link End -->
			<!-- ------------------------------------------------------ -->
			<?php
		}
		?>

