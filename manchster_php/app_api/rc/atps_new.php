<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Add_New_Training_Provider");
$pageController = $POINTER . 'add_atps_list';
$submitBtn = lang("Add_TP", "AAR");



$atp_id = 0;
$atp_name = "";
$emirate_id = 0;
$contact_name = "";
$contact_name_designation = "";
$atp_email = "";
$atp_phone = "";
$added_by = 0;
$added_date = "";
$accreditation_status = "";
$is_draft = 0;
$stage_no = 0;


$maxStage = 1;
$thsStage = (isset($_GET['stage'])) ? (int) test_inputs($_GET['stage']) : 1;

$isModify = (isset($_GET['modify'])) ? (int) test_inputs($_GET['modify']) : 0;
$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;




/*
	
	//check if there is a draft atp or not
	$qu_atps_list_sel = "SELECT * FROM  `atps_list` WHERE ((`is_draft` = 1) AND (`added_by` = $EMPLOYEE_ID))";
	
	
	$qu_atps_list_EXE = mysqli_query($KONN, $qu_atps_list_sel);
	if(mysqli_num_rows($qu_atps_list_EXE)){
		$atps_list_DATA = mysqli_fetch_assoc($qu_atps_list_EXE);
		$atp_id = ( int ) $atps_list_DATA['atp_id'];
		$atp_name = $atps_list_DATA['atp_name'];
		$developer_name = $atps_list_DATA['developer_name'];
		$atp_status = ( int ) $atps_list_DATA['atp_status'];
		$handover_date = $atps_list_DATA['handover_date'];
		$area_id = ( int ) $atps_list_DATA['area_id'];
		$added_by = ( int ) $atps_list_DATA['added_by'];
		$added_date = $atps_list_DATA['added_date'];
		$is_draft = ( int ) $atps_list_DATA['is_draft'];
		$thsStage = ( int ) $atps_list_DATA['stage_no'];
	}
	


	*/

$nxtStage = $thsStage + 1;
$forwardTo = $POINTER . 'atps/new/?stage=' . $nxtStage;

if ($nxtStage >= $maxStage) {
	//forwadr tp atp page to add images
	$forwardTo = $POINTER . 'atps/view/?atp_id=' . $atp_id;
}
$forwardTo = $POINTER . 'atps/list/?success=true';
$pageId = 500;
$asideIsHidden = true;
include("app/assets.php");
?>


<!-- START OF THREE COL -->
<div class="threeCols">
	<!-- START OF THREE COL -->
	<div class="progresser">
		<a href="<?= $DIR_atps; ?>" class="backBtn"><i
				class="fa-solid fa-arrow-<?= lang('left', 'right'); ?>"></i><span><?= lang('Back'); ?></span></a>
		<h1 class="mainTitle"><?= $page_title; ?></h1>
		<div class="stages">
			<div class="decorer"></div>
			<a class="formStage <?php if ($thsStage == 1) {
				echo 'activeStage" href="#';
			} ?>">
				<div class="numer">1</div>
				<div class="namer"><?= lang('Training_Provider_details'); ?></div>
			</a>
			<?php
			/*
														 <a class="formStage <?php if( $thsStage == 2 ){ echo 'activeStage" href="#'; } ?>">
															 <div class="numer">2</div>
															 <div class="namer"><?=lang('location_details'); ?></div>
														 </a>
														 <a class="formStage <?php if( $thsStage == 3 ){ echo 'activeStage" href="#'; } ?>">
															 <div class="numer">3</div>
															 <div class="namer"><?=lang('atp_images'); ?></div>
														 </a>
														 */
			?>
		</div>
	</div>




	<!-- START OF FORMER COL -->
	<div class="former">
		<?php
		$props = [
			'dataType' => 'hidden',
			'dataName' => 'stage_no',
			'dataDen' => '',
			'dataValue' => $thsStage,
			'dataClass' => 'inputer',
			'isDisabled' => 0,
			'isReadOnly' => 0,
			'isRequired' => '1'
		];
		echo build_input(...$props);
		?>




		<?php
		if ($thsStage == 1) {
			?>
			<!-- START OF FORM PANEL -->
			<div class="formPanel">
				<h2 class="formPanelTitle"><?= lang('Training_Provider_details'); ?></h2>


				<div class="formGroup formGroup-100">
					<label><?= lang("Institution_Name"); ?></label>
					<?php
					$props = [
						'dataType' => 'text',
						'dataName' => 'atp_name',
						'dataDen' => '',
						'dataValue' => $atp_name,
						'dataClass' => 'inputer',
						'isDisabled' => 0,
						'isReadOnly' => 0,
						'isRequired' => '1'
					];
					echo build_input(...$props);
					?>
				</div>

				<div class="formGroup formGroup-50">
					<label><?= lang("contact_person"); ?></label>
					<?php
					$props = [
						'dataType' => 'text',
						'dataName' => 'contact_name',
						'dataDen' => '',
						'dataValue' => $contact_name,
						'dataClass' => 'inputer',
						'isDisabled' => 0,
						'isReadOnly' => 0,
						'isRequired' => '1'
					];
					echo build_input(...$props);
					?>
				</div>

				<div class="formGroup formGroup-50">
					<label><?= lang("contact_person_designation"); ?></label>
					<?php
					$props = [
						'dataType' => 'text',
						'dataName' => 'contact_name_designation',
						'dataDen' => '',
						'dataValue' => $contact_name_designation,
						'dataClass' => 'inputer',
						'isDisabled' => 0,
						'isReadOnly' => 0,
						'isRequired' => '1'
					];
					echo build_input(...$props);
					?>
				</div>

				<div class="formGroup formGroup-50">
					<label><?= lang("email"); ?></label>
					<?php
					$props = [
						'dataType' => 'email',
						'dataName' => 'atp_email',
						'dataDen' => '',
						'dataValue' => $atp_email,
						'dataClass' => 'inputer',
						'isDisabled' => 0,
						'isReadOnly' => 0,
						'isRequired' => '1'
					];
					echo build_input(...$props);
					?>
				</div>

				<div class="formGroup formGroup-50">
					<label><?= lang("phone"); ?></label>
					<?php
					$props = [
						'dataType' => 'text',
						'dataName' => 'atp_phone',
						'dataDen' => '',
						'dataValue' => $atp_phone,
						'dataClass' => 'inputer onlyNumbers',
						'isDisabled' => 0,
						'isReadOnly' => 0,
						'isRequired' => '1'
					];
					echo build_input(...$props);
					?>
				</div>



				<div class="formGroup formGroup-100">
					<label><?= lang("emirate"); ?></label>
					<select data-name="emirate_id" data-type="int" data-den="0" data-req="1" class="inputer need_data"
						data-from="<?= $POINTER; ?>get_select_data" data-dest="sys_countries_cities"
						data-initial="0"></select>
				</div>

				<?php
				/*
																		<div class="formGroup formGroup-100">
																			<label><?=lang("IQC_NOC"); ?></label>
																			<input type="file" 
																					class="inputer" 
																					data-time="0" 
																					data-req="0" 
																					data-type="file" 
																					data-name="iqc_noc" 
																					data-den="" 
																					value="" 
																					placeholder=" * Field is required" 
																					accept="application/pdf"
																					id="inputer-102" data-ids="102">
																		</div>

																		*/
				?>




				<div class="formGroup formGroup-100">
					<label><?= lang("Accredition_Type"); ?></label>
					<select data-name="accreditation_type" data-type="int" data-den="0" data-req="1" class="inputer">
						<option value="0">Please Select</option>
						<option value="1">Institutional Accreditation</option>
						<option value="2">Qualification Registration</option>
					</select>
				</div>

				<div class="formGroup formGroup-100">
					<label><?= lang("Training_Provider_Type"); ?></label>
					<select data-name="atp_type_id" data-type="int" data-den="0" data-req="1" class="inputer need_data"
						data-from="<?= $POINTER; ?>get_select_data" data-dest="atps_list_types" data-initial="0"></select>
				</div>

			</div>
			<!-- END OF FORM PANEL -->
			<?php
		}
		?>





	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">
			<div class="saveBtn actionBtn" onclick="saveFormChanges('<?= $pageController; ?>');">
				<i class="fa-solid fa-arrow-right"></i>
				<span class="label"><?= lang("Next"); ?></span>
			</div>
			<a href="<?= $DIR_atps; ?>" class="cancelBtn actionBtn">
				<i class="fa-solid fa-xmark"></i>
				<span class="label"><?= lang("cancel"); ?></span>
			</a>
		</div>
	</div>





	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->




<script>
	function afterFormSubmission() {
		window.location.href = '<?= $forwardTo; ?>';
	}
</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>