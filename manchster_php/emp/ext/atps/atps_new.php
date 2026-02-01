<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Add_New");
$pageController = $POINTER . 'ext/add_atps_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;



$addNewAtpController = $POINTER . "ext/add_atps_list";


$pageId = 10001;
$subPageId = 2000;
if( !checkUserService($pageId) ){
	die("You don't have permission to view this page!");
}
include("app/assets.php");

?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= lang("Add_New_Training_Provider", "AAR"); ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('atps_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="submitForm('addNewForm', '<?= $pageController; ?>');">

			<div class="linkTxt"><?= lang("Add"); ?></div>
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



					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("Institution_name", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="atp_name" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("contact_person", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="contact_name" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("contact_person_designation", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="contact_name_designation" data-den=""
								data-req="1" data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("email", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="atp_email" data-den="" data-req="1"
								data-type="email">
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("phone", "AAR"); ?></label>
							<input type="text" class="inputer onlyNumbers" data-name="atp_phone" data-den=""
								data-req="1" data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->



					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("emirate", "AAR"); ?></label>
							<select class="inputer" data-name="emirate_id" data-den="0" data-req="1" data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								<?php
								$qu_SEL_sel = "SELECT `city_id`, `city_name` FROM  `sys_countries_cities` ORDER BY `city_id` ASC";
								$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
								if (mysqli_num_rows($qu_SEL_EXE)) {
									while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
										$idder = (int) $SEL_REC['city_id'];
										$namer = $SEL_REC['city_name'];

										?>
										<option value="<?= $idder; ?>"><?= $namer; ?></option>

										<?php
									}
								}
								?>
							</select>
						</div>
					</div>
					<!-- ELEMENT END -->



					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("Category", "AAR"); ?></label>
							<select class="inputer" data-name="atp_category_id" data-den="0" data-req="1"
								data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								<?php
								$qu_SEL_sel = "SELECT `atp_category_id`, `atp_category_name` FROM  `atps_list_categories` ORDER BY `atp_category_id` ASC";
								$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
								if (mysqli_num_rows($qu_SEL_EXE)) {
									while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
										$idder = (int) $SEL_REC['atp_category_id'];
										$namer = $SEL_REC['atp_category_name'];

										?>
										<option value="<?= $idder; ?>"><?= $namer; ?></option>

										<?php
									}
								}
								?>
							</select>
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("Type", "AAR"); ?></label>
							<select class="inputer" data-name="atp_type_id" data-den="0" data-req="1" data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								<?php
								$qu_SEL_sel = "SELECT `atp_type_id`, `atp_type_name` FROM  `atps_list_types` ORDER BY `atp_type_id` ASC";
								$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
								if (mysqli_num_rows($qu_SEL_EXE)) {
									while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
										$idder = (int) $SEL_REC['atp_type_id'];
										$namer = $SEL_REC['atp_type_name'];

										?>
										<option value="<?= $idder; ?>"><?= $namer; ?></option>

										<?php
									}
								}
								?>
							</select>
						</div>
					</div>
					<!-- ELEMENT END -->



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
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>





<script>
	function afterFormSubmission() {
		setTimeout(function () {
			window.location.href = "<?= $POINTER; ?>ext/atps/list/";
		}, 100);
	}
</script>
<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>