<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Users_List");

$pageDataController = $POINTER . "get_users_list";

$addNewUserController = $POINTER . 'add_users_list';


$feedback = isset($_GET['feedback']) ? test_inputs($_GET['feedback']) : 0;



$pageId = 700;
$subPageId = 200;

if ($feedback == 1) {
	include('z_feedback.php');
	die();
}

include("app/assets.php");









?>


<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('users_list_nav.php');
		?>
	</div>
	<div class="pageOptions">
		
		<a class="pageLink hasTooltip" onclick="showModal('newUserModal');">
		<i class="fa-solid fa-plus"></i>
			<div class="tooltip"><?= lang("new_user"); ?></div>
		</a>

	</div>
</div>


<!--div class="userWelcome">
	<div class="userMessages">
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
			var btns = '';
			btns += '<a href="<?= $POINTER; ?>users/view/?employee_id=' + data[i]["employee_id"] + '" class="dataActionBtn hasTooltip">' +
				'	<i class="fa-regular fa-eye"></i>' +
				'	<div class="tooltip"><?= lang("Details"); ?></div>' +
				'</a>';

			//btns= '---';
			var dt = '<div class="tr levelRow" id="levelRow-' + data[i]["employee_id"] + '">' +
				'	<div class="td">' + data[i]["employee_no"] + '</div>' +
				'	<div class="td">' + data[i]["employee_name"] + '</div>' +
				'	<div class="td">' + data[i]["employee_email"] + '</div>' +
				'	<div class="td">' + data[i]["department_name"] + '</div>' +
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



<!-- Modals START -->
<div class="modal" id="newUserModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_User", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newUserForm">


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("IQC_ID", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="employee_no" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("first_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="first_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("last_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="last_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department", "AAR"); ?></label>
					<select class="inputer" data-name="department_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments` ORDER BY `department_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$depId = (int) $SEL_REC['department_id'];
								$department_name = $SEL_REC['department_name'];
								?>
								<option value="<?= $depId; ?>"><?= $department_name; ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("login_ID", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="employee_email" data-den="" data-req="1"
						data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("password", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="employee_pass" data-den="" data-req="1"
						data-type="text_mixed">
				</div>
			</div>
			<!-- ELEMENT END -->









			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newUserForm', '<?= $addNewUserController; ?>');"><?= lang("Save", "AAR"); ?></button>
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
	}
</script>
