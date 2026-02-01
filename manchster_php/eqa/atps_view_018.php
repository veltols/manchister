<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_007sss';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;
$tab_id = (isset($_GET['tab_id'])) ? (int) test_inputs($_GET['tab_id']) : 0;

$q_form = '018';

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
			<h2 class="formPanelTitle"><?= lang('IQA Interview Questions'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->



			<div class="formGroup formGroup-100">
				<label><?= lang("IQA"); ?></label>
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

			<div><br><br></div>
			<table border="1" style="width: 100%;">

				<thead>
					<tr>

						<th style="width:45%;">Questions</th>
						<th style="width:30%;">Answer</th>
						<th style="width:5%;"></th>
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


								<td><textarea style="width:100%;" rows="8" class="inputer" data-name="q_answers[]" data-req="1"
										data-type="text" data-den=""><?= $answer; ?></textarea></td>


								<td><i onclick="remRow(<?= $rowsCountTrain; ?>)" style="color:red;display:block;cursor:pointer;"
										class="fa-solid fa-xmark"></i></td>

							</tr>

							<?php
						}
					}

					?>
				</tbody>
				<tbody>

					<tr>
						<td colspan="3"><button onclick="addRow_train(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								Row</button></td>
					</tr>

				</tbody>
			</table>
			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->










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