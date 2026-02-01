<?php
//initial_form
$pageController = $POINTER . 'save_initial_form';

?>

<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("Initial Request for Registration", "AAR"); ?>
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

				<h2 class="formPanelTitle"><?= lang('Basic_information'); ?></h2>
				<div class="col-1">
                    <div class="formElement">
                        <hr><br>
                    </div>
				</div>


				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Institution_Name"); ?></label>
                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'est_name',
                            'dataDen' => '',
                            'dataValue' => $ATP_NAME,
                            'dataClass' => 'inputer est_name',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Institution_Name"); ?> (AR)</label>
                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'est_name_ar',
                            'dataDen' => '',
                            'dataValue' => $ATP_NAME,
                            'dataClass' => 'inputer est_name_ar',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1',
                            'dir' => 'rtl'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("IQA Lead name"); ?></label>
                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'iqa_name',
                            'dataDen' => '',
                            'dataValue' => '',
                            'dataClass' => 'inputer iqa_name',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("email_address"); ?></label>
                        <?php
                        $props = [
                            'dataType' => 'email',
                            'dataName' => 'email_address',
                            'dataDen' => '',
                            'dataValue' => $ATP_EMAIL,
                            'dataClass' => 'inputer email_address',
                            'isDisabled' => 1,
                            'isReadOnly' => 1,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>


				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Sector"); ?></label>
                        <select data-name="atp_category_id" data-type="int" data-den="0" data-req="1"
                            class="inputer need_data atp_category_id" data-from="<?= $POINTER; ?>get_select_data"
                            data-dest="atps_list_industries" data-initial="<?=$atp_category_id; ?>"></select>
                    </div>
				</div>




				<div class="col-2">
                    <div class="formElement">
                        <label><?= lang("Trade_License_Expiry_date"); ?></label>
                        <?php
                        $props = [
                            'dataType' => 'date',
                            'dataName' => 'registration_expiry',
                            'dataDen' => '',
                            'dataValue' => '',
                            'dataClass' => 'inputer has_future_date registration_expiry',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

                
				<div class="col-1">
                    <div class="formElement">
                        <br><br><br>
                    </div>
				</div>

				<h2 class="formPanelTitle"><?= lang('Address_Details'); ?></h2>
				<div class="col-1">
                    <div class="formElement">
                        <hr><br>
                    </div>
				</div>



				<div class="col-1">
                    <div class="formElement">
                        <label><?= lang("emirate"); ?></label>
                        <select data-name="emirate_id" data-type="int" data-den="0" data-req="1" class="inputer need_data"
                            data-from="<?= $POINTER; ?>get_select_data" data-dest="sys_countries_cities"
                            data-initial="<?= $emirate_id; ?>" readonly disabled></select>
                    </div>
				</div>

				<div class="col-3">
                    <div class="formElement">
                        <label><?= lang("Area"); ?></label>

                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'area_name',
                            'dataDen' => '',
                            'dataValue' => $area_name,
                            'dataClass' => 'inputer area_name',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

				<div class="col-3">
                    <div class="formElement">
                        <label><?= lang("street"); ?></label>

                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'street_name',
                            'dataDen' => '',
                            'dataValue' => $street_name,
                            'dataClass' => 'inputer street_name',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
                    </div>
				</div>

				<div class="col-3">
                    <div class="formElement">
                        <label><?= lang("Building_NO"); ?></label>

                        <?php
                        $props = [
                            'dataType' => 'text',
                            'dataName' => 'building_name',
                            'dataDen' => '',
                            'dataValue' => $building_name,
                            'dataClass' => 'inputer building_name',
                            'isDisabled' => 0,
                            'isReadOnly' => 0,
                            'isRequired' => '1'
                        ];
                        echo build_input(...$props);
                        ?>
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

					if (key == 'emirate_id') {
						$('.' + key).val(dt[key]);
						$('.' + key).change();
					} else {
						$('.' + key).val(dt[key]);
					}

					if (key == 'area_id') {
						aId = dt[key];
					}

				}
			}
		}
	}

	function bindArea() {
		$('.area_id').val(aId);
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