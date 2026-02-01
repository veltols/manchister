<?php

$reqListController = $POINTER . 'get_info_request_list';



?>


<div class="tableContainer" id="appsListDtd" style="width: 95%;">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Application"); ?></div>
				<!--div class="th"><?= lang("status"); ?></div-->
				<!--div class="th"><?= lang("submission_start"); ?></div-->
				<!--div class="th"><?= lang("submitted_date"); ?></div-->
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="formsList01"></div>
	</div>
</div>
<div><br><br><br><br></div>


<script>


	function loadForms02() {
		var dt = linker = linkBtn = '';

		$('#formsList01').html('');
		appName = 'Evidence Collection Log';
		linker = '007';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-2">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';
		appName = 'IQC External Quality Assurance Visit Planner';
		linker = '008';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-3">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'Internal Remote Activity Feedback Form';
		linker = '006';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'Assessors/Trainer Interview Questions';
		linker = '017';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'IQA Interview Questions';
		linker = '018';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'Learner Interview Questions';
		linker = '019';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'Lead IQA Interview Questions';
		linker = '020';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'ATP Approval Site Checklist';
		linker = '014';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'Teaching and learning Observation Record';
		linker = '049';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';

		appName = 'EQA Live Assessment Observation Checklist';
		linker = '028';
		linkBtn = '	<div class="td"><a href="<?= $POINTER; ?>atps/view/' + linker + '/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>" class="tableActionBtn"><?= lang("Action"); ?></a></div>';
		dt += '<div class="tr levelRow" id="levelRow-1">' +
			'	<div class="td">' + appName + '</div>' +
			//	'	<div class="td">' + data[i]["app_status"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submission_start"] + '</div>' + 
			//	'	<div class="td">' + data[i]["submitted_date"] + '</div>' + 
			linkBtn +
			'</div>';


		$('#formsList01').prepend(dt);
	}

	loadForms02();
</script>