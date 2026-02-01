<?php
include('index_nav.php');
?>




<?php
$totalLeaveBalance = 0;
$RemainingLeaveBalance = 0;
$totalHrRequests = 0;
$totalPending = 0;


?>



<div class="kpiContainer">
	<div class="kpiTitle">
		<?= lang("HR", ""); ?>
	</div>
	<div class="kpiBoxes">

		<a href="<?= $DIR_tickets; ?>?stt=0" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("Total_Leaves_Balance", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalLeaveBalance; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi">
			<div class="dataName"><?= lang("Remaining_Leaves", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $RemainingLeaveBalance; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=1" class="boxKpi boxKpiX2">
			<div class="dataName"><?= lang("total_HR_Requests", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalHrRequests; ?>">0</div>
		</a>
		<a href="<?= $DIR_tickets; ?>?stt=3" class="boxKpi">
			<div class="dataName"><?= lang("Pending_Approval", "AAR"); ?></div>
			<div class="dataValue count" data-stop="<?= $totalPending; ?>">0</div>
		</a>
		
	</div>
</div>



<div class="row">
	
	<div class="col-1" >
		<div class="overAllViewer">
			<h1><?= lang("Latest_Annoucements", ""); ?></h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("Title"); ?></div>
							<div class="th"><?= lang("description"); ?></div>
						</div>
					</div>
					<div class="tableBody" id="">
						<?php

						$qu_hr_documents_sel = "SELECT * FROM  `hr_documents` WHERE `document_type_id` = 3 ORDER BY `document_id` DESC LIMIT 5";
						$qu_hr_documents_EXE = mysqli_query($KONN, $qu_hr_documents_sel);
						if (mysqli_num_rows($qu_hr_documents_EXE)) {
							while ($hr_documents_REC = mysqli_fetch_assoc($qu_hr_documents_EXE)) {
								$document_id = (int) $hr_documents_REC['document_id'];
								$document_title = $hr_documents_REC['document_title'];
								$document_description = $hr_documents_REC['document_description'];
								$document_attachment = $hr_documents_REC['document_attachment'];
								$document_type_id = (int) $hr_documents_REC['document_type_id'];
								?>

								<div class="tr levelRow" id="ticket-<?= $document_id; ?>">
									<div class="td"><?= $document_title; ?></div>
									<div class="td"><?= $document_description; ?></div>
								</div>
								<?php
							}
						}

						?>
					</div>
				</div>
			</div>

		</div>
	</div>
	
</div>

<br><br><br>


<?php
include("app/footer.php");
?>

<script src="<?= $POINTER; ?>../public/assets/js/chart.umd.min.js"></script>