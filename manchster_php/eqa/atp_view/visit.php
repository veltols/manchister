<?php

$logsController = $POINTER . 'get_atps_apps';



?>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Application"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt2">


			<div class="tr levelRow" id="levelRow-1">
				<div class="td">IQC External Quality Assurance Visit Planner</div>
				<div class="td"><a href="<?= $POINTER; ?>atps/view/008/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>"
						class="tableActionBtn"><?= lang("Action"); ?></a></div>
			</div>

			<div class="tr levelRow" id="levelRow-2">
				<div class="td">EQA-ATP Internal Report for Accreditation</div>
				<div class="td"><a href="<?= $POINTER; ?>atps/view/004/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>"
						class="tableActionBtn"><?= lang("Action"); ?></a></div>
			</div>


			<div class="tr levelRow" id="levelRow-3">
				<div class="td">Internal-ATP Approval Site Inspection Checklist</div>
				<div class="td"><a href="<?= $POINTER; ?>atps/view/014/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>"
						class="tableActionBtn"><?= lang("Action"); ?></a></div>
			</div>


			<div class="tr levelRow" id="levelRow-4">
				<div class="td">EQA Report on ATP Accreditation</div>
				<div class="td"><a href="<?= $POINTER; ?>atps/view/003/?atp_id=<?= $atp_id; ?>&tab_id=<?= $thsTab; ?>"
						class="tableActionBtn"><?= lang("Action"); ?></a></div>
			</div>






		</div>
	</div>
</div>

<script>



</script>