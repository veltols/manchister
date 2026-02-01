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



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th">&nbsp;</div>
				<div class="th">&nbsp;</div>
				<div class="th">&nbsp;</div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td">
					<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="assignTicket();"
						style="width: 50%;background: var(--info);">
						<span class="label"><?= lang("Assign_to_user"); ?></span>
					</div>
				</div>
				<?php
				if ($status_id == 1) {
					if ($assigned_to != 0) {
						?>
						<div class="td">
							<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="sttInProgress();"
								style="width: 50%;background: var(--danger);">
								<span class="label"><?= lang("Ticket_In_Progress"); ?></span>
							</div>
						</div>
						<div class="td">
							<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="markDone();" style="width: 50%;">
								<span class="label"><?= lang("Resolve_Ticket"); ?></span>
							</div>
						</div>
						<?php
					}
				} else if ($status_id == 2) {
					if ($assigned_to != 0) {
						//markDone
						?>
							<div class="td">
								<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="markDone();" style="width: 50%;">
									<span class="label"><?= lang("Resolve_Ticket"); ?></span>
								</div>
							</div>
						<?php
					}
				} else if ($status_id == 3) {
					if ($assigned_to != 0) {
						//reOpenTicket
						?>
								<div class="td">
									<div class="dataActionBtn actionBtn" id="thsFormBtns" onclick="reOpenTicket();"
										style="width: 50%;background: var(--warning);">
										<span class="label"><?= lang("ReOpen_Ticket"); ?></span>
									</div>
								</div>
						<?php
					}
				}


				?>


			</div>

		</div>
	</div>
</div>

<script>


</script>