<?php

$page_title = $page_description = $page_keywords = $page_author = lang("View_Details");
$updatePageController = $POINTER . 'update_employees_list';
$updatePageCredsController = $POINTER . 'creds_employees_list';
$submitBtn = lang("Save", "AAR");
$isNewForm = true;

$addNewTicketController = $POINTER . "add_tickets_list";
$employee_id = 0;


if (!isset($_GET['employee_id'])) {
	header("location:" . $DIR_atps);
	die();
}

$employee_id = (int) test_inputs($_GET['employee_id']);

if ($employee_id == 0) {
	header("location:" . $DIR_atps);
	die();
}

$pageId = 700;
$subPageId = 20000;
include("app/assets.php");



$employee_no = "";
$first_name = "";
$second_name = "";
$third_name = "";
$last_name = "";
$employee_code = "";
$employee_dob = "";
$employee_join_date = "";
$title_id = 0;
$gender_id = 0;
$nationality_id = 0;
$timezone_id = 0;
$employee_email = "";
$employee_picture = "";
$company_name = "";
$department_id = 0;
$leaves_open_balance = 0;
$is_deleted = 0;
$is_new = 0;
$employee_type = '';
$qu_employees_list_sel = "SELECT * FROM  `employees_list` WHERE `employee_id` = $employee_id";
$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);

if (mysqli_num_rows($qu_employees_list_EXE)) {
	$employees_list_DATA = mysqli_fetch_assoc($qu_employees_list_EXE);
	$employee_id = (int) $employees_list_DATA['employee_id'];
	$employee_no = "" . $employees_list_DATA['employee_no'];
	$first_name = "" . $employees_list_DATA['first_name'];
	$second_name = "" . $employees_list_DATA['second_name'];
	$third_name = "" . $employees_list_DATA['third_name'];
	$last_name = "" . $employees_list_DATA['last_name'];
	$employee_code = "" . $employees_list_DATA['employee_code'];
	$employee_dob = "" . $employees_list_DATA['employee_dob'];
	$employee_join_date = "" . $employees_list_DATA['employee_join_date'];
	$title_id = (int) $employees_list_DATA['title_id'];
	$gender_id = (int) $employees_list_DATA['gender_id'];
	$nationality_id = (int) $employees_list_DATA['nationality_id'];
	$timezone_id = (int) $employees_list_DATA['timezone_id'];
	$employee_email = "" . $employees_list_DATA['employee_email'];
	$employee_picture = "" . $employees_list_DATA['employee_picture'];
	$company_name = "" . $employees_list_DATA['company_name'];
	$employee_type = "" . $employees_list_DATA['employee_type'];
	$department_id = (int) $employees_list_DATA['department_id'];
	$leaves_open_balance = (int) $employees_list_DATA['leaves_open_balance'];
	$is_deleted = (int) $employees_list_DATA['is_deleted'];
	$is_new = (int) $employees_list_DATA['is_new'];
}


$department_name = "";
$department_code = "";
$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments` WHERE `department_id` = $department_id";
$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
if (mysqli_num_rows($qu_employees_list_departments_EXE)) {
	$employees_list_departments_DATA = mysqli_fetch_assoc($qu_employees_list_departments_EXE);
	$department_id = (int) $employees_list_departments_DATA['department_id'];
	$department_name = $employees_list_departments_DATA['department_name'];
	$department_code = $employees_list_departments_DATA['department_code'];
}



$department_name = "";
$department_code = "";



$passport_issue_date = "";
$passport_expiry_date = "";
$passport_no = "";
$visa_issue_date = "";
$visa_expiry_date = "";
$visa_no = "";
$eid_issue_date = "";
$eid_expiry_date = "";
$eid_no = "";
$labour_issue_date = "";
$labour_expiry_date = "";
$labour_no = "";
$license_issue_date = "";
$license_expiry_date = "";
$license_no = "";

$qu_employees_list_creds_sel = "SELECT * FROM  `employees_list_creds` WHERE `employee_id` = $employee_id";
$qu_employees_list_creds_EXE = mysqli_query($KONN, $qu_employees_list_creds_sel);
if (mysqli_num_rows($qu_employees_list_creds_EXE)) {
	$employees_list_creds_DATA = mysqli_fetch_assoc($qu_employees_list_creds_EXE);
	$employee_credential_id = (int) $employees_list_creds_DATA['employee_credential_id'];
	$passport_issue_date = $employees_list_creds_DATA['passport_issue_date'];
	$passport_expiry_date = $employees_list_creds_DATA['passport_expiry_date'];
	$passport_no = $employees_list_creds_DATA['passport_no'];
	$visa_issue_date = $employees_list_creds_DATA['visa_issue_date'];
	$visa_expiry_date = $employees_list_creds_DATA['visa_expiry_date'];
	$visa_no = $employees_list_creds_DATA['visa_no'];
	$eid_issue_date = $employees_list_creds_DATA['eid_issue_date'];
	$eid_expiry_date = $employees_list_creds_DATA['eid_expiry_date'];
	$eid_no = $employees_list_creds_DATA['eid_no'];
	$labour_issue_date = $employees_list_creds_DATA['labour_issue_date'];
	$labour_expiry_date = $employees_list_creds_DATA['labour_expiry_date'];
	$labour_no = $employees_list_creds_DATA['labour_no'];
	$license_issue_date = $employees_list_creds_DATA['license_issue_date'];
	$license_expiry_date = $employees_list_creds_DATA['license_expiry_date'];
	$license_no = $employees_list_creds_DATA['license_no'];
	$employee_id = (int) $employees_list_creds_DATA['employee_id'];
}





?>

<div class="pageHeader">
	<div class="pageNav">
		<?php
		include('employees_list_nav.php');
		?>
		<a href="#" class="pageNavLink activePageNavLink">
			<span><i class="fa-regular fa-eye"></i><?= lang("View", "AAR"); ?></span>
			<div class="dec"></div>
		</a>
	</div>
	<div class="pageOptions">

		<a class="pageLink" onclick="showModal('EditEmployeeModal');">
			<div class="linkTxt"><?= lang("Edit"); ?></div>
		</a>
		<a class="pageLink" onclick="showModal('EditEmployeeCredsModal');">
			<div class="linkTxt"><?= lang("Credentials"); ?></div>
		</a>
	</div>
</div>

<!-- MAIN VIEW CONTAINER -->
<div class="viewContainer">
	<!-- MAIN VIEW CONTAINER -->




	<div class="summaryBadges">

		<div class="sumBadge">
			<div class="name"><?= lang('system_ID', 'ARR'); ?></div>
			<div class="number"><?= $employee_id; ?></div>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		</div>

		<div class="sumBadge">
			<div class="name"><?= lang('Department', 'ARR'); ?></div>
			<div class="number"><?= $department_name; ?></div>
		</div>


	</div>



	<div class="tabContainer">
		<div class="tabHeaders">
			<div class="tabTitle" extra-action="doNothing"><?= lang('Employee_Details', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLeaves"><?= lang('Leaves', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getpermissions"><?= lang('Permissions', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getDA"><?= lang('DA', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getAttendance"><?= lang('Attendance', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getPerformance"><?= lang('Performance', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getLogs"><?= lang('Logs', 'ARR'); ?></div>
			<div class="tabTitle" extra-action="getInterviews"><?= lang('Exit_Interview', 'ARR'); ?></div>
		</div>
		<div class="tabBody">
			<div class="tabContent">
				<?php
				include('employees_view/details.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/leaves.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/permissions.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/da.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/attendance.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/performance.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/logs.php');
				?>
			</div>
			<div class="tabContent">
				<?php
				include('employees_view/interviews.php');
				?>
			</div>
		</div>
	</div>



	<!-- MAIN VIEW CONTAINER -->
</div>
<!-- MAIN VIEW CONTAINER -->
<script>
	function doNothing() { }
	//getApps();
</script>

<?php
include("app/footer.php");
include("../public/app/tabs.php");
?>


<!-- Modals START -->
<div class="modal" id="empActModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_Ticket", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="ItReqForm">

			<input type="hidden" class="inputer" data-name="employee_id" data-den="" data-req="0" data-type="hidden"
				value="<?= $employee_id; ?>">
			<input type="hidden" class="inputer" data-name="added_by" data-den="" data-req="0" data-type="hidden"
				value="<?= $USER_ID; ?>">

			<input type="hidden" class="inputer" data-name="category_id" data-den="" data-req="0" data-type="hidden"
				value="3">
			<input type="hidden" class="inputer" data-name="ticket_subject" data-den="" data-req="0" data-type="hidden"
				value="Employee activation">



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Priority", "AAR"); ?></label>
					<select class="inputer" data-name="theme_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `theme_id`, `priority_name` FROM  `sys_list_priorities` ORDER BY `theme_id` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$theme_id = (int) $SEL_REC['theme_id'];
								$priority_name = $SEL_REC['priority_name'];
								?>
								<option value="<?= $theme_id; ?>"><?= $priority_name; ?></option>

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
					<label><?= lang("ticket_description", "AAR"); ?></label>
					<textarea type="text" class="inputer edit_ticket_description" data-name="ticket_description"
						data-den="" data-req="1" data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Attachment", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="ticket_attachment" data-den="" data-req="0"
						data-type="file">
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
						onclick="submitForm('ItReqForm', '<?= $addNewTicketController; ?>');"><?= lang("Create_Ticket", "AAR"); ?></button>
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





<!-- Modals START -->
<div class="modal modal-lg" id="EditEmployeeCredsModal">
	<div class="modalHeader">
		<h1><?= lang("Employee_Data", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="EditEmployeeCredsForm">

			<input type="hidden" class="inputer edit_employee_id" data-name="employee_id" data-den="" data-req="0"
				data-type="hidden" value="<?= $employee_id; ?>">

			<input type="hidden" class="inputer edit_employee_credential_id" data-name="employee_credential_id"
				data-den="" data-req="0" data-type="hidden" value="<?= $employee_credential_id; ?>">


			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("passport_issue_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="passport_issue_date"
						value="<?= $passport_issue_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("passport_expiry_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="passport_expiry_date"
						value="<?= $passport_expiry_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("passport_no", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="passport_no" value="<?= $passport_no; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("visa_issue_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="visa_issue_date"
						value="<?= $visa_issue_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("visa_expiry_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="visa_expiry_date"
						value="<?= $visa_expiry_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("visa_no", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="visa_no" value="<?= $visa_no; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("eid_issue_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="eid_issue_date"
						value="<?= $eid_issue_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("eid_expiry_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="eid_expiry_date"
						value="<?= $eid_expiry_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("eid_no", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="eid_no" value="<?= $eid_no; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("license_issue_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="license_issue_date"
						value="<?= $license_issue_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("license_expiry_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="license_expiry_date"
						value="<?= $license_expiry_date; ?>" data-den="" data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-3">
				<div class="formElement">
					<label><?= lang("license_no", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="license_no" value="<?= $license_no; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						placeholder="Mention reason for data update" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('EditEmployeeCredsForm', '<?= $updatePageCredsController; ?>');"><?= lang("Update", "AAR"); ?></button>
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

<!-- Modals START -->
<div class="modal modal-lg" id="EditEmployeeModal">
	<div class="modalHeader">
		<h1><?= lang("Employee_Data", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="EditEmployeeForm">




			<input type="hidden" class="inputer edit_employee_id" data-name="employee_id" data-den="" data-req="1"
				data-type="hidden" value="<?= $employee_id; ?>">

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("employee_code", "AAR"); ?></label>
					<input type="text" class="" data-name="employee_code" value="<?= $employee_code; ?>" data-den=""
						data-req="1" data-type="text" disabled readonly>
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("title", "AAR"); ?></label>
					<select class="inputer title_id" data-name="title_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `item_id`, `item_name` FROM  `sys_lists` WHERE ( `item_category` = 'title' ) ORDER BY `item_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$item_id = (int) $SEL_REC['item_id'];
								$item_name = $SEL_REC['item_name'];
								?>
								<option value="<?= $item_id; ?>"><?= $item_name; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.title_id').val('<?= $title_id; ?>');
			</script>
			<!-- ELEMENT START -->
			<div class="col-4">
				<div class="formElement">
					<label><?= lang("first_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="first_name" value="<?= $first_name; ?>" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-4">
				<div class="formElement">
					<label><?= lang("second_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="second_name" value="<?= $second_name; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-4">
				<div class="formElement">
					<label><?= lang("third_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="third_name" value="<?= $third_name; ?>" data-den=""
						data-req="0" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->
			<!-- ELEMENT START -->
			<div class="col-4">
				<div class="formElement">
					<label><?= lang("last_name", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="last_name" value="<?= $last_name; ?>" data-den=""
						data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("employee_dob", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="employee_dob"
						value="<?= $employee_dob; ?>" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("employee_join_date", "AAR"); ?></label>
					<input type="text" class="inputer has_long_date" data-name="employee_join_date"
						value="<?= $employee_join_date; ?>" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("gender", "AAR"); ?></label>
					<select class="inputer gender_id" data-name="gender_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `item_id`, `item_name` FROM  `sys_lists` WHERE ( `item_category` = 'gender' ) ORDER BY `item_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$item_id = (int) $SEL_REC['item_id'];
								$item_name = $SEL_REC['item_name'];
								?>
								<option value="<?= $item_id; ?>"><?= $item_name; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.gender_id').val('<?= $gender_id; ?>');
			</script>



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("certificate", "AAR"); ?></label>
					<select class="inputer certificate_id" data-name="certificate_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `certificate_id`, `certificate_name` FROM  `hr_certificates` ORDER BY `certificate_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$certificate_id = (int) $SEL_REC['certificate_id'];
								$certificate_name = $SEL_REC['certificate_name'];
								?>
								<option value="<?= $certificate_id; ?>"><?= $certificate_name; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.certificate_id').val('<?= $certificate_id; ?>');
			</script>




			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("nationality", "AAR"); ?></label>
					<select class="inputer nationality_id" data-name="nationality_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `country_id`, `country_name` FROM  `sys_countries` ORDER BY `country_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$country_id = (int) $SEL_REC['country_id'];
								$country_name = $SEL_REC['country_name'];
								?>
								<option value="<?= $country_id; ?>"><?= $country_name; ?></option>

								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.nationality_id').val('<?= $nationality_id; ?>');
			</script>



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("leaves_open_balance", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="leaves_open_balance"
						value="<?= $leaves_open_balance; ?>" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->






			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("department", "AAR"); ?></label>
					<select class="inputer edit_department_id" data-name="department_id" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_SEL_sel = "SELECT `department_id`, `department_name` FROM  `employees_list_departments` ORDER BY `department_name` ASC";
						$qu_SEL_EXE = mysqli_query($KONN, $qu_SEL_sel);
						if (mysqli_num_rows($qu_SEL_EXE)) {
							while ($SEL_REC = mysqli_fetch_assoc($qu_SEL_EXE)) {
								$depId = (int) $SEL_REC['department_id'];
								$department_name = $SEL_REC['department_name'];
								?>
								<option value="<?= $department_id; ?>"><?= $department_name; ?></option>

								<?php


							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.edit_department_id').val('<?= $department_id; ?>');
			</script>





			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Contracted Company", "AAR"); ?></label>
					<input type="text" class="inputer" data-name="company_name" value="<?= $company_name; ?>"
						data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->



			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("employee_type", "AAR"); ?></label>
					<select class="inputer employee_type" data-name="employee_type" data-den="0" data-req="1"
						data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<option value="local"><?= lang(data: "Local", trans: "AAR"); ?></option>
						<option value="hire"><?= lang(data: "Hire", trans: "AAR"); ?></option>
					</select>
				</div>
			</div>
			<!-- ELEMENT END -->
			<script>
				$('.employee_type').val('<?= $employee_type; ?>');
			</script>

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= lang("Remarks", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="log_remark" data-den="" data-req="1"
						placeholder="Mention reason for data update" data-type="text" rows="5"></textarea>
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
						onclick="submitForm('EditEmployeeForm', '<?= $updatePageController; ?>');"><?= lang("Update", "AAR"); ?></button>
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
		makeSiteAlert('suc', "User Updated");
		setTimeout(function () {
			window.location.reload();
		}, 400);
	}
</script>
