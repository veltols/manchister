<?php
//initial_form
$pageController = $POINTER . 'save_initial_form';

?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("Program Registration Submission", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
        
<a class="pageNavLink activePageNavLink">
	<span><?= lang("submit", "AAR"); ?></span>
	<div class="dec"></div>
</a>

	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="submitForm('thsPageForm', '<?= $pageController; ?>');" style="width: 10% !important;text-align: center;">

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

            <div class="row pageForm" id="thsPageForm">

				<h2 class="formPanelTitle"><?= lang('Terms & Conditions for Submission of Information'); ?></h2>
				<div class="col-1">
                    <div class="formElement">
                        <hr><br>
                    </div>
				</div>



                    <h2><br></h2>
                    <h4 style="line-height: 2.3;">
                        1. Accuracy of Information By submitting your information, you confirm that all details provided are
                        true, accurate, and complete to the best of your knowledge. Any false or misleading information may
                        result in the rejection of your application or certification.<br>
                        2. Acknowledgment and Acceptance You acknowledge and accept that the information you have submitted
                        will be used to process your application or qualification. The Awarding Body is not responsible for
                        any discrepancies resulting from incorrect or incomplete submissions.<br>
                        3. Verification and Review The Awarding Body reserves the right to verify the submitted information
                        and may request additional documentation or clarification if necessary. If the information meets the
                        required standards, your application will proceed to the next stage of the process.<br>
                        4. Next Level Progression Upon successful submission and acceptance of the information, you will
                        proceed to the next level. Any failure to meet the required standards at this stage may result in
                        disqualification or delay.<br>
                        5. Liability You agree to bear full responsibility for the accuracy of the information provided. Any
                        consequences arising from inaccurate information are solely your responsibility.<br>
                        6. Data Protection All personal information will be handled in compliance with UAE data protection
                        laws and will only be used for the purpose of processing your application.<br>


                    </h4>
                

                    <div class="formGroup formGroup-100">
											<br>
											<br>
											<hr>
										</div>

										<div class="formGroup formGroup-50" style="margin: 0 auto;">
											<div class="saveBtn actionBtn" onclick="submitInitialForm();">
												<i class="fa-solid fa-arrow-right"></i>
												<span class="label"><?= lang("Accept"); ?></span>
											</div>
										</div>

			</div>
			<!-- END OF FORM -->





			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->


<script>
    function submitInitialForm() {
        var aa = confirm('<?= lang("Are_you_sure?", "AAR"); ?>');
        if (aa) {
            activeRequest = true;
            start_loader('article');
            $.ajax({
                url: '<?= $POINTER; ?>submit_program_reg_form',
                dataType: "JSON",
                method: "POST",
                data: { "location_id": 1 },
                success: function (RES) {
                    activeRequest = false;
                    end_loader('article');
                    thsDt = RES[0];
                    if (thsDt['success'] == true) {
                        window.location.href = '<?= $DIR_dashboard; ?>';
                    } else {
                        alert("Failed to load data - 22");
                    }
                },
                error: function (res) {
                    activeRequest = false;
                    end_loader('article');
                    console.log(res);
                    alert("Failed to load data");
                }
            });
        }
    }
</script>
<?php
include("app/footer.php");
?>

<script>
	function afterFormSubmission() {
		setTimeout(function () {
			window.location.href = '<?= $DIR_dashboard; ?>';
		}, 100);
	}
</script>
<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>


<script>
    
</script>