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
				<div class="td"><?= $QS_DATA['ss_description']; ?></div>
				<div class="td"><?= $QS_DATA['added_by']; ?></div>
				<div class="td"><?= $QS_DATA['ss_added_date']; ?></div>
			</div>

		</div>
	</div>
</div>



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Attachment"); ?></div>
				<div class="th"><?= lang("Result"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td">

					<?php

					if ($QS_DATA['ss_attachment'] != 'no-img.png') {
						?>
						<a href="<?= $POINTER; ?>../uploads/<?= $QS_DATA['ss_attachment']; ?>" target="_blank"
							class="dataActionBtn actionBtn" style="width: 50%;">
							<span class="label"><?= lang("View_Attachment"); ?></span>
						</a>
						<?php
					}

					?>
				</div>
				<div class="td">

					<?php
					$thsRes = '' . $QS_DATA['ss_result_attachment'];
					if ($thsRes != '') {
						?>
						<a href="<?= $POINTER; ?>../uploads/<?= $thsRes; ?>" target="_blank" class="dataActionBtn actionBtn"
							style="width: 50%;">
							<span class="label"><?= lang("View_Result"); ?></span>
						</a>
						<?php
					}

					?>
				</div>

			</div>

		</div>
	</div>
</div>





<?php

if ($sent_to_id == $USER_ID) {
	if ($status_id != 3) {
		?>
		<div class="tableContainer" id="appsListDtd">
			<div class="table">
				<div class="tableHeader">
					<div class="tr">
						<div class="th"><?= lang("Options"); ?></div>
					</div>
				</div>
				<div class="tableBody" id="appsListDt">

					<div class="tr levelRow" id="levelRow-sss">
						<div class="td">
							<?php
							if ($status_id == 1) {
								?>
								<a onclick="updateRequestStatus(2);" target="_blank" class="dataActionBtn actionBtn"
									style="width: 20%;background: var(--warning);">
									<span class="label"><?= lang("Mark_In_Progress"); ?></span>
								</a>
								<?php
							} else if ($status_id == 2) {
								?>
									<a onclick="updateRequestStatus(3);" target="_blank" class="dataActionBtn actionBtn"
										style="width: 20%;background: var(--info);">
										<span class="label"><?= lang("Finish"); ?></span>
									</a>
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
}

?>

<script>
	function updateRequestStatus(stt) {
		$('.edit-status_id').val(stt);
		if (stt == 2) {
			$('#ss_result_attachment').hide();
			$('.edit-ss_result_attachment').attr('data-req', '0');
		} else {
			$('#ss_result_attachment').show();
			$('.edit-ss_result_attachment').attr('data-req', '1');
		}
		showModal('UpdateSttModal');
	}
</script>