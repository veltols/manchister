<div class="panelFooter">

	<div class="pagintationPerPage">
		<span>Records : </span>
		<select id="recordsPerPage">
			<option value="10" selected>10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
	</div>
	<div class="pagintationPages">
		<span class="goToFirstPage" onclick="goToFirstPage();"><i class="fas fa-angle-double-left"></i></span>
		<span class="goToPrePage" onclick="goToPrePage();"><i class="fas fa-chevron-left"></i></span>

		<div class="pageCounter">
			<span class="currentPage" id="currentPage">1</span> <span>-</span> <span id="totalPages"
				class="totalPages">11</span>
		</div>

		<span class="goToNextPage" onclick="goToNextPage();"><i class="fas fa-chevron-right"></i></span>
		<span class="goToLastPage" onclick="goToLastPage();"><i class="fas fa-angle-double-right"></i></span>
	</div>

</div>


<script>

	var currentDataViewClass = 'data-list-view';
	function changeDataView(nwClass) {
		if (currentDataViewClass != nwClass) {
			$('.' + currentDataViewClass).each(function () {
				$(this).addClass(nwClass);
				$(this).removeClass(currentDataViewClass);
			});
			$('#' + currentDataViewClass).removeClass('pageLinkActive');
			$('#' + nwClass).addClass('pageLinkActive');
			currentDataViewClass = nwClass;
			setCookie('defined-view', currentDataViewClass);
		}
	}

	function getCurrentDataView() {
		var userDefinedView = getCookie('defined-view');
		var res = '';
		if (userDefinedView != '') {
			res = userDefinedView;
			currentDataViewClass = userDefinedView;
		} else {
			res = 'data-list-view';
		}
		//highlight buttons
		$('#data-tiles-view').removeClass('pageLinkActive');
		$('#data-list-view').removeClass('pageLinkActive');



		$('#' + res).addClass('pageLinkActive');



		return res;
	}

	var currentPage = 1;
	var totalPages = 10;
	var activeRequest = false;
	function updatePanelPager(isCallApi) {
		recordsPerPage = parseInt($('#recordsPerPage').val());
		$('#totalPages').html(totalPages);
		$('#currentPage').html(currentPage);
		if (isCallApi == true) {
			callPageData();
		}
	}

	function changePageCount() {
		recordsPerPage = parseInt($('#recordsPerPage').val());
		updatePanelPager(true);
	}
	function goToLastPage() {
		currentPage = totalPages;
		updatePanelPager(true);
	}

	function goToFirstPage() {
		currentPage = 1;
		updatePanelPager(true);
	}

	function goToPrePage() {
		var nxtPage = currentPage - 1;
		if (nxtPage < 1) {
			currentPage = totalPages;
		} else {
			currentPage = nxtPage;
		}
		updatePanelPager(true);
	}
	function goToNextPage() {
		var nxtPage = currentPage + 1;
		if (nxtPage > totalPages) {
			currentPage = 1;
		} else {
			currentPage = nxtPage;
		}
		updatePanelPager(true);
	}
	function callPageData() {

		if (activeRequest == false) {
			activeRequest = true;
			start_loader('article');
			$.ajax({
				url: '<?= $pageDataController; ?>',
				dataType: "JSON",
				method: "POST",
				data: {
					"currentPage": currentPage, "recordsPerPage": recordsPerPage, "extra_id": <?php if (isset($extra_id)) {
						echo $extra_id;
					} else {
						echo 0;
					} ?> },
				success: function (RES) {
					doStuff(RES);
				},
				error: function () {
					activeRequest = false;
					end_loader('article');
					alert("Err-327657");
				}
			});
		}
	}

	function doStuff(stuff) {
		var itm = stuff[0];
		activeRequest = false;
		end_loader('article');
		totalPages = itm['totalPages'];
		updatePanelPager(false);
		if (itm['success'] == true) {
			bindData(itm['data']);
		}

	}
	function searchRecords() {
		var dt = $('#pageSearcher').val().trim();
		if (activeRequest == false) {
			activeRequest = true;
			start_loader('article');
			$.ajax({
				url: '<?= $pageDataController; ?>',
				dataType: "JSON",
				method: "POST",
				data: {
					"query_srch": dt, "currentPage": currentPage, "recordsPerPage": recordsPerPage, "extra_id": <?php if (isset($extra_id)) {
						echo $extra_id;
					} else {
						echo 0;
					} ?> },
				success: function (RES) {
					doStuff(RES);
				},
				error: function () {
					end_loader('article');
					alert("Err-327657");
				}
			});
		}


	};


	$('#recordsPerPage').on('change', function () {

		changePageCount();
	});

	<?php
	if (!isset($DOnotInitialRequest)) {
		?>
		updatePanelPager(true);
		<?php
	} else {
		?>
		activeRequest = false;
		end_loader();
		<?php
	}
	?>

</script>