<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Teaching and Learning Observation Form");
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


<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->



	<!-- START OF FORMER COL -->
	<div class="former">

		<?php
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
			<h2 class="formPanelTitle"><?= lang('Teaching and Learning Observation Form'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->

			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Instructor_Information'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



			<div class="formGroup formGroup-50">
				<label><?= lang("Instuctor_name"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_1',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer sed_1',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>

			<div class="formGroup formGroup-50">
				<label><?= lang("Course/Module"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'sed_1',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer sed_1',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'isRequired' => '0',
					'dir' => 'ltr'
				];
				echo build_input(...$props);
				?>
			</div>


			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->

		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Pre-Observation Details'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



			<div class="formGroup formGroup-50">
				<label><?= lang("Lesson_plan_available"); ?></label>
				<select data-name="visit_type" data-type="text" data-den="100" data-req="1" class="inputer visit_type"
					data-from="<?= $POINTER; ?>get_yes_no_select">
					<option value="100" selected><?= lang("Please_Select"); ?></option>
					<option value="1">yes</option>
					<option value="1111">No</option>
				</select>
			</div>


			<div class="formGroup formGroup-50">
				<label><?= lang("Materials and Resources Prepared"); ?></label>
				<select data-name="visit_type" data-type="text" data-den="100" data-req="1" class="inputer visit_type"
					data-from="<?= $POINTER; ?>get_yes_no_select">
					<option value="100" selected><?= lang("Please_Select"); ?></option>
					<option value="1">yes</option>
					<option value="1111">No</option>
				</select>
			</div>



			<div class="formGroup formGroup-100">
				<label><?= lang("Objective_and_goals"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>



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

						<th style="width:60%;">Criteria</th>
						<th style="width:30%;">Response</th>
						<th>---</th>
					</tr>
				</thead>
				<tbody id="trainBody">
					<?php
					$rowsCountTrain = 4000;
					$qu_eqa_interim_sel = "SELECT * FROM  `eqa_interim` WHERE ((`q_form` = $q_form)) ORDER BY `q_id` ASC";
					$qu_eqa_interim_EXE = mysqli_query($KONN, $qu_eqa_interim_sel);
					if (mysqli_num_rows($qu_eqa_interim_EXE)) {
						while ($eqa_interim_REC = mysqli_fetch_assoc($qu_eqa_interim_EXE)) {
							$q_id = (int) $eqa_interim_REC['q_id'];
							$q_text = "" . $eqa_interim_REC['q_text'];
							$answer = '';

							$rowsCountTrain++;
							?>
							<tr id="row-<?= $rowsCountTrain; ?>">
								<input type="hidden" name="q_ids[]" value="<?= $q_id; ?>" class="inputer" data-name="q_ids[]"
									data-req="1" data-type="int" data-den="">

								<td><?= $q_text; ?></td>


								<td>

									<div class="formGroup formGroup-100">
										<select data-name="visit_type" data-type="text" data-den="100" data-req="1"
											class="inputer visit_type" data-from="<?= $POINTER; ?>get_yes_no_select">
											<option value="100" selected><?= lang("Please_Select_Rating"); ?></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
									</div>

									<textarea placeholder="Comment" style="width:100%;" rows="8" class="inputer"
										data-name="q_answers[]" data-req="1" data-type="text"
										data-den=""><?= $answer; ?></textarea>
								</td>
								<td>
									<a onclick="save006Entry();"
										style="display: block;margin: 0 auto;background: var(--primary);text-align: center;padding: 0.3em;color: var(--white);">Save</a>
								</td>




							</tr>

							<?php
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
			<h2 class="formPanelTitle"><?= lang('Post-Observation Reflection'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



			<div class="formGroup formGroup-100">
				<label><?= lang("Strengths Observed"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>


			<div class="formGroup formGroup-100">
				<label><?= lang("Areas for Improvement"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>


			<div class="formGroup formGroup-100">
				<label><?= lang("Suggestions for Professional Development"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>


			<div class="formGroup formGroup-100">
				<label><?= lang("Observer Feedback and Comments"); ?></label>
				<?php
				$props = [
					'dataType' => 'text',
					'dataName' => 'visit_scope',
					'dataDen' => '',
					'dataValue' => '',
					'dataClass' => 'inputer visit_scope',
					'isDisabled' => 0,
					'isReadOnly' => 0,
					'extraAttributes' => 'rows="8"',
					'isRequired' => '1',
					'dir' => 'ltr'
				];
				echo build_textarea(...$props);
				?>
			</div>



			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->




		<script>
			var rowsCount = 100;

			function remRow(idd) {
				$('#row-' + idd).remove();
				rowsCount++;
			}


			function addRow_train(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="q_ids[]" value="' + recId + '" class="inputer" data-name="q_ids[]" data-req="1" data-type="int" data-den="">' +

					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_questions[]" data-req="1" data-type="text" data-den=""></textarea></td>' +
					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="train_answers[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


					'	<td><i onclick="remRow(' + rowsCount + ')" style="color:red;display:block;cursor:pointer;" class="fa-solid fa-xmark"></i></td>' +

					'</tr>';
				$('#trainBody').append(nwTr);
				rowsCount++;
				initForms();
			}

		</script>






















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


	//addRow_train(0);

</script>