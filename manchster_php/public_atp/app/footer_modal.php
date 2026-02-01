<div id="modalDarker"><img src="<?= $POINTER; ?>../uploads/loader.gif" alt="loading" /></div>

<script>
	var activeModal = '';
	var isModalOverrided = false;
	var isModOpen = false;

	function showModalDarker() {
		$('#modalDarker').addClass("modalDarkerShowed");
		setTimeout(function () {
			$('#modalDarker').addClass("modalDarkerShowed01");
		}, 500);
	}
	function hideModalDarker() {
		$('#modalDarker').removeClass("modalDarkerShowed01");
		setTimeout(function () {
			$('#modalDarker').removeClass("modalDarkerShowed");
		}, 500);
	}

	function showModal(idd) {
		isModOpen = true;
		activeModal = idd;
		$('#modalDarker').addClass("modalDarkerShowed");
		setTimeout(function () {
			$('#modalDarker').addClass("modalDarkerShowed01");
			setTimeout(function () {
				$('#' + activeModal).addClass("modalShowed");
			}, 500);
		}, 500);
	}

	function closeModal() {
		if (isModOpen == true) {
			isModOpen = false;
			if (activeModal != '') {
				$('#' + activeModal).removeClass("modalShowed");
				$('#modalDarker').removeClass("modalDarkerShowed01");
				setTimeout(function () {
					$('#modalDarker').removeClass("modalDarkerShowed");
					activeModal = '';
				}, 500);
			}
		}
	}
</script>