<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Feedback");

$addNewController = $POINTER . 'submit_feedback_form';
$navId = 1;
$pageId = 3000;
$subPageId = 150;



$qu_feedback_forms_sel = "SELECT * FROM  `feedback_forms` WHERE `employee_id` = $USER_ID";
$qu_feedback_forms_EXE = mysqli_query($KONN, $qu_feedback_forms_sel);
if (mysqli_num_rows($qu_feedback_forms_EXE) > 0) {
	header(header: "location:" . $POINTER . "dashboard/");
	die();
}


include("app/assets.php");





?>

<div class="pageHeader">
	<div class="pageNav">
		<a href="<?= $DIR_tasks; ?>" class="pageNavLink activePageNavLink">
			<span><i class="fa-solid fa-list"></i><?= lang("Feedback_Form", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
	</div>
</div>



<div class="tableContainer" style="width: 90%;background:#FFF;padding:1em;" id="appsListDtd">


	<div class="row pageForm" id="AddNewForm">


		<h1 style="text-align:center;width:100%;">
			Portal- Feedback Form
		</h1>
		<p>As a valued Staff member, your feedback is important in improving & enhancing the platform to serve your
			needs better. we kindly invite you to spare a few minutes and share your valuable feedback.</p>



		<!-- ELEMENT START -->
		<div class="col-1">
			<br>
		</div>
		<!-- ELEMENT END -->

		<input type="hidden" class="inputer new-employee_id" data-name="employee_id" data-den="" data-req="1"
			value="<?= $USER_ID; ?>" data-type="hidden">


		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("Q1. How would you rate the portals’ user interface, in terms of:", "AAR"); ?></label>
			</div>
		</div>
		<!-- ELEMENT END -->


		<!-- ELEMENT START -->
		<div class="col-3">
			<div class="formElement">
				<label><?= lang("User-friendliness", "AAR"); ?></label>
				<select class="inputer" data-name="a1" data-den="0" data-req="1" data-type="text">
					<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
					<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
					<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
					<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
					<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
					<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
				</select>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-3">
			<div class="formElement">
				<label><?= lang("Visual appeal", "AAR"); ?></label>
				<select class="inputer" data-name="a2" data-den="0" data-req="1" data-type="text">
					<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
					<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
					<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
					<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
					<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
					<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
				</select>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-3">
			<div class="formElement">
				<label><?= lang("Login and logout credentials", "AAR"); ?></label>
				<select class="inputer" data-name="a3" data-den="0" data-req="1" data-type="text">
					<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
					<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
					<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
					<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
					<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
					<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
				</select>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-1">
			<hr>
		</div>

		<!-- ELEMENT START -->
		<div class="col-2">
			<div class="formElement">
				<label><?= lang("Did you encouter any technical issues while using the portal?", "AAR"); ?></label>
				<select class="inputer" data-name="a4" data-den="100" data-req="1" data-type="text">
					<option value="100" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
					<option value="Yes"><?= lang(data: "Yes", trans: "AAR"); ?></option>
					<option value="No"><?= lang(data: "No", trans: "AAR"); ?></option>
				</select>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-2">
			<div class="formElement">
				<label><?= lang("If yes, please explain", "AAR"); ?></label>
				<textarea type="text" class="inputer" data-name="a5" data-den="" data-req="1" data-type="text"
					rows="3"></textarea>
			</div>
		</div>
		<!-- ELEMENT END -->


		<!-- ELEMENT START -->
		<div class="col-1">
			<hr>
		</div>


		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("How would you rate your experience while using the following features :", "AAR"); ?></label>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-1">

			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th" style="width: 30%;"><?= lang("Feature"); ?></div>
							<div class="th"><?= lang("Rate"); ?></div>
							<div class="th"><?= lang("Comment"); ?></div>
						</div>
					</div>
					<div class="tableBody">
						<div class="tr levelRow">
							<div class="td">
								Calendar
							</div>
							<div class="td">
								<div class="formElement">
									<select class="inputer" data-name="a6" data-den="0" data-req="1" data-type="text">
										<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?>
										</option>
										<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
										<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
										<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
										<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
										<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
									</select>
								</div>

							</div>
							<div class="td">
								<div class="formElement">
									<textarea type="text" class="inputer" data-name="a7" data-den="" data-req="1"
										data-type="text" rows="3" placeholder="If yes, please explain"></textarea>
								</div>
							</div>
						</div>
						<div class="tr levelRow">
							<div class="td"></div>
							<div class="td"></div>
							<div class="td"></div>
						</div>
						<div class="tr levelRow">
							<div class="td">
								Chat
							</div>
							<div class="td">
								<div class="formElement">
									<select class="inputer" data-name="a8" data-den="0" data-req="1" data-type="text">
										<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?>
										</option>
										<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
										<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
										<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
										<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
										<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
									</select>
								</div>

							</div>
							<div class="td">
								<div class="formElement">
									<textarea type="text" class="inputer" data-name="a9" data-den="" data-req="1"
										data-type="text" rows="3" placeholder="If yes, please explain"></textarea>
								</div>
							</div>
						</div>
						<div class="tr levelRow">
							<div class="td"></div>
							<div class="td"></div>
							<div class="td"></div>
						</div>
						<div class="tr levelRow">
							<div class="td">
								Tasks
							</div>
							<div class="td">
								<div class="formElement">
									<select class="inputer" data-name="a10" data-den="0" data-req="1" data-type="text">
										<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?>
										</option>
										<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
										<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
										<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
										<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
										<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
									</select>
								</div>

							</div>
							<div class="td">
								<div class="formElement">
									<textarea type="text" class="inputer" data-name="a11" data-den="" data-req="1"
										data-type="text" rows="3" placeholder="If yes, please explain"></textarea>
								</div>
							</div>
						</div>
						<div class="tr levelRow">
							<div class="td"></div>
							<div class="td"></div>
							<div class="td"></div>
						</div>
						<div class="tr levelRow">
							<div class="td">
								IT Tickets
							</div>
							<div class="td">
								<div class="formElement">
									<select class="inputer" data-name="a12" data-den="0" data-req="1" data-type="text">
										<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?>
										</option>
										<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
										<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
										<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
										<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
										<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
									</select>
								</div>

							</div>
							<div class="td">
								<div class="formElement">
									<textarea type="text" class="inputer" data-name="a13" data-den="" data-req="1"
										data-type="text" rows="3" placeholder="If yes, please explain"></textarea>
								</div>
							</div>
						</div>
						<div class="tr levelRow">
							<div class="td"></div>
							<div class="td"></div>
							<div class="td"></div>
						</div>
						<div class="tr levelRow">
							<div class="td">
								HR Requests
							</div>
							<div class="td">
								<div class="formElement">
									<select class="inputer" data-name="a14" data-den="0" data-req="1" data-type="text">
										<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?>
										</option>
										<option value="Excellent"><?= lang(data: "Excellent", trans: "AAR"); ?></option>
										<option value="Good"><?= lang(data: "Good", trans: "AAR"); ?></option>
										<option value="Average"><?= lang(data: "Average", trans: "AAR"); ?></option>
										<option value="Poor"><?= lang(data: "Poor", trans: "AAR"); ?></option>
										<option value="very_Poor"><?= lang(data: "very_Poor", trans: "AAR"); ?></option>
									</select>
								</div>

							</div>
							<div class="td">
								<div class="formElement">
									<textarea type="text" class="inputer" data-name="a15" data-den="" data-req="1"
										data-type="text" rows="3" placeholder="If yes, please explain"></textarea>
								</div>
							</div>
						</div>
						<div class="tr levelRow">
							<div class="td"></div>
							<div class="td"></div>
							<div class="td"></div>
						</div>

					</div>
				</div>
			</div>

		</div>
		<!-- ELEMENT END -->




		<!-- ELEMENT START -->
		<div class="col-1">
			<br>
			<br>
			<hr>
		</div>

		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("What new feature(s) would you like to be added to the portal", "AAR"); ?></label>
				<textarea type="text" class="inputer new-da_remark" data-name="a16" data-den="" data-req="1"
					data-type="text" rows="5"></textarea>
			</div>
		</div>
		<!-- ELEMENT END -->


		<!-- ELEMENT START -->
		<div class="col-1">
			<br>
			<br>
			<hr>
		</div>

		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("Notes", "AAR"); ?></label>
				<textarea type="text" class="inputer" data-name="a17" data-den="" data-req="1" data-type="text"
					rows="5"></textarea>
			</div>
		</div>
		<!-- ELEMENT END -->


		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement"><br><br></div>
		</div>
		<div class="col-1" id="addNewDABtn">
			<div class="formElement">
				<button class="submitBtn" type="button"
					onclick="submitForm('AddNewForm', '<?= $addNewController; ?>');"><?= lang("Submit Form", "AAR"); ?></button>
			</div>
		</div>
		<!-- ELEMENT END -->

		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement"><br><br></div>
		</div>
	</div>


</div>













<?php
include("app/footer.php");
?>

<script>
	function afterFormSubmission() {
		window.location.reload();
	}
</script>
