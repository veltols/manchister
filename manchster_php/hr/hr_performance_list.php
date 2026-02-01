<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Performance_List");



//hr_performance
$pageDataController = $POINTER . "get_hr_performance_list";
$addNewController = $POINTER . 'add_hr_performance_list';
$updateController = $POINTER . 'update_hr_performance_list';



$pageId = 550;
$subPageId = 700;
include("app/assets.php");
?>




<div class="pageHeader">
	<div class="pageTitle">
		<h1><?= $page_title; ?></h1>
	</div>
	<div class="pageNav">
		<?php
		include('requests_list_nav.php');
		?>
	</div>

	<div class="pageOptions">
		<a class="pageLink" onclick="addNewPerformance();">
			<i class="fa-solid fa-plus"></i>
			<div class="linkTxt"><?= lang("Add_New"); ?></div>
		</a>
	</div>
</div>




<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Employee_name"); ?></div>
				<div class="th"><?= lang("performance_object"); ?></div>
				<div class="th"><?= lang("performance_kpi"); ?></div>
				<div class="th"><?= lang("remarks"); ?></div>
				<div class="th"><?= lang("added_date"); ?></div>
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
			var btns = '';


			btns += '<a onclick="getData(' + data[i]["performance_id"] + ');" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("View"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="dataRow-' + data[i]["performance_id"] + '">' +
				'	<div class="td row-performance_id">' + data[i]["performance_id"] + '</div>' +
				'	<div class="td row-employee_name">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td row-performance_object">' + data[i]["performance_object"] + '</div>' +
				'	<div class="td row-performance_kpi">' + data[i]["performance_kpi"] + '</div>' +
				'	<div class="td row-performance_remark">' + data[i]["performance_remark"] + '</div>' +
				'	<div class="td row-added_date">' + data[i]["added_date"] + '</div>' +
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


	function getData(performance_id) {

		if (performance_id != 0) {
			//get data from server
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $pageDataController; ?>',
					dataType: "JSON",
					method: "POST",
					data: { "performance_id": performance_id },
					success: function (RES) {
						var itm = RES[0];
						activeRequest = false;
						end_loader('article');
						if (itm['success'] == true) {
							bindViewData(itm['data']);
						}
					},
					error: function () {
						activeRequest = false;
						end_loader('article');
						alert("Err-327657");
					}
				});
			}
		}

	}

	function bindViewData(response) {
		//data[i]["performance_id"]
		resetForm('AddNewForm');
		$('.new-performance_id').val(response[0].performance_id);
		$('.new-performance_object').val(response[0].performance_object);
		$('.new-performance_kpi').val(response[0].performance_kpi);
		$('.new-performance_remark').val(response[0].performance_remark);
		$('.new-employee_id').val(response[0].employee_id);
		$('.new-added_date').val(response[0].added_date);
		$('.new-added_by').val(response[0].added_by);


		$('#AddNewModal .modalHeader h1').text('View Performance Details');
		$('#addNewPerformanceBtn').hide();
		showModal('AddNewModal');
	}

	function addNewPerformance() {
		$('#AddNewModal .modalHeader h1').text('Add New Performance');
		resetForm('AddNewForm');
		$('.new-performance_id').val(1);
		$('.new-added_date').val('<?= date('Y-m-d'); ?>');
		$('#addNewPerformanceBtn').show();
		showModal('AddNewModal');
	}
</script>





<?php
include("../public/app/footer_records.php");
?>


<?php
include("app/footer.php");
?>



<!-- Modals START -->
<div class="modal modal-lg" id="AddNewModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="AddNewForm">

			<input type="hidden" class="inputer new-performance_id" data-name="performance_id" data-den="" data-req="1"
				value="0" data-type="hidden">
			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Employee", "AAR"); ?></label>
					<select class="inputer new-employee_id" data-name="employee_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `employee_id`, CONCAT(`first_name`, ' ', `last_name`) AS `namer` FROM  `employees_list` WHERE ( (`is_deleted` = 0) AND (`is_hidden` = 0) ) ORDER BY `employee_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$emp_id = (int) $SEL_REC['employee_id'];
								$namer = $SEL_REC['namer'];
								?>
								<option value="<?= $emp_id; ?>"><?= $namer; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("added_date", "AAR"); ?></label>
					<input type="text" class="inputer new-added_date" data-name="added_date" data-den="" data-req="0"
						value="<?= date('Y-m-d'); ?>" data-type="date" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("performance_object", "AAR"); ?></label>
					<textarea type="text" class="inputer new-performance_object" data-name="performance_object"
						data-den="" data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("performance_kpi", "AAR"); ?></label>
					<textarea type="text" class="inputer new-performance_kpi" data-name="performance_kpi" data-den=""
						data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->






			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer new-performance_remark" data-name="performance_remark"
						data-den="" data-req="1" data-type="text" rows="3"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2" id="addNewPerformanceBtn">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('AddNewForm', '<?= $addNewController; ?>');"><?= lang("Create", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeModal();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->






<script>
	function afterFormSubmission() {
		closeModal();
		goToFirstPage();
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
