<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Name"); ?></div>
				<div class="th"><?= lang("Email"); ?></div>
				<div class="th"><?= lang("Department"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $first_name . " " . $last_name; ?></div>
				<div class="td"><?= $employee_email; ?></div>
				<div class="td"><?= $department_name; ?></div>
			</div>

		</div>
	</div>
</div>



<?php
//get user status
$is_active = 0;
$qu_users_list_sel = "SELECT `is_active` FROM  `users_list` WHERE `user_id` = $employee_id LIMIT 1";
$qu_users_list_EXE = mysqli_query($KONN, $qu_users_list_sel);
if (mysqli_num_rows($qu_users_list_EXE)) {
	$users_list_DATA = mysqli_fetch_assoc($qu_users_list_EXE);
	$is_active = (int) $users_list_DATA['is_active'];
}


?>
<div class="tableContainer" style="width: 50%;">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("IT Requests"); ?></div>
				<div class="th">&nbsp;</div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">
			<?php
			if ($is_active == 0) {
				?>
				<div class="tr levelRow" id="levelRow-sss">
					<div class="td"><?= lang("Send_Activation_Request"); ?></div>
					<div class="td">
						<div class="tblBtnsGroup">
							<a onclick="sendActReq();" class="dataActionBtn hasTooltip">
								<i class="fa-regular fa-folder-open"></i>
								<div class="tooltip"><?= lang("Send_Activation_Request"); ?></div>
							</a>
						</div>
					</div>
				</div>
				<?php
			} else if ($is_active == 1) {
				?>
					<div class="tr levelRow" id="levelRow-sss">
						<div class="td"><?= lang("Send_Deactivation_Request"); ?></div>
						<div class="td">
							<div class="tblBtnsGroup">
								<a onclick="sendDeReq();" class="dataActionBtn tableBtnDanger ionBtnInfo hasTooltip">
									<i class="fa-solid fa-times"></i>
									<div class="tooltip"><?= lang("Send_Deactivation_Request"); ?></div>
								</a>
							</div>
						</div>
					</div>
				<?php
			}
			?>

		</div>
	</div>
</div>


<script>
	function sendActReq() {
		resetForm('ItReqForm');
		$('.edit_ticket_description').val('Employee Activation Request, please activate employee :  <?= $first_name; ?> <?= $last_name; ?> (<?= $employee_no; ?>)');
		showModal('empActModal');
	}
	function sendDeReq() {
		resetForm('ItReqForm');
		$('.edit_ticket_description').val('Employee Deactivation Request, please deactivate employee :  <?= $first_name; ?> <?= $last_name; ?> (<?= $employee_no; ?>)');
		showModal('empActModal');
	}
</script>