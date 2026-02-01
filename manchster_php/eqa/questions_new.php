<?php
	
	$page_title=$page_description=$page_keywords=$page_author= lang("Add_New_Question");
	$pageController = $POINTER.'add_questions_list';
	$submitBtn = lang("Add_TP", "AAR");
	
	

	$q_id = 0;
	$q_text = "";

	$is_draft = 0;
	$stage_no = 0;
	

	$maxStage = 1;
	$thsStage = (isset( $_GET['stage'] )) ? ( int ) test_inputs($_GET['stage']) : 1;

	$isModify = (isset( $_GET['modify'] )) ? ( int ) test_inputs($_GET['modify']) : 0;
	$q_id = (isset( $_GET['q_id'] )) ? ( int ) test_inputs($_GET['q_id']) : 0;


	

	
	$nxtStage = $thsStage + 1;
	$forwardTo = $POINTER.'questions/new/?stage='.$nxtStage;

	if( $nxtStage >= $maxStage ){
		//forwadr tp atp page to add images
		$forwardTo = $POINTER.'questions/view/?q_id='.$q_id;
	}
	$forwardTo = $POINTER.'questions/list/?success=true';
	$pageId = 600;
	$asideIsHidden = true;
	include("app/assets.php");
?>


	<!-- START OF THREE COL -->
<div class="threeCols">
	<!-- START OF THREE COL -->
	<div class="progresser">
		<a href="<?=$DIR_questions; ?>" class="backBtn"><i class="fa-solid fa-arrow-<?=lang('left', 'right'); ?>"></i><span><?=lang('Back'); ?></span></a>
		<h1 class="mainTitle"><?=$page_title; ?></h1>
		<div class="stages">
			<div class="decorer"></div>
			<a class="formStage <?php if( $thsStage == 1 ){ echo 'activeStage" href="#'; } ?>">
				<div class="numer">1</div>
				<div class="namer"><?=lang('Question_details'); ?></div>
			</a>
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
	echo build_input( ...$props );
?>




<?php
if( $thsStage == 1 ){
?>
<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?=lang('Question_details'); ?></h2>


		<div class="formGroup formGroup-100">
			<label><?=lang("Question"); ?></label>
<?php
								$props = [
									'dataType' => 'text',
									'dataName' => 'q_text',
									'dataDen' => '',
									'dataValue' => $q_text,
									'dataClass' => 'inputer q_text',
									'extraAttributes' => 'rows="8"',
									'isDisabled' => 0,
									'isReadOnly' => 0, 
									'isRequired' => '1'
								];
								echo build_textarea( ...$props );
?>
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
		<div class="saveBtn actionBtn" onclick="saveFormChanges('<?=$pageController; ?>');">
			<i class="fa-solid fa-arrow-right"></i>
			<span class="label"><?=lang("Save"); ?></span>
		</div>
		<a href="<?=$DIR_atps; ?>" class="cancelBtn actionBtn">
			<i class="fa-solid fa-xmark"></i>
			<span class="label"><?=lang("cancel"); ?></span>
		</a>
	</div>
</div>





	<!-- END OF THREE COL -->
</div>
	<!-- END OF THREE COL -->




<script>
function afterFormSubmission(){
	window.location.href = '<?=$forwardTo; ?>';
}
</script>



<?php
	include("app/footer.php");
	include("../public/app/form_controller.php");
?>


