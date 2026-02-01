<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Strategy_Self_Studies");
$pageController = $POINTER . 'ext/add_self_study_list';
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
$pageId = 10004;
$subPageId = 200;
include("app/assets.php");








?>


<div class="pageHeader">
	<div class="pageTitle">
		<h1>
			<a href="<?= $POINTER; ?>ext/strategies/self_studies/"><i class="fa-solid fa-arrow-left"></i></a>
			<?= lang("Create self study", "AAR"); ?>
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
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("Self_study_title", "AAR"); ?></label>
							<input type="text" class="inputer" data-name="study_title" data-den="" data-req="1"
								data-type="text">
						</div>
					</div>
					<!-- ELEMENT END -->


					<!-- ELEMENT START -->
					<div class="col-1">
						<div class="formElement">
							<label><?= lang("Self_study_overview", "AAR"); ?></label>
							<textarea type="text" class="inputer" data-name="study_overview" data-den="" rows="8" data-req="1"
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
			window.location.href = "<?= $POINTER; ?>ext/strategies/self_studies/";
		}, 100);
	}
</script>
