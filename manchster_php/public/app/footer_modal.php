<div id="modalDarker" onclick="closeModal();"></div>


<!-- Modals START -->
<div class="modal" id="resetPassModal">
	<div class="modalHeader">
		<h1><?= lang("You_need_to_change_your_password", "AAR"); ?></h1>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="resetPassForm">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Type_your_new_password", "AAR"); ?></label>
					<input type="hidden" class="inputer employee_id_at_reset" data-name="employee_id" data-den="0"
						data-req="1" data-type="hidden" value="<?= $USER_ID; ?>">
					<input type="hidden" class="inputer employee_id_at_reset" data-name="is_pass" data-den="0"
						data-req="1" data-type="hidden" value="1">
					<input type="hidden" class="inputer log_remark" data-name="log_remark" data-den="0" data-req="1"
						data-type="hidden" value="password first change">



					<input type="text" class="inputer" data-name="employee_pass" data-den="" data-req="1"
						data-type="text_mixed">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-1">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('resetPassForm', '<?= $POINTER . 'update_users_list'; ?>');"><?= lang("Save", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->


		</div>
	</div>
</div>
<!-- Modals END -->
 
<script>

	function showModal(idd) {
		activeModal = idd;
		$('#modalDarker').addClass('modalShowed');
		$('#' + idd).addClass('modalShowed');
	}

	function closeModal() {
		$('#' + activeModal).removeClass('modalShowed');
		$('#modalDarker').removeClass('modalShowed');
		activeModal = '';
	}
</script>