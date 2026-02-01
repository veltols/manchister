<?php

$logsController = $POINTER . 'get_atps_apps';



?>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Category"); ?></div>
				<div class="th"><?= lang("Description"); ?></div>
				<div class="th"><?= lang("assigned_by"); ?></div>
				<div class="th"><?= lang("assigned_date"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $QS_DATA['category_name']; ?></div>
				<div class="td"><?= $QS_DATA['ticket_description']; ?></div>
				<div class="td"><?= $QS_DATA['added_by']; ?></div>
				<div class="td"><?= $QS_DATA['ticket_added_date']; ?></div>
			</div>

		</div>
	</div>
</div>



<?php

if ($QS_DATA['ticket_attachment'] != 'no-img.png') {
	?>
	<div class="tableContainer" id="appsListDtd">
		<div class="table">
			<div class="tableHeader">
				<div class="tr">
					<div class="th"><?= lang("ticket_attachment"); ?></div>
				</div>
			</div>
			<div class="tableBody" id="appsListDt">

				<div class="tr levelRow" id="levelRow-sss">
					<div class="td">

						<a href="<?= $POINTER; ?>../uploads/<?= $QS_DATA['ticket_attachment']; ?>" target="_blank"
							class="dataActionBtn actionBtn" style="width: 20%;">
							<span class="label"><?= lang("View_Attachment"); ?></span>
						</a>
					</div>

				</div>

			</div>
		</div>
	</div>

	<?php
}

?>



<?php
/*
if ($status_id != 3) {
	?>
	<div class="tableContainer" id="appsListDtd">
		<div class="table">
			<div class="tableHeader">
				<div class="tr">
					<div class="th"><?= lang("Update_ticket_status"); ?></div>
				</div>
			</div>
			<div class="tableBody" id="appsListDt">

				<div class="tr levelRow" id="levelRow-sss">
					<div class="td">
						<?php
						if ($status_id == 1) {
							//to-do
							?>
							<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="updateTicketStatus(2);"
								style="width: 20%;">
								<span class="label"><?= lang("Ticket_In_Progress"); ?></span>
							</div>
							<?php
						} else if ($status_id == 2) {
							//in progress
							?>
								<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="updateTicketStatus(3);"
									style="width: 20%;">
									<span class="label"><?= lang("Mark_as_done"); ?></span>
								</div>
							<?php
						} else if ($status_id == 3) {
							//Done
							?>
							<?php
						}
						?>

					</div>

				</div>

			</div>
		</div>
	</div>

	<?php
}
*/
?>
<script>


</script>