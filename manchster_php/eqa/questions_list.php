<?php
	$page_title=$page_description=$page_keywords=$page_author= lang("Questions_List");
	
	$pageDataController = $POINTER."get_questions_list";
	
	$pageId = 600;
	include("app/assets.php");
?>
<!-- MAIN MID VIEW -->
<div class="articleContainer">
<!-- MAIN MID VIEW -->


<div class="pageMainTitle">
	<h1><?=$page_title; ?></h1>
	<div class="breadCrumbs">
		<a href="<?=$DIR_dashboard; ?>" class="hasLink"><?=lang('Home', 'ARR'); ?></a>
		<span>\</span>
		<a href="<?=$DIR_questions; ?>" class="hasLink"><?=lang('Questions', 'ARR'); ?></a>
		<span>\</span>
		<a><?=lang('List', 'ARR'); ?></a>
	</div>
</div>


<div class="pageOptions">
	<div class="pageOptionsButtons">
		<a class="pageLink hasTooltip" href="<?=$POINTER; ?>questions/new/?stage=1">
			<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?=lang("New_Question"); ?></div>
		</a>

		<div class="vr"></div>

		<a class="pageLink hasTooltip" onclick="changeDataView('data-list-view');" id="data-list-view">
			<i class="fa-solid fa-list"></i>
			<div class="tooltip"><?=lang("show list"); ?></div>
		</a>

		<a class="pageLink hasTooltip" onclick="changeDataView('data-tiles-view');" id="data-tiles-view">
			<i class="fa-solid fa-table-cells"></i>
			<div class="tooltip"><?=lang("show tiles"); ?></div>
		</a>
		
	</div>
	<div class="pageSearchForm">
		
		<input type="text" id="pageSearcher" placeholder="<?=lang("Search"); ?>">
		<a class="pageLink pageLinkActive" onclick="searchRecords();">
			<i class="fa-solid fa-magnifying-glass"></i>
		</a>

	</div>
</div>




<div class="dataContainer" id="listData"></div>


<script>
async function bindData( data ) {
	userDefinedClass = await getCurrentDataView();
	$('#listData').html('<?=lang(''); ?>');
	var listCount = 0;
	
	for( i = 0 ; i < data.length ; i++ ){
			var btns = '';

				// btns += '<a href="<?=$POINTER; ?>questions/view/?atp_id=' + data[i]["atp_id"] + '" class="dataActionBtn hasTooltip">' + 
				// 		'	<i class="fa-regular fa-eye"></i>' + 
				// 		'	<div class="tooltip"><?=lang("Details"); ?></div>' + 
				// 		'</a>';


						


			//btns= '---';
			var dt = '<div class="dataElement ' + userDefinedClass + '" id="levelRow-' + data[i]["atp_id"] + '">' + 
					'	<div class="dataView col-1">' + 
					'		<div class="property"><?=lang("Question"); ?></div>' + 
					'		<div class="value">' + data[i]["q_text"] + '</div>' + 
					'	</div>' +
					'	<div class="dataView col-3">' + 
					'		<div class="property"><?=lang("added_date"); ?></div>' + 
					'		<div class="value">' + data[i]["added_date"] + '</div>' + 
					'	</div>' + 
					'	<div class="dataView col-3">' + 
					'		<div class="property"><?=lang("added_by"); ?></div>' + 
					'		<div class="value">' + data[i]["added_name"] + '</div>' + 
					'	</div>' + 
					'	<div class="dataOptions">' + 
					btns + 
					'	</div>' + 
					'</div>';
					
			$('#listData').append( dt );
			listCount++;
	}
	
	if( listCount == 0 ){
		$('#listData').html( '<div class="noData"><img src="<?=$POINTER; ?>../uploads/no_data.png" alt="noData" /><?=lang("No_records_found"); ?></div>' );
	}
	changeDataView(currentDataViewClass);
}


function sendRegEmail( atpId ){
	var aa = confirm('<?=lang("send_Email_to_Atp?", "AAR"); ?>');
	if( aa ){
		if( atpId != 0 ){
			activeRequest = true;
			start_loader('article');
			$.ajax({
				url      : '<?=$POINTER; ?>send_atp_init_email', 
				dataType : "JSON", 
				method   : "POST", 
				data     : { "atp_id" : atpId }, 
				success: function( RES ){
					activeRequest = false;
					end_loader('article');
					thsDt = RES[0];
					if( thsDt['success'] == true ){
						makeSiteAlert('suc', '<?=lang("Email_Sent_Successfully", "AAR"); ?>');
						$('#emBtn-' + atpId).remove();
						$('#stt-' + atpId).html('<?=lang("Email_Sent", "AAR"); ?>');
					} else {
						alert("Failed to load data - 8787");
					}
				}, 
				error: function(res){
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


<!-- MAIN MID VIEW -->
</div>
<!-- MAIN MID VIEW -->



<?php
	include("app/footer.php");
?>