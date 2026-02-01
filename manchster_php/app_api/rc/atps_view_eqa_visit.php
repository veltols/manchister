<?php
	
	$page_title=$page_description=$page_keywords=$page_author= lang("Registration_Request_Form");
	$pageController = $POINTER.'add_init_data';
	$submitBtn = lang("Add_TP", "AAR");
	
	

	
	
	$is_draft = 0;
	$stage_no = 0;
	
	
	$atp_id = (isset( $_GET['atp_id'] )) ? ( int ) test_inputs($_GET['atp_id']) : 0;

	
	$added_date = "";
	$submitted_date = "";
	$is_submitted = 0;
	$form_status = "";
	$eqa_visit_date = "";
	$qu_atps_eqa_details_sel = "SELECT * FROM  `atps_eqa_details` WHERE `atp_id` = $atp_id";
	$qu_atps_eqa_details_EXE = mysqli_query($KONN, $qu_atps_eqa_details_sel);
	if(mysqli_num_rows($qu_atps_eqa_details_EXE)){
		$atps_eqa_details_DATA = mysqli_fetch_assoc($qu_atps_eqa_details_EXE);
		$added_date = $atps_eqa_details_DATA['added_date'];
		$submitted_date = $atps_eqa_details_DATA['submitted_date'];
		$is_submitted = ( int ) $atps_eqa_details_DATA['is_submitted'];
		$form_status = $atps_eqa_details_DATA['form_status'];
		$eqa_visit_date = $atps_eqa_details_DATA['eqa_visit_date'];
	}

	
	$pageId = 500;
	$asideIsHidden = true;
	include("app/assets.php");
?>


	<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->
	 


	<!-- START OF FORMER COL -->
	<div class="former">
		


	
	
<!-- START OF FORM PANEL -->
<div class="formPanel">
			<h2 class="formPanelTitle"><?=lang('Quality_Assurance_Visit_Date'); ?></h2>
			

		<div class="formGroup formGroup-100">
			<label><?=lang("preferred_EQA_Visit_Date"); ?></label>
			<?php
	$props = [
		'dataType' => 'date',
		'dataName' => 'eqa_visit_date',
		'dataDen' => '',
		'dataValue' => $eqa_visit_date,
		'dataClass' => 'inputer has_future_date eqa_visit_date',
		'isDisabled' => 0,
		'isReadOnly' => 0, 
		'isRequired' => '0'
	];
	echo build_input( ...$props );
?>
		</div>




		<div class="formGroup formGroup-100">
			<br>
			<br>
			<hr>
		</div>
		

</div>
<!-- END OF FORM PANEL -->









	</div>
		<!-- END OF FORMER COL -->









<div class="describer">
	<div class="formActionBtns">

		<a href="<?=$POINTER; ?>atps/view/?atp_id=<?=$atp_id; ?>" class="backBtn actionBtn" id="thsFormBtns">
			<i class="fa-solid fa-arrow-left"></i>
			<span class="label"><?=lang("Back"); ?></span>
		</a>
<?php
if( $form_status == 'pending' || $form_status == 'denied' ){
?>
			<div class="saveBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?=$atp_id; ?>', 1);">
				<i class="fa-solid fa-check"></i>
				<span class="label"><?=lang("Approve"); ?></span>
			</div>
			<div class="cancelBtn actionBtn" id="thsFormBtns" onclick="actionForm('<?=$atp_id; ?>', 0);">
				<i class="fa-solid fa-x"></i>
				<span class="label"><?=lang("Decline"); ?></span>
			</div>
			<div class="revBtn actionBtn" id="thsFormBtns" onclick="showRev();">
				<i class="fa-solid fa-refresh"></i>
				<span class="label"><?=lang("Revise"); ?></span>
			</div>
			<br>
			<br>
			<textarea name="comments" id="rc_comment" style="width:100%;padding:0.5em;margin:1% auto;display:block;" rows="8" placeholder="Insert Comment"></textarea>
			<div class="saveBtn actionBtn" id="revSubimt" onclick="actionForm('<?=$atp_id; ?>', 2);">
				<i class="fa-solid fa-save"></i>
				<span class="label"><?=lang("Submit"); ?></span>
			</div>
<?php
}
?>


	</div>
	
</div>





	<!-- END OF THREE COL -->
</div>
	<!-- END OF THREE COL -->

	

<script>
function actionForm(atpId, ops){
	var aa = confirm("Are you sure?");
	var rc_comment = $('#rc_comment').val();
	if( aa == true ){
		if( activeRequest == false ){
			activeRequest = true;
			start_loader('article');
			$.ajax({
				url      : '<?=$POINTER; ?>approve_atp_form', 
				dataType : "JSON", 
				method   : "POST", 
				data     : { "form" : 'eqa_visit_date', 'atp_id' : atpId, 'ops' : ops, 'rc_comment' : rc_comment }, 
				success: function( RES ){
					
					var itm = RES[0];
					activeRequest = false;
					end_loader('article');
					makeSiteAlert('suc', '<?=lang("Data Saved"); ?>');
					setTimeout(function(){
						window.location.href = '<?=$POINTER; ?>atps/view/?atp_id=<?=$atp_id; ?>';
					}, 1000);
				}, 
				error: function(){
					activeRequest = false;
					end_loader('article');
					alert("Err-327657");
				}
			});
		}

	}
}


function showRev(){
	$('#rc_comment').toggle();
	$('#revSubimt').toggle();
}




</script>



<?php
	include("app/footer.php");
	include("../public/app/form_controller.php");
?>

<script>
	$('#rc_comment').hide();
	$('#revSubimt').hide();
</script>