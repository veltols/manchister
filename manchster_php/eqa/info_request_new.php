<?php

$page_title = $page_description = $page_keywords = $page_author = lang("Registration_Request_Form");
$pageController = $POINTER . 'save_info_request';
$submitBtn = lang("Add_TP", "AAR");





$is_draft = 0;
$stage_no = 0;


$atp_id = (isset($_GET['atp_id'])) ? (int) test_inputs($_GET['atp_id']) : 0;



$pageId = 500;
$asideIsHidden = true;
include("app/assets.php");
?>


<!-- START OF THREE COL -->
<div class="twoCols threeCols">
	<!-- START OF THREE COL -->



	<!-- START OF FORMER COL -->
	<div class="former">


		<!-- START OF FORM PANEL -->
		<div class="formPanel">
			<h2 class="formPanelTitle"><?= lang('Request Evidence from Training Provider'); ?></h2>
			<!-- START -------------------------------------------------------------------------------------------------------------------------------- -->


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
			$rowsCountTrain = 4000;
			?>


			<table border="1" style="width: 100%;">

				<thead>
					<tr>

						<th style="width:45%;">Required Evidence</th>
						<th style="width:5%;"></th>
					</tr>
				</thead>
				<tbody id="trainBody"></tbody>
				<tbody>
					<tr>
						<td colspan="3"><button onclick="addRow_evidence(0);" type="button"
								style="padding: 0.3em;margin: 1em 0;text-align:center;font-family:inherit;width:95%;margin: 0 auto;display:block;">Add
								More</button></td>
					</tr>
				</tbody>
			</table>
			<!-- END   -------------------------------------------------------------------------------------------------------------------------------- -->

		</div>
		<!-- END OF FORM PANEL -->


		<script>
			var rowsCount = 100;

			function remRow(idd) {
				$('#row-' + idd).remove();
				rowsCount++;
			}


			function addRow_evidence(recId) {
				var nwTr = '<tr id="row-' + rowsCount + '">' +
					'<input type="hidden" name="evidence_ids[]" value="' + recId + '" class="inputer" data-name="evidence_ids[]" data-req="1" data-type="int" data-den="">' +

					'<td><textarea style="width:100%;" type="text" rows="8" class="inputer" data-name="required_evidences[]" data-req="1" data-type="text" data-den=""></textarea></td>' +


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

			<a href="<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=100" class="backBtn actionBtn"
				id="thsFormBtns">
				<i class="fa-solid fa-arrow-left"></i>
				<span class="label"><?= lang("Back"); ?></span>
			</a>
			<?php
			if (1 == 1) {
				?>
				<div class="saveBtn actionBtn" onclick="saveFormChanges('<?= $pageController; ?>');">
					<i class="fa-solid fa-save"></i>
					<span class="label"><?= lang("Send_Request"); ?></span>
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

	function showRev() {
		$('#rc_comment').toggle();
		$('#revSubimt').toggle();
	}



	function afterFormSubmission() {
		window.location.href = '<?= $POINTER; ?>atps/view/?atp_id=<?= $atp_id; ?>&tab=100';
	}

</script>



<?php
include("app/footer.php");
include("../public/app/form_controller.php");
?>

<script>


	addRow_evidence(0);

</script>