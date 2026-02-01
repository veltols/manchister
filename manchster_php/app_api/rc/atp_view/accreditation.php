<?php

	$logsController = $POINTER.'accredit_atp';
	
	
	
?>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?=lang("TEST_Accreditation"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">
			
			<div class="tr levelRow" id="levelRow-sss">
							<div class="td">
								
		<div class="submitBtn actionBtn" id="thsFormBtns" onclick="accreditAtp();" style="width: 20%;">
			<i class="fa-solid fa-check"></i>
			<span class="label"><?=lang("Accredit ATP"); ?></span>
		</div>

							</div>
					
			</div>
		
		</div>
	</div>
</div>
<script>
	
function accreditAtp(){
	if( onCall == false ){
		onCall = true;
		start_loader('article');
		$.ajax({
			type: 'POST', 
			url: '<?=$logsController; ?>', 
			dataType : "JSON", 
			data: {'atp_id': <?=$atp_id; ?>}, 
			success: function(responser){
				onCall = false;
				end_loader('article');
				if( responser[0].success == true ){
					makeSiteAlert('suc', "ATP accredited - system test");
				} else {
					makeSiteAlert('err', "General Error, please try later !!");
				}
			},
			error: function(){
				onCall = false;
				end_loader('article');
				makeSiteAlert('err', "General Error, please try later !");
			}
		});
	}
}
	
</script>

