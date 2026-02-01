<script>


	function initTabs() {
		var tabsCount = 99;

		$('.tabTitle').each(function () {
			tabsCount++;
			thsExtraAct = '' + $(this).attr('extra-action');
			if (thsExtraAct == 'undefined') {
				thsExtraAct = '';
			} else {
				thsExtraAct = thsExtraAct + '();'
			}
			$(this).attr('id', 'tabTitle-' + tabsCount);
			$(this).attr('onclick', 'switchTab(' + tabsCount + ');' + thsExtraAct);

		});
		tabsCount = 99;
		$('.tabContent').each(function () {
			tabsCount++;
			$(this).attr('id', 'tabContent-' + tabsCount);
		});

		if (typeof tab !== 'undefined') {

			setTimeout(function () {
				$('#tabTitle-' + tab).click();
			}, 100);

		} else {
			$('#tabTitle-100').click();
		}


	}
	function switchTab(tID) {
		$('.tabTitleActive').removeClass('tabTitleActive');
		$('.tabBodyActive').removeClass('tabBodyActive');

		$('#tabTitle-' + tID).addClass('tabTitleActive');
		$('#tabContent-' + tID).addClass('tabBodyActive');
	}

	initTabs();
</script>