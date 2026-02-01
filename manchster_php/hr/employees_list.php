<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Users_List");

$pageDataController = $POINTER . "get_employees_list";

$addNewUserController = $POINTER . 'add_employees_list';

$pageId = 700;
$subPageId = 200;
include("app/assets.php");
?>


<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('employees_list_nav.php');
		?>
	</div>
	<div class="pageOptions">

	</div>
</div>


<!--div class="employeeWelcome">
	<div class="employeeMessages">
		<h1><?= $page_title; ?></h1>
	</div>

</div-->



<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("IQC_ID"); ?></div>
				<div class="th"><?= lang("Employee"); ?></div>
				<div class="th"><?= lang("Email"); ?></div>
				<div class="th"><?= lang("Department"); ?></div>
				<div class="th"><?= lang("Designation"); ?></div>
				<div class="th"><?= lang("Options"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="listData"></div>
	</div>
</div>




<script>
	async function bindData(data) {

		$('#listData').html('<?= lang(''); ?>');
		var listCount = 0;

		for (i = 0; i < data.length; i++) {
			var isNew = getInt(data[i]["is_new"]);

			var btns = '';
			btns += '<a href="<?= $POINTER; ?>employees/view/?employee_id=' + data[i]["employee_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			var rowBadge = '';
			if (isNew == 1) {
				rowBadge = '<div class="rowBadge"></div>'
			}
			//btns= '---';
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["employee_id"] + '">' +

				'	<div class="td">' + data[i]["employee_no"] + '</div>' +
				'	<div class="td">' + rowBadge + data[i]["employee_name"] + '</div>' +
				'	<div class="td">' + data[i]["employee_email"] + '</div>' +
				'	<div class="td">' + data[i]["department_name"] + '</div>' +
				'	<div class="td">' + data[i]["designation_name"] + '</div>' +
				'	<div class="td">' +
				btns +
				'	</div>' +
				'</div>';

			$('#listData').append(dt);
			listCount++;
		}

		if (listCount == 0) {
			$('#listData').html('<div class="noData"><img src="<?= $POINTER; ?>../uploads/no_data.png" alt="noData" /><?= lang("No_records_found"); ?></div>');
		}

	}
</script>




<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>