<?php
//initial_form
$pageDataController = $POINTER . "get_statistic_list";
$addNewStatisticController = $POINTER . 'add_statistic_list';
//$editStatisticController = $POINTER . 'update_atp_statistic';
$deleteStatisticController = $POINTER . 'remove_atp_statistic';
?>
<style>
    .panelFooter {
        display: none !important;
    }
    .ww span {
        background: var(--primary) !important;
        color: var(--on-primary) !important;
        cursor: pointer !important;
    }
</style>
<div class="pageHeader">
	<div class="pageTitle">
        
		<h1>
        <a href="<?=$POINTER; ?>accreditation/new/"><i class="fa-solid fa-arrow-left"></i></a>
            <?= lang("Statistics / Estimation of expected number of learner and graduates", "AAR"); ?>
        </h1>
	</div>
	<div class="pageNav">
		<?php
			include_once('main_nav.php');
		?>
	</div>

	<div class="pageOptions">
        <br>
	</div>
</div>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->



            <h1 class="viewPageHeading" style="padding: 0 1em;padding-top: 1em;">
            Learners registered in Vocational Qualifications (if Applicable):
            </h1>

			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Qualification"); ?></div>
							<div class="th"><?= date('Y') - 4; ?></div>
							<div class="th"><?= date('Y') - 3; ?></div>
							<div class="th"><?= date('Y') - 2; ?></div>
							<div class="th"><?= date('Y') - 1; ?></div>
							<div class="th ww" onclick="addStatisticsRow('registered');"><i class="fa-solid fa-plus"></i></div>
						</div>
					</div>
					<div class="tableBody" id="listData-registered"></div>
				</div>
			</div>

                <br><br>
            <h1 class="viewPageHeading" style="padding: 0 1em;padding-top: 1em;">
            Awarded Certificates in vocational Qualifications (if Applicable):
            </h1>

			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Qualification"); ?></div>
							<div class="th"><?= date('Y') - 3; ?></div>
							<div class="th"><?= date('Y') - 2; ?></div>
							<div class="th"><?= date('Y') - 1; ?></div>
							<div class="th"><?= date('Y'); ?></div>
							<div class="th ww" onclick="addStatisticsRow('awarded');"><i class="fa-solid fa-plus"></i></div>
						</div>
					</div>
					<div class="tableBody" id="listData-awarded"></div>
				</div>
			</div>





<script>
	async function bindData(data) {
		$('#listData-registered').html('<?= lang(''); ?>');
		$('#listData-awarded').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
            var tshType = data[i]["statistic_type"];
			var btns = '';
			btns += '<a onclick="deleteStatistic(' + data[i]["statistic_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-solid fa-trash"></i>' +
				'	<div class="tooltip"><?= lang("Delete"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="statistic-' + data[i]["statistic_id"] + '">' +
				'	<div class="td">' + data[i]["qualification_name"] + '</div>' +
				'	<div class="td">' + data[i]["y1_value"] + '</div>' +
				'	<div class="td">' + data[i]["y2_value"] + '</div>' +
				'	<div class="td">' + data[i]["y3_value"] + '</div>' +
				'	<div class="td">' + data[i]["y4_value"] + '</div>' +
				'	<div class="td">' +
				'		<div class="tblBtnsGroup">' +
				btns +
				'		</div>' +
				'	</div>' +
				'</div>';
            

			$('#listData-' + tshType).append(dt);
			listCount++;
		}

        

	}
    
    
	function addStatisticsRow(typer) {
        if( typer == 'registered' ){
            showModal('newRegisteredStatisticModal');
        } else if( typer == 'awarded' ){
            showModal('newAwardedStatisticModal');
        }
	}



	

    function deleteStatistic(qId) {
		var aa = confirm('<?= lang("Are_you_sure?", "AAR"); ?>');
		if (aa) {
			if (qId != 0) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $deleteStatisticController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "statistic_id": qId },
					success: function (RES) {
						activeRequest = false;
						end_loader('article');
						thsDt = RES[0];
						if (thsDt['success'] == true) {
							makeSiteAlert('suc', '<?= lang("Statistic_Removed_Successfully", "AAR"); ?>');
							$('#statistic-' + qId).remove();
						} else {
							alert("Failed to load data - 5454");
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
	}
</script>


<?php
include("../public/app/footer_records.php");
?>
<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->

<?php
include("app/footer.php");
?>






<!-- Modals START -->
<div class="modal" id="newAwardedStatisticModal">
	<div class="modalHeader">
		<h1><?= lang("Awarded Certificates in vocational Qualifications (if Applicable):", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newAwardedStatisticForm">


        <input type="hidden" class="inputer statistic_type" data-name="statistic_type" data-den="" value="awarded" data-req="1"
						data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Qualification", "AAR"); ?></label>
					<select class="inputer" data-name="qualification_id" data-den="0" data-req="1" data-type="text">
							<option value="0"><?= lang("Please_Select"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `qualification_id`, `qualification_name` FROM  `atps_list_qualifications` ORDER BY `qualification_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$qualification_id = (int) $SEL_REC['qualification_id'];
								$qualification_name = $SEL_REC['qualification_name'];
								?>
								<option value="<?= $qualification_id; ?>"><?= $qualification_name; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

<?php
    $counter = 1;
    for( $year = date('Y') - 3 ; $year <= date('Y') ; $year++  ){
        $thsName = 'y'.$counter.'_value';
?>
            <!-- ELEMENT START -->
            <div class="col-2">
                <div class="formElement">
                    <label><?= $year; ?></label>
                    <input type="text" class="inputer onlyNumbers" data-name="<?=$thsName; ?>" data-den="" data-req="1"
                        data-type="int">
                </div>
            </div>
            <!-- ELEMENT END -->

<?php
    $counter++;
    }
?>
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newAwardedStatisticForm', '<?= $addNewStatisticController; ?>');"><?= lang("Add_Statistic", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->

<!-- Modals START -->
<div class="modal" id="newRegisteredStatisticModal">
	<div class="modalHeader">
		<h1><?= lang("Learners registered in Vocational Qualifications (if Applicable):", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newRegisteredStatisticForm">


        <input type="hidden" class="inputer statistic_type" data-name="statistic_type" data-den="" value="registered" data-req="1"
						data-type="hidden">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Qualification", "AAR"); ?></label>
					<select class="inputer" data-name="qualification_id" data-den="0" data-req="1" data-type="text">
							<option value="0"><?= lang("Please_Select"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `qualification_id`, `qualification_name` FROM  `atps_list_qualifications` ORDER BY `qualification_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$qualification_id = (int) $SEL_REC['qualification_id'];
								$qualification_name = $SEL_REC['qualification_name'];
								?>
								<option value="<?= $qualification_id; ?>"><?= $qualification_name; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

<?php
    $counter = 1;
    for( $year = date('Y') - 4 ; $year < date('Y') ; $year++  ){
        $thsName = 'y'.$counter.'_value';
?>
            <!-- ELEMENT START -->
            <div class="col-2">
                <div class="formElement">
                    <label><?= $year; ?></label>
                    <input type="text" class="inputer onlyNumbers" data-name="<?=$thsName; ?>" data-den="" data-req="1"
                        data-type="int">
                </div>
            </div>
            <!-- ELEMENT END -->

<?php
    $counter++;
    }
?>
			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newRegisteredStatisticForm', '<?= $addNewStatisticController; ?>');"><?= lang("Add_Statistic", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->






<script>
	function afterFormSubmission() {
		closeModal();
		goToFirstPage();
        /*
		setTimeout(function () {
			window.location.reload();
		}, 400);
        */
	}
    
</script>

<?php
include("../public/app/footer_modal.php");
include("../public/app/form_controller.php");
?>


<script>
    
	initForms();
	doOnlyNumbers();
</script>