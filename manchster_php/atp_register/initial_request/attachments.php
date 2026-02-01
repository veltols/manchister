<?php
//initial_form
$pageController = $POINTER . 'save_initial_form';

?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("Attachments_required", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
		<?php
			include_once('main_nav.php');
		?>

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

				<h2 class="formPanelTitle"><?= lang('Attachments'); ?></h2>
				<div class="col-1">
                    <div class="formElement">
                        <hr><br>
                    </div>
				</div>


                
                

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("delivery_plan"); ?></label>
                        <input type="file" class="inputer input-delivery_plan" data-time="0" data-req="1" data-type="file" data-name="delivery_plan"
                            data-den="" value="" placeholder="" accept="application/pdf" id="inputer-101"
                            data-ids="101">
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Status"); ?></label>
                        <label id="lbl-delivery_plan"><?= lang("Not uploaded"); ?></label>
                    </div>
				</div>
                

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Approved_Organization_Chart"); ?></label>
                        <input type="file" class="inputer input-org_chart" data-time="0" data-req="1" data-type="file" data-name="org_chart"
                            data-den="" value="" placeholder="" accept="application/pdf" id="inputer-101"
                            data-ids="101">
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Status"); ?></label>
                        <label id="lbl-org_chart"><?= lang("Not uploaded"); ?></label>
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Site_Plan"); ?></label>
                        <input type="file" class="inputer input-site_plan" data-time="0" data-req="1" data-type="file" data-name="site_plan"
                            data-den="" value="" placeholder="" accept="application/pdf" id="inputer-101"
                            data-ids="101">
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Status"); ?></label>
                        <label id="lbl-site_plan"><?= lang("Not uploaded"); ?></label>
                    </div>
				</div>
                

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("SED_Form"); ?></label>
                        <input type="file" class="inputer input-sed_form" data-time="0" data-req="1" data-type="file" data-name="sed_form"
                            data-den="" value="" placeholder="" accept="application/pdf" id="inputer-101"
                            data-ids="101">
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Status"); ?></label>
                        <label id="lbl-sed_form"><?= lang("Not uploaded"); ?></label>
                    </div>
				</div>
                

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Logo"); ?></label>
                        <input type="file" class="inputer" data-time="0" data-req="0" data-type="file" data-name="atp_logo"
                            data-den="" value="" placeholder="" accept="image/png, image/jpg, image/jpeg" id="inputer-101"
                            data-ids="101">
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Status"); ?></label>
                        <label id="lbl-atp_logo"><?= lang("Not uploaded"); ?></label>
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

var eId = 0;
	var aId = 0;
	function bendFormData(mainDt) {
		for (indx = 0; indx < mainDt.length; indx++) {
			var dt = mainDt[indx];
			for (var key in dt) {
				if (dt.hasOwnProperty(key)) {
					if( dt[key] != '' ){

                        
                        $('.input-' + key).attr('data-req', '0');
                        $('#lbl-' + key).text('Uploaded - ');
                        $('#lbl-' + key).append('<a class="fileBtnView" target="_blank" href="../<?=$POINTER; ?>uploads/' + dt[key] + '" >View File</a>');

                    }
					

				}
			}
		}
	}

    

	function loadFormData() {
		start_loader('article');
		$.ajax({
			url: '<?= $POINTER; ?>get_initial_form',
			dataType: "JSON",
			method: "POST",
			data: { "stage_no": 0 },
			success: function (RES) {

				end_loader();
				var itm = RES[0];
				var is_submitted = itm['is_submitted'];
				var dt = itm['data'];
				if (is_submitted) {
					window.location.href = '<?= $DIR_dashboard; ?>';
				} else {
					bendFormData(dt);
				}
			},
			error: function (res) {
				activeRequest = false;
				end_loader('article');
				alert("Failed to load data");
			}
		});

	}
</script>

<?php
include("app/footer.php");
?>

<script>
	function afterFormSubmission() {
		setTimeout(function () {
			//window.location.href = "<?= $POINTER; ?>exst/";
            window.location.reload();
		}, 100);
	}
</script>
<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>


<script>
    loadFormData();

</script>