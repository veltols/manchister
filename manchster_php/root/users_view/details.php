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

<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("Employee_Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td">
					<div class="tblBtnsGroup">
						<a onclick="resetPass();"
							class="tableBtn tableBtnInfo"><?= lang("Reset_password", "AAR"); ?></a>
						<?php
						if ($is_active == 1) {
							?>
							<a onclick="updateUserStatus(0);"
								class="tableBtn tableBtnDanger"><?= lang("Deactivate_User", "AAR"); ?></a>
							<?php
						} else if ($is_active == 0) {
							?>
								<a onclick="updateUserStatus(1);"
									class="tableBtn tableBtnSuccess"><?= lang("Activate_User", "AAR"); ?></a>
							<?php
						}
						?>
					</div>


				</div>

			</div>

		</div>
	</div>
</div>

<script>

	function resetPass() {
		showModal('resetPassModal');
	}
	function updateUserStatus(nwStatus) {
		if (onCall == false) {
			onCall = true;
			var conf = confirm("Are you sure?");
			if (conf) {
				start_loader('article');
				$.ajax({
					type: 'POST',
					url: '<?= $POINTER; ?>update_users_list',
					dataType: "JSON",
					data: { 'employee_id': <?= $employee_id; ?>, 'is_active': nwStatus },
					success: function (responser) {
						onCall = false;
						end_loader('article');
						if (responser[0].success == true) {
							makeSiteAlert('suc', "User Updated");
							setTimeout(function () {
								window.location.reload();
							}, 1000);
						} else {
							makeSiteAlert('err', responser[0].message);
						}
					},
					error: function () {
						onCall = false;
						end_loader('article');
						makeSiteAlert('err', "General Error, please try later !");
					}
				});

			}
		}
	}
</script>