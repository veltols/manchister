<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Type"); ?></div>
				<div class="th"><?= lang("external_party_name"); ?></div>
				<div class="th"><?= lang("Subject"); ?></div>
				<div class="th"><?= lang("Description"); ?></div>
				<div class="th"><?= lang("information_shared"); ?></div>
				<div class="th"><?= lang("last_updated"); ?></div>
				<div class="th"><?= lang("updated_by"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $QS_DATA['communication_type_name']; ?></div>
				<div class="td"><?= $QS_DATA['external_party_name']; ?></div>
				<div class="td"><?= $QS_DATA['communication_subject']; ?></div>
				<div class="td"><?= $QS_DATA['communication_description']; ?></div>
				<div class="td"><?= $QS_DATA['information_shared']; ?></div>
			</div>

		</div>
	</div>
</div>





<?php

$communication_type_name = "";
$communication_type_name_ar = "";
$typeApp_id_1 = 0;
$typeApp_id_2 = 0;
$qu_m_communications_list_types_sel = "SELECT * FROM  `m_communications_list_types` WHERE `communication_type_id` = $communication_type_id";
$qu_m_communications_list_types_EXE = mysqli_query($KONN, $qu_m_communications_list_types_sel);
if (mysqli_num_rows($qu_m_communications_list_types_EXE)) {
	$m_communications_list_types_DATA = mysqli_fetch_assoc($qu_m_communications_list_types_EXE);
	$communication_type_id = (int) $m_communications_list_types_DATA['communication_type_id'];
	$communication_type_name = $m_communications_list_types_DATA['communication_type_name'];
	$communication_type_name_ar = $m_communications_list_types_DATA['communication_type_name_ar'];
	$typeApp_id_1 = (int) $m_communications_list_types_DATA['approval_id_1'];
	$typeApp_id_2 = (int) $m_communications_list_types_DATA['approval_id_2'];
}

$is_approved_1 = (int) $QS_DATA['is_approved_1'];
$is_approved_2 = (int) $QS_DATA['is_approved_2'];



if ($is_approved_1 == 0) {
	//require first approval
	//check if this is the user
	if ($typeApp_id_1 == $USER_ID) {
		//allow to approve
		?>

		<div class="tableContainer" id="appsListDtd">
			<div class="table">
				<div class="tableHeader">
					<div class="tr">
						<div class="th"><?= lang("Approve_Request"); ?></div>
					</div>
				</div>
				<div class="tableBody" id="appsListDt">

					<div class="tr levelRow" id="levelRow-sss">
						<div class="td">
							<?php
							if ($status_id == 1) {
								?>
								<a onclick="approveRequest_1();" target="_blank" class="dataActionBtn actionBtn"
									style="width: 20%;background: var(--info);">
									<span class="label"><?= lang("Approve"); ?></span>
								</a>
								<?php
							}
							?>

						</div>

					</div>

				</div>
			</div>
		</div>
		<script>
			function approveRequest_1() {
				$('.edit-communication_status_id').val(2);
				showModal('UpdateSttModal');
			}
		</script>
		<?php
	}

}


if ($is_approved_2 == 0) {

	if ($typeApp_id_2 == $USER_ID) {
		//allow to approve
		?>

		<div class="tableContainer" id="appsListDtd">
			<div class="table">
				<div class="tableHeader">
					<div class="tr">
						<div class="th"><?= lang("Approve_Request"); ?></div>
					</div>
				</div>
				<div class="tableBody" id="appsListDt">

					<div class="tr levelRow" id="levelRow-sss">
						<div class="td">
							<?php
							if ($status_id == 2) {
								?>
								<a onclick="approveRequest_2();" target="_blank" class="dataActionBtn actionBtn"
									style="width: 20%;background: var(--info);">
									<span class="label"><?= lang("Approve"); ?></span>
								</a>
								<?php
							}
							?>

						</div>

					</div>

				</div>
			</div>
		</div>
		<script>
			function approveRequest_2() {
				$('.edit-communication_status_id').val(3);
				showModal('UpdateSttModal');
			}
		</script>
		<?php
	}

}


?>