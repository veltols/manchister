<?php

	$logsController = $POINTER.'get_atps_apps';
	
	
	
?>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?=lang("Application"); ?></div>
				<!--div class="th"><?=lang("status"); ?></div-->
				<!--div class="th"><?=lang("submission_start"); ?></div-->
				<!--div class="th"><?=lang("submitted_date"); ?></div-->
				<div class="th"><?=lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt33"></div>
	</div>
</div>

<script>
function bindthsAppsData33( data ){
	
	$('#appsListDt33').html('');
	var listCount = 0;
	/*
	for( i = 0 ; i < data.length ; i++ ){
		var btns = '';
		
			var is_show_view = parseInt( data[i]["is_show_view"] );
			var app_type = data[i]["app_type"];

            //go to app page
            if( is_show_view == 1 ){
                btns = '<a href="<?=$POINTER; ?>atps/view/init/?atp_id=<?=$atp_id; ?>" class="tableActionBtn"><?=lang("Details"); ?></a>';
            }

            if( btns == '' ){
                btns = 'NA';
            }
			var linker = 'init';
			if( app_type == 1 ){
				linker = 'init';
			} else if( app_type == 2 ){
				linker = 'reg_req';
			} else if( app_type == 3 ){
				linker = 'faculty_details';
			} else if( app_type == 4 ){
				linker = '006';
			} else if( app_type == 5 ){
				linker = 'compliance';
			} else if( app_type == 6 ){
				linker = 'qip';
			} else if( app_type == 7 ){
				linker = 'eqa_visit';
			} else if( app_type == 8 ){
				linker = 'qualification_reg';
			}
			
			var linkBtn = '	<div class="td"><a href="<?=$POINTER; ?>atps/view/' + linker + '/?atp_id=<?=$atp_id; ?>" class="tableActionBtn"><?=lang("Action"); ?></a></div>';
			if( linker == '' ){
				linkBtn = '';
			}
			var thsStt = data[i]["form_status"];
			var thsBG    = 'yellow';
			var thsColor = 'yellow';
			appName = data[i]["app_name"];

			
			if( app_type == 4 ){

			}
	}
	*/
	app_type = 4;
				appName = 'IQC External Quality Assurance Visit Planner';
				linker = '008';
				var linkBtn = '	<div class="td"><a href="<?=$POINTER; ?>atps/view/' + linker + '/?atp_id=<?=$atp_id; ?>" class="tableActionBtn"><?=lang("Action"); ?></a></div>';
			var dt = '<div class="tr levelRow" id="levelRow-' + app_type + '">' + 
					'	<div class="td">' + appName + '</div>' + 
				//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
				//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
				//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
					linkBtn + 
					'</div>';
					
			$('#appsListDt33').prepend( dt );
			listCount++;
			
	if( listCount == 0 ){
		$('#appsListDt33').html( '<div class="noData"><img src="<?=$POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?=lang("No_records_found"); ?></div>' );
	}
}

function getApps33(){
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
				if( responser[0].success == true && responser[0].data){
					bindthsAppsData33(responser[0].data);
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

