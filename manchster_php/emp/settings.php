<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Profile_Settings");

$pageController = $POINTER . "save_settings";

$pageId = 8;
$subPageId = 500;
include("app/assets.php");



?>



<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Theme", "AAR"); ?></h1>
	</div>
	<div class="pageNav">

	
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="submitForm('addNewForm', '<?= $pageController; ?>');">

			<div class="linkTxt"><?= lang("Save"); ?></div>
		</a>
	</div>
</div>





<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="tabContainer">
		<div class="tabBody">
			<div class="tabContent tabBodyActive">



				<div class="row pageForm" id="addNewForm">



<script>
	function selectColor(cId, container) {
		$('#' + container + ' .colorItemSelected').removeClass('colorItemSelected');
		$('#' + container + ' #color-' + cId).addClass('colorItemSelected');
		$('.group_color').val(cId);
	}
</script>
				<!-- ELEMENT START -->
				<div class="col-1">
					<div class="formElement">
						<label><?= lang("theme_Color", "AAR"); ?></label>
						<input class="inputer group_color" value="1" data-name="user_theme_id" data-den="" data-req="1"
							data-type="hidden">
						<div id="newGroupColor" class="colorSelector">
							<?php
							$qu_sys_lists_colors_sel = "SELECT * FROM  `users_list_themes` ORDER BY `user_theme_id` ASC";
							$qu_sys_lists_colors_EXE = mysqli_query($KONN, $qu_sys_lists_colors_sel);
							if (mysqli_num_rows($qu_sys_lists_colors_EXE)) {
								$fs = 0;
								while ($sys_lists_colors_REC = mysqli_fetch_assoc($qu_sys_lists_colors_EXE)) {
									$color_id = (int) $sys_lists_colors_REC['user_theme_id'];
									$color_value = '#'.$sys_lists_colors_REC['color_secondary'];
									$thsClass = '';
									if ($color_id == $USER_THEME_ID) {
										$thsClass = 'colorItemSelected';
										$fs = 100;
									}
									?>
									<div id="color-<?= $color_id; ?>" onclick="selectColor(<?= $color_id; ?>, 'newGroupColor');"
										class="colorItem <?= $thsClass; ?>">
										<div class="colorValue" style="background: <?= $color_value; ?>;"></div>
									</div>
									<?php
								}
							}

							?>



						</div>
					</div>
				</div>
				<!-- ELEMENT END -->
						<input class="inputer user_lang" value="en" data-name="user_lang" data-den="" data-req="1"
							data-type="hidden">
					 
<?php
/*
					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("language", "AAR"); ?></label>
							<select class="inputer user_lang" data-name="user_lang" data-den="0" data-req="1" data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								
								<option value="en">EN</option>
								<option value="ar">العربية</option>
							</select>
						</div>
					</div>
					<!-- ELEMENT END -->

<script>
	$('.user_lang').val('<?=$USER_LANG; ?>');
</script>
*/
?>
					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement"><br><br>
							<hr>
						</div>
					</div>
					<!-- ELEMENT END -->
				</div>



			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->


<?php
include("app/footer.php");
?>



<script>
	function afterFormSubmission() {
		var nwLang = $('.user_lang').val();
		window.location.reload();
		//window.location.href = "<?=$POINTER; ?>settings/?nw_lang=" + nwLang;
	}
</script>