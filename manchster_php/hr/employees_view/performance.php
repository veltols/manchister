<?php

$perCont = $POINTER . 'get_hr_performance_list';



?>



<div class="tableContainer" id="">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("performance_object"); ?></div>
				<div class="th"><?= lang("performance_kpi"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
				<div class="th"><?= lang("added_date"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="perDataList"></div>
	</div>
</div>

<script>

	function bindPerData(data) {
		$('#perDataList').html('');
		var listCount = 0;
		for (i = 0; i < data.length; i++) {
			var btns = '';

			var dt = '<div class="tr levelRow" id="levelRow-sss">' +
				'	<div class="td row-performance_id">' + data[i]["performance_id"] + '</div>' +
				'	<div class="td row-performance_object">' + data[i]["performance_object"] + '</div>' +
				'	<div class="td row-performance_kpi">' + data[i]["performance_kpi"] + '</div>' +
				'	<div class="td row-performance_remark">' + data[i]["performance_remark"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
				'</div>';

			$('#perDataList').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#perDataList').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><br><?= lang("No_records_found"); ?></div>');
		}
	}

	function getPerformance() {
		if (onCall == false) {
			onCall = true;
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $perCont; ?>',
				dataType: "JSON",
				data: { 'employee_id': <?= $employee_id; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindPerData(responser[0].data);
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
</script>