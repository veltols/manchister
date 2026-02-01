<?php

$page_title = $page_description = $page_keywords = $page_author = lang("EQA Live Assessment Observation Checklist");
$pageController = $POINTER . 'save_007sss';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$tab_id = (isset($_GET['tab_id'])) ? (int) test_inputs($_GET['tab_id']) : 0;

$q_form = '049';

$pageId = 500;
$asideIsHidden = true;
include("app/assets.php");
?>



<script>
	var rowsCount = 100;

	function remRow(idd) {
		$('#row-' + idd).remove();
		rowsCount++;
	}


	function addRow_assesor(recId) {
		var nwTr = '<tr id="row-' + rowsCount + '">' +
			'<input type="hidden" name="q_ids[]" value="' + recId + '" class="inputer" data-name="q_ids[]" data-req="1" data-type="int" data-den="">' +

			'<td><input style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></td>' +


			'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

			'</tr>';
		$('#assesorBody').append(nwTr);
		rowsCount++;
		initForms();
	}


	function addRow_learner(recId) {
		var nwTr = '<tr id="row-' + rowsCount + '">' +
			'<input type="hidden" name="q_ids[]" value="' + recId + '" class="inputer" data-name="q_ids[]" data-req="1" data-type="int" data-den="">' +

			'<td><input style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></td>' +
			'<td><input style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></td>' +


			'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

			'</tr>';
		$('#learnerBody').append(nwTr);
		rowsCount++;
		initForms();
	}


	function addRow_actions(recId) {
		var nwTr = '<tr id="row-' + rowsCount + '">' +
			'<input type="hidden" name="q_ids[]" value="' + recId + '" class="inputer" data-name="q_ids[]" data-req="1" data-type="int" data-den="">' +

			'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
			'<td><input style="width:100%;" type="text" rows="8" class="inputer has_date" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></td>' +


			'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

			'</tr>';
		$('#actionsBody').append(nwTr);
		rowsCount++;
		initForms();
	}

	function addRow_recommendations(recId) {
		var nwTr = '<tr id="row-' + rowsCount + '">' +
			'<input type="hidden" name="q_ids[]" value="' + recId + '" class="inputer" data-name="q_ids[]" data-req="1" data-type="int" data-den="">' +

			'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
			'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

			'</tr>';
		$('#recommendationsBody').append(nwTr);
		rowsCount++;
		initForms();
	}

</script>




<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->



	<!-- START OF FORMER COL -->
	<div class="former">

		<?php
		//Learner_ID_and_name
		$props = [
			'dataType' => 'hidden',
			'dataName' => 'atp_id',
			'dataDen' => '0',
			'dataValue' => $atp_id,
			'dataClass' => 'inputer',
			'isDisabled' => 0,
			'isReadOnly' => 0,
			'isRequired' => '1'
		];
		echo build_input(...$props);
		?>

		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('EQA Live Assessment Observation Checklist'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->

		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Assessors'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<table border="1" style="width: 100%;">
				<thead>
					<tr>

						<th style="width:45%;">Assessor Name</th>
						<th style="width:30%;"></th>
					</tr>
				</thead>
				<tbody id="assesorBody">
				</tbody>
				<tbody>
					<tr>
						<td colspan="3"><button onclick="addRow_assesor(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								Row</button></td>
					</tr>
				</tbody>
			</table>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->

		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Learners'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<table border="1" style="width: 100%;">
				<thead>
					<tr>

						<th style="width:45%;">Learner ID and Name</th>
						<th style="width:45%;">Cohort</th>
						<th style="width:30%;"></th>
					</tr>
				</thead>
				<tbody id="learnerBody">
				</tbody>
				<tbody>
					<tr>
						<td colspan="3"><button onclick="addRow_learner(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								Row</button></td>
					</tr>
				</tbody>
			</table>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->










































		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Observation Criteria'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->


			<table border="1" style="width: 100%;">

				<thead>
					<tr>

						<th style="width:45%;">Criteria</th>
						<th style="width:30%;">Fully Implemented</th>
						<th style="width:30%;">Comment</th>
					</tr>
				</thead>
				<tbody id="trainBody">
					<?php
					$rowsCountTrain = 4000;
					$qu_eqa_interim_028_sel = "SELECT * FROM  `eqa_interim_028`  ORDER BY `criteria_id` ASC";
					$qu_eqa_interim_028_EXE = mysqli_query($KONN, $qu_eqa_interim_028_sel);
					if (mysqli_num_rows($qu_eqa_interim_028_EXE)) {
						while ($eqa_interim_028_REC = mysqli_fetch_assoc($qu_eqa_interim_028_EXE)) {
							$criteria_id = (int) $eqa_interim_028_REC['criteria_id'];
							$is_main = (int) $eqa_interim_028_REC['is_main'];
							$criteria_text = "" . $eqa_interim_028_REC['criteria_text'];
							$answer = '';

							$rowsCountTrain++;
							if ($is_main == 1) {
								?>
								<tr id="row-<?= $rowsCountTrain; ?>" style="background:var(--primary);color: white;">
									<td colspan="3"><strong style="display: block;padding: 0.5em"><?= $criteria_text; ?></strong>
									</td>
								</tr>

								<?php
							} else {
								?>
								<tr id="row-<?= $rowsCountTrain; ?>">
									<input type="hidden" name="criteria_ids[]" value="<?= $criteria_id; ?>" class="inputer"
										data-name="criteria_ids[]" data-req="1" data-type="int" data-den="">

									<td><?= $criteria_text; ?></td>


									<td>

										<div class="formGroup formGroup-100">
											<select data-name="visit_type" data-type="text" data-den="100" data-req="1"
												class="inputer visit_type" data-from="<?= $POINTER; ?>get_yes_no_select">
												<option value="100" selected><?= lang("Please_Select"); ?></option>
												<option value="1">Yes</option>
												<option value="2">No</option>
											</select>
										</div>
									</td>

									<td><textarea style="width:100%;" rows="8" class="inputer" data-name="q_answers[]" data-req="1"
											data-type="text" data-den=""><?= $answer; ?></textarea></td>




								</tr>

								<?php
							}
						}
					}

					?>
				</tbody>
			</table>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->












		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Recommendations'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<table border="1" style="width: 100%;">
				<thead>
					<tr>

						<th style="width:45%;">Recommendation</th>
						<th style="width:5%;"></th>
					</tr>
				</thead>
				<tbody id="recommendationsBody">
				</tbody>
				<tbody>
					<tr>
						<td colspan="3"><button onclick="addRow_recommendations(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								Row</button></td>
					</tr>
				</tbody>
			</table>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->





		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('actions'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<table border="1" style="width: 100%;">
				<thead>
					<tr>

						<th style="width:45%;">action</th>
						<th style="width:20%;">Agreed Date</th>
						<th style="width:5%;"></th>
					</tr>
				</thead>
				<tbody id="actionsBody">
				</tbody>
				<tbody>
					<tr>
						<td colspan="3"><button onclick="addRow_actions(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								Row</button></td>
					</tr>
				</tbody>
			</table>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->







	</div>
	<!-- END OF FORMER COL -->









	<div class="describer">
		<div class="formActionBtns">

			<!-- <a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=100" class="revBtn actionBtn"
				id="thsFormBtns">
				<i class="fa-solid fa-file"></i>
				<span class="label"><?= lang("Request_Evidence"); ?></span>
			</a> -->
			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=<?= $tab_id; ?>" class="backBtn actionBtn"
				id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>
			<?php
			if (1 == 1) {
				?>
				<div class="saveBtn actionBtn" onclick="saveFormChanges('<?= $pageController; ?>');">
					<i class="fa-solid fa-save"></i>
					<span class="label"><?= lang("Save"); ?></span>
				</div>
				<?php
			}
			?>


		</div>

	</div>





	<!-- END OF THREE COL -->
</div>
<!-- END OF THREE COL -->



<script>
	function actionForm(atpId, ops) {
		var aa = confirm("Are you sure?");
		var rc_comment = $('#rc_comment').val();
		if (aa == true) {
			if (activeRequest == false) {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: '<?= $POINTER; ?>approve_atp_form',
					dataType: "JSON",
					method: "POST",
					data: { "form": 'sed_form', 'atp_id': atpId, 'ops': ops, 'rc_comment': rc_comment },
					success: function (RES) {

						var itm = RES[0];
						activeRequest = false;
						end_loader('article');
						makeSiteAlert('suc', '<?= lang("Data Saved"); ?>');
						setTimeout(function () {
							window.location.href = '<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>';
						}, 1000);
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


	function showRev() {
		$('#rc_comment').toggle();
		$('#revSubimt').toggle();
	}




</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>

<script>

	addRow_assesor(0);
	addRow_learner(0);
	addRow_recommendations(0);
	addRow_actions(0);
</script>