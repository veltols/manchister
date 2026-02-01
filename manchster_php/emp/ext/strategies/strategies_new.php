<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Stratigic Planning");
$pageController = $POINTER . 'ext/add_strategies_list';
$submitBtn = lang("Save_ticket", "AAR");
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
$pageId = 10002;
$subPageId = 200;
include("app/assets.php");








?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/list/"><i class="fa-solid fa-arrow-left"></i></a>
			<?= lang("Create Stratigic Plan", "AAR"); ?>
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
							<label><?= lang("Strategic_Plan_Title", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="plan_title" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->


					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("Period", "AAR"); ?></label>
							<input type="text" class="inputer new-plan_period" data-name="plan_period" data-den=""
								data-req="1" data-type="text" readonly disabled>
						</div>
					</div>
					<!-- ELEMENT END -->


					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("From", "AAR"); ?></label>
							<input type="text" class="inputer has_date new-plan_from" data-name="plan_from" data-den=""
								data-req="1" data-type="text">
								
						</div>
					</div>
					<!-- ELEMENT END -->
					<!-- ELEMENT START -->
					<div class="col-2">
						<div class="formElement">
							<label><?= lang("To", "AAR"); ?></label>
							<input type="text" class="inputer has_date new-plan_to" data-name="plan_to" data-den=""
								data-req="1" data-type="text">
								
						</div>
					</div>
					<!-- ELEMENT END -->


					<script>
						function Ncalc_def() {

							var a1 = '' + $('.new-plan_from').val();
							var a2 = '' + $('.new-plan_to').val();
							
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



								$('.new-plan_period').val(difTxt);
							}
							//-----


						}


						$('.new-plan_from').on('change', function () {
							Ncalc_def();
						});
						$('.new-plan_to').on('change', function () {
							Ncalc_def();
						});

					</script>
					


					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("Organizational_Level", "AAR"); ?></label>
							<select class="inputer" data-name="plan_level" data-den="0" data-req="1" data-type="text">
								<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
								<?php
								$qu_SEL_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments` ORDER BY `department_id` ASC";
								$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
								if (mysqli_num_rows($qu_SEL_EXE)) {
									while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
										$depId = (int) $SEL_REC['department_id'];
										$department_name = $SEL_REC['department_name'];

										?>
										<option value="<?= $depId; ?>"><?= $department_name; ?></option>

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
						<div class="formElement">
							<label><?= lang("plan_vision", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="plan_vision" data-den="" rows="8" data-req="1"
								data-type="text"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("plan_mission", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="plan_mission" data-den="" rows="8" data-req="1"
								data-type="text"></textarea>
						</div>
					</div>
					<!-- ELEMENT END -->

					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("plan_values", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="plan_values" data-den="" rows="8" data-req="1"
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
			window.location.href = "<?= $POINTER; ?>ext/strategies/list/";
		}, 100);
	}
</script>
