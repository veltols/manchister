<?php
	
	$page_title=$page_description=$page_keywords=$page_author= lang("Qualification_Registration");
	$pageController = $POINTER.'add_init_data';
	$submitBtn = lang("Add_TP", "AAR");
	
	

	
	
	$is_draft = 0;
	$stage_no = 0;
	
	
	$atp_id = (isset( $_GET['atp_id'] )) ? ( int ) test_inputs($_GET['atp_id']) : 0;

	
	$added_date = "";
	$submitted_date = "";
	$is_submitted = 0;
	$form_status = "";
	$qu_atps_qualification_ev_sel = "SELECT * FROM  `atps_qualification_ev` WHERE `atp_id` = $atp_id";
	$qu_atps_qualification_ev_EXE = mysqli_query($KONN, $qu_atps_qualification_ev_sel);
	if(mysqli_num_rows($qu_atps_qualification_ev_EXE)){
		$atps_qualification_ev_DATA = mysqli_fetch_assoc($qu_atps_qualification_ev_EXE);
		$added_date = $atps_qualification_ev_DATA['added_date'];
		$submitted_date = $atps_qualification_ev_DATA['submitted_date'];
		$is_submitted = ( int ) $atps_qualification_ev_DATA['is_submitted'];
		$form_status = $atps_qualification_ev_DATA['form_status'];
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
		


<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- START OF FORM PANEL -->
<div class="formPanel">
	<h2 class="formPanelTitle"><?=lang('Qualifications'); ?></h2>
	<div class="dataContainer" id="listData">

<?php



	$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE ((`atp_id` = $atp_id) )";
	$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
	if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
		while($atps_list_qualifications_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE)){
			$qualification_id = ( int ) $atps_list_qualifications_REC['qualification_id'];
			$qualification_code = $atps_list_qualifications_REC['qualification_code'];
			$qualification_name = $atps_list_qualifications_REC['qualification_name'];
			$qualification_provider = $atps_list_qualifications_REC['qualification_provider'];
			$qualification_type = $atps_list_qualifications_REC['qualification_type'];
			$learners_entry_no = ( int ) $atps_list_qualifications_REC['learners_entry_no'];
			$learners_estimate = ( int ) $atps_list_qualifications_REC['learners_estimate'];
			$academic_session = $atps_list_qualifications_REC['academic_session'];
			$atp_id = ( int ) $atps_list_qualifications_REC['atp_id'];
		?>
		

					<div class="dataElement data-list-view" id="f-<?=$qualification_id; ?>">
						<div class="dataView col-2">
							<div class="property"><?=lang("qualification_code"); ?></div>
							<div class="value"><?=$qualification_code; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("qualification_name"); ?></div>
							<div class="value"><?=$qualification_name; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("learners_estimate"); ?></div>
							<div class="value"><?=$learners_estimate; ?></div>
						</div>
					</div>

		<?php
		}
	}

?>
	</div>
</div>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- START OF FORM PANEL -->
<div class="formPanel">
	<h2 class="formPanelTitle"><?=lang('Faculty'); ?></h2>
	<div class="dataContainer" id="listData">

<?php


	$qu_atps_list_faculties_sel = "SELECT * FROM  `atps_list_faculties` WHERE ((`atp_id` = $atp_id) AND (`qualification_id` != 0))";
	$qu_atps_list_faculties_EXE = mysqli_query($KONN, $qu_atps_list_faculties_sel);
	if(mysqli_num_rows($qu_atps_list_faculties_EXE)){
		while($atps_list_faculties_REC = mysqli_fetch_assoc($qu_atps_list_faculties_EXE)){
			$faculty_id   = ( int ) $atps_list_faculties_REC['faculty_id'];
			$faculty_name = "".$atps_list_faculties_REC['faculty_name'];
			$faculty_type = "".$atps_list_faculties_REC['faculty_type'];
			$faculty_spec = "".$atps_list_faculties_REC['faculty_spec'];
			$faculty_cv   = "".$atps_list_faculties_REC['faculty_cv'];
			$qualification_id   = "".$atps_list_faculties_REC['qualification_id'];
			


			$qualification_name = "";
		$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE ((`atp_id` = $atp_id) AND (`qualification_id` = $qualification_id))";
		$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
		if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
			$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
			$qualification_name = $atps_list_qualifications_DATA['qualification_code']. ' - ' .$atps_list_qualifications_DATA['qualification_name'];
		}
	



			$thsType = '';
			$dbType = ( int ) $atps_list_faculties_REC['faculty_type'];
			if( $dbType == 1 ){
				$thsType = 'IQA';
			} else if( $dbType == 2 ){
				$thsType = 'IQA_Lead';
			} else if( $dbType == 3 ){
				$thsType = 'Assessor';
			} else if( $dbType == 4 ){
				$thsType = 'Trainer';
			} else if( $dbType == 5 ){
				$thsType = 'Teacher';
			}
			
		?>
		

					<div class="dataElement data-list-view" id="f-<?=$faculty_id; ?>">
						<div class="dataView col-2">
							<div class="property"><?=lang("Name"); ?></div>
							<div class="value"><?=$faculty_name; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("Type"); ?></div>
							<div class="value"><?=$thsType; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("Specialization"); ?></div>
							<div class="value"><?=$faculty_spec; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("qualification"); ?></div>
							<div class="value"><?=$qualification_name; ?></div>
						</div>
<div class="dataView col-2">
	<div class="property"><?=lang("CV"); ?></div>
<?php
if( $faculty_cv != '' ){
?>
	<div class="value"><a href="../<?=$POINTER; ?>uploads/<?=$org_chart; ?>" target="_blank">View CV</a></div>
<?php
} else {
?>
	<div class="value">NA</div>
<?php
}
?>
</div>
					</div>

		<?php
		}
	}

?>
	</div>
</div>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->





<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- START OF FORM PANEL -->
<div class="formPanel">
	<h2 class="formPanelTitle"><?=lang('Compliance_Evidence'); ?></h2>
	<div class="dataContainer" id="listData">

<?php



	$qu_atps_list_qualifications_evidence_sel = "SELECT * FROM  `atps_list_qualifications_evidence` WHERE ((`atp_id` = $atp_id) AND (`qualification_id` != 0))";
	$qu_atps_list_qualifications_evidence_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_evidence_sel);
	if(mysqli_num_rows($qu_atps_list_qualifications_evidence_EXE)){
		while($atps_list_qualifications_evidence_REC = mysqli_fetch_assoc($qu_atps_list_qualifications_evidence_EXE)){
			$record_id = ( int ) $atps_list_qualifications_evidence_REC['record_id'];
			$main_id = ( int ) $atps_list_qualifications_evidence_REC['main_id'];
			$qs_id = ( int ) $atps_list_qualifications_evidence_REC['qs_id'];
			$cat_id = ( int ) $atps_list_qualifications_evidence_REC['cat_id'];
			$evidence = $atps_list_qualifications_evidence_REC['evidence'];
			$qualification_id = ( int ) $atps_list_qualifications_evidence_REC['qualification_id'];
			
			


			$qualification_name = "";
			$qu_atps_list_qualifications_sel = "SELECT * FROM  `atps_list_qualifications` WHERE ((`atp_id` = $atp_id) AND (`qualification_id` = $qualification_id))";
			$qu_atps_list_qualifications_EXE = mysqli_query($KONN, $qu_atps_list_qualifications_sel);
			if(mysqli_num_rows($qu_atps_list_qualifications_EXE)){
				$atps_list_qualifications_DATA = mysqli_fetch_assoc($qu_atps_list_qualifications_EXE);
				$qualification_name = $atps_list_qualifications_DATA['qualification_code']. ' - ' .$atps_list_qualifications_DATA['qualification_name'];
			}
	
			$qs_title = "";
			$qs_description = "";
		$qu_quality_standards_sel = "SELECT * FROM  `quality_standards` WHERE `qs_id` = ".$qs_id;
		$qu_quality_standards_EXE = mysqli_query($KONN, $qu_quality_standards_sel);
		if(mysqli_num_rows($qu_quality_standards_EXE)){
			$quality_standards_DATA = mysqli_fetch_assoc($qu_quality_standards_EXE);
			$qs_title = $quality_standards_DATA['qs_title'];
			$qs_description = $quality_standards_DATA['qs_description'];
		}


		
		
			$cat_ref = "";
			$cat_description = "";
			$qu_quality_standards_cats_sel = "SELECT * FROM  `quality_standards_cats` WHERE `cat_id` = ".$cat_id;
			$qu_quality_standards_cats_EXE = mysqli_query($KONN, $qu_quality_standards_cats_sel);
			$quality_standards_cats_DATA;
			if(mysqli_num_rows($qu_quality_standards_cats_EXE)){
				$quality_standards_cats_DATA = mysqli_fetch_assoc($qu_quality_standards_cats_EXE);
				$cat_ref = $quality_standards_cats_DATA['cat_ref'];
				$cat_description = $quality_standards_cats_DATA['cat_description'];
			}
		?>
		

					<div class="dataElement data-list-view" id="f-<?=$record_id; ?>">
						<div class="dataView col-2">
							<div class="property"><?=lang("Standard"); ?></div>
							<div class="value"><?=$qs_title; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("Category REF"); ?></div>
							<div class="value"><?=$cat_ref; ?></div>
						</div>
						<div class="dataView col-2">
							<div class="property"><?=lang("Category"); ?></div>
							<div class="value"><?=$cat_description; ?></div>
						</div>
						
						<div class="dataView col-2">
							<div class="property"><?=lang("qualification"); ?></div>
							<div class="value"><?=$qualification_name; ?></div>
						</div>
<div class="dataView col-2">
	<div class="property"><?=lang("Evidence"); ?></div>
<?php
if( $evidence != '' ){
?>
	<div class="value"><a href="../<?=$POINTER; ?>uploads/<?=$evidence; ?>" target="_blank">View Evidence</a></div>
<?php
} else {
?>
	<div class="value">NA</div>
<?php
}
?>
</div>
					</div>

		<?php
		}
	}

?>
	</div>
</div>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->




	







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
				data     : { "form" : 'qualification_register', 'atp_id' : atpId, 'ops' : ops, 'rc_comment' : rc_comment  }, 
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