<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Operational_Planning");
$pageController = $POINTER . 'ext/add_ops_projects_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;


$ticket_id = 0;
/*

if (!isset($_GET['ticket_id'])) {
	header("location:" . $DIR_tickets);
	die();
}

$ticket_id = (int) test_inputs($_GET['ticket_id']);

if ($ticket_id == 0) {
	header("location:" . $DIR_tickets);
	die();
}
*/
$pageId = 10003;
$subPageId = 200;
include("app/assets.php");








?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/projects_list/"><i class="fa-solid fa-arrow-left"></i></a>
			<?= lang("Create Project Plan", "AAR"); ?>
		</h1>
	</div>
	<div class="pageNav">

	</div>

	<div class="pageOptions">
		<a class="pageLink pageLinkwithText" onclick="submitForm('newstrategicForm', '<?= $pageController; ?>');"
			style="background: var(--strategy-grey) !important;">

			<div class="linkTxt"><?= lang("Create"); ?></div>
		</a>
	</div>
</div>


<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="tabContainer">
		<div class="tabBody">
			<div class="tabContent tabBodyActive">



				<div class="row pageForm" id="newstrategicForm">



					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("project_code", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="project_code" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("project_name", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="project_name" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->


					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("project_description", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="project_description" data-den="" rows="8" data-req="1"
								data-type="text"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-3">
						<div class="formElement">
							<label><?= lang("From", "AAR"); ?></label>
							<input type="text" class="inputer has_date new-project_start_date" data-name="project_start_date" data-den=""
								data-req="1" data-type="text">
								
						</div>
					</div>
					<!-- ELEMENT END -->
					<!-- ELEMENT START -->
					<div class="col-3">
						<div class="formElement">
							<label><?= lang("To", "AAR"); ?></label>
							<input type="text" class="inputer has_date new-project_end_date" data-name="project_end_date" data-den=""
								data-req="1" data-type="text">
								
						</div>
					</div>
					<!-- ELEMENT END -->


					<!-- ELEMENT START -->
					<div class="col-3">
						<div class="formElement">
							<label><?= lang("Period", "AAR"); ?></label>
							<input type="text" class="inputer new-project_period" data-name="project_period" data-den=""
								data-req="1" data-type="text" readonly disabled>
						</div>
					</div>
					<!-- ELEMENT END -->

					<script>
						function Ncalc_def() {

							var a1 = '' + $('.new-project_start_date').val();
							var a2 = '' + $('.new-project_end_date').val();
							
							if (a1 != '' && a2 != '') {
								
							    // Convert to Date objects
								let d1 = new Date(a1);
								let d2 = new Date(a2);

								if (d1 > d2) {
									// Swap if start > end
									let temp = d1;
									d1 = d2;
									d2 = temp;
								}

								let years = d2.getFullYear() - d1.getFullYear();
								let months = d2.getMonth() - d1.getMonth();
								let days = d2.getDate() - d1.getDate();

								// Adjust days
								if (days < 0) {
									months--;
									let prevMonth = new Date(d2.getFullYear(), d2.getMonth(), 0);
									days += prevMonth.getDate();
								}

								// Adjust months
								if (months < 0) {
									years--;
									months += 12;
								}

								// Final string
								let parts = [];
								if (years > 0) parts.push(years + " year" + (years > 1 ? "s" : ""));
								if (months > 0) parts.push(months + " month" + (months > 1 ? "s" : ""));

								if (days > 0) parts.push(days + " day" + (days > 1 ? "s" : ""));

								difTxt = parts.join(", ");


								if( difTxt == '' ){
									difTxt = '1 Day';
								}



								$('.new-project_period').val(difTxt);
							}
							//-----


						}


						$('.new-project_start_date').on('change', function () {
							Ncalc_def();
						});
						$('.new-project_end_date').on('change', function () {
							Ncalc_def();
						});

					</script>
					

					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("Strategic_Plan", "AAR"); ?></label>
							<select class="inputer" data-name="plan_id" data-den="0" data-req="1" data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								<?php
								$qu_SEL_sel = "SELECT `plan_id`, `plan_title` FROM  `m_strategic_plans` WHERE `is_published` = 1 ORDER BY `plan_id` ASC";
								$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
								if (mysqli_num_rows($qu_SEL_EXE)) {
									while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
										$depId = (int) $SEL_REC['plan_id'];
										$plan_title = $SEL_REC['plan_title'];
										?>
										<option value="<?= $depId; ?>"><?= $plan_title; ?></option>
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
							<label><?= lang("analysis", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="project_analysis" data-den="" rows="8" data-req="1"
								data-type="text"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("recommendations", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="project_recommendations" data-den="" rows="8" data-req="1"
								data-type="text"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->











					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement"><br><br></div>
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
			window.location.href = "<?= $POINTER; ?>ext/strategies/projects_list/";
		}, 100);
	}
</script>
