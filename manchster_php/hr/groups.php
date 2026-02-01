<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Employee_Dashboard");
$groupsListController = $POINTER . 'get_group_list';
$getGroupDataController = $POINTER . 'get_group_data';
$addNewGroupController = $POINTER . 'add_group_list';

$addNewFileController = $POINTER . 'upload_group_list';
$addNewFileVerController = $POINTER . 'version_group_list';
$addNewMemberController = $POINTER . 'add_group_member';

$isCom = isset($_GET['c']) ? 1 : 0;

$pageId = 200;
$pageGroupTitle = lang("group", "AAR");


if ($isCom == 1) {
	$pageId = 250;
	$pageGroupTitle = lang("committee", "AAR");
}
include("app/assets.php");

$page_title = $EMPLOYEE_NAME;

//check permission
if ($isCom == 1) {
	if ($IS_COMMITTEE == 0) {
		header("location:" . $POINTER . "dashboard/");
	}
} else {
	if ($IS_GROUP == 0) {
		header("location:" . $POINTER . "dashboard/");
	}
}
?>

<script>
	var activeGroup = 0;
</script>
<!-- Main Groups Container START -->
<div class="groupsContainer">

	<!-- Groups Manager START -->
	<div class="groupsManager">
		<!-- Groups Header START -->
		<div class="gmHeader">
			<span class="titler"><?= $pageGroupTitle; ?>s</span>
			<span class="searcherBtn">
				<i onclick="addNewGroup();" title="<?= lang("Add_New_", "AAR"); ?> <?= $pageGroupTitle; ?>"
					class="fa-solid fa-plus"></i>
				<i onclick="showGroupsNameSearcher();" title="<?= lang("Search_", "AAR"); ?> <?= $pageGroupTitle; ?>"
					class="fa-solid fa-search"></i>
			</span>

			<div id="groupsSearcher" class="groupsSearcher groupsSearcherHidden">
				<input type="text" id="groupNameSearcher"
					placeholder="<?= lang("Search By ", "AAR"); ?> <?= $pageGroupTitle; ?> <?= lang("Name", "AAR"); ?>">
				<i onclick="hideGroupsNameSearcher();" title="<?= lang("Close", "AAR"); ?>"
					class="fa-solid fa-times"></i>
			</div>
		</div>
		<!-- Groups Header END -->

		<!-- Groups Lister START -->
		<div class="groupsLister" id="groupsListContainer"></div>
		<!-- Groups Lister END -->

	</div>
	<!-- Groups Manager END -->
	<script>
		var newGroupAdded = false;
		var newfileAdded = false;
		var newMemberAdded = false;
		function bindGroupData(data) {

			$('#groupsListContainer').html('');
			var listCount = 0;
			for (i = 0; i < data.length; i++) {

				var dt = '<div onclick="loadGroupData(' + data[i]["group_id"] + ');" class="groupItem" id="group-' + data[i]["group_id"] + '">' +
					'	<div class="itemImageTxt" style="background: ' + data[i]["group_color"] + '"><span>' + data[i]["group_initials"] + '</span></div>' +
					'	<div class="itemName"><span>' + data[i]["group_name"] + '</span></div>' +
					'</div>';

				$('#groupsListContainer').prepend(dt);
				listCount++;
			}
		}
		function loadGroups() {
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $groupsListController; ?>',
				dataType: "JSON",
				data: { 'is_com': <?= $isCom; ?> },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						bindGroupData(responser[0].data);
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
		function loadGroupData(group_id) {
			start_loader('article');
			$.ajax({
				type: 'POST',
				url: '<?= $getGroupDataController; ?>',
				dataType: "JSON",
				data: { 'group_id': group_id, 'op': 'details' },
				success: function (responser) {
					onCall = false;
					end_loader('article');
					if (responser[0].success == true && responser[0].data) {
						var thsData = responser[0].data;
						$('#groupMainInit span').text(thsData[0]["group_initials"]);
						$('#groupMainInit').css('background', thsData[0]["group_color"]);

						$('#groupMainName').text(thsData[0]["group_name"]);
						$('#groupDetName').text(thsData[0]["group_name"]);
						$('#groupDetDesc').text(thsData[0]["group_desc"]);

						$('.groupItemActive').removeClass("groupItemActive");
						$('#group-' + group_id).addClass("groupItemActive");
						activeGroup = group_id;
						$('.group-id-service').each(function () {
							$(this).val(group_id);
						});
						switchTab(3);
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
	</script>


	<!-- Groups Viewer START -->
	<div class="groupsViewer">
		<!-- Groups Tab Header START -->
		<div class="tabHeader tabHeaderHidden">
			<div class="tabHeaderGroupName">
				<div class="itemImageTxt" id="groupMainInit"><span></span></div>
				<div class="itemName"><span id="groupMainName"></span></div>
			</div>
			<div class="tabHeaderItems">
				<div id="tabHeader-1" onclick="loadGroupMessages();" class="tabItem">
					<span><?= lang("Posts", "AAR"); ?></span>
				</div>
				<div id="tabHeader-2" onclick="loadGroupFiles();" class="tabItem">
					<span><?= lang("Files", "AAR"); ?></span>
				</div>
				<div id="tabHeader-3" onclick="switchTab(3);" class="tabItem">
					<span><?= lang("Details", "AAR"); ?></span>
				</div>
				<?php
				if ($isCom == 1) {
					?>
					<div id="tabHeader-5" onclick="switchTab(5);" class="tabItem">
						<span><?= lang("Approvals", "AAR"); ?></span>
					</div>
					<?php
				}
				?>
				<div id="tabHeader-4" onclick="loadGroupMembers();" class="tabItem">
					<span><?= lang("members", "AAR"); ?></span>
				</div>
			</div>
		</div>
		<!-- Groups Tab Header END -->

		<!-- Groups Tab Body START -->
		<div class="tabBody">
			<div id="tabBody-0" class="tabBodyItem tabBodyItemActive">
				<br><br>&nbsp;&nbsp;&nbsp;Select a <?= $pageGroupTitle; ?> to view
			</div>
			<div id="tabBody-1" class="tabBodyItem">
				<?php
				include("groups_view/01_posts.php");
				?>
			</div>
			<div id="tabBody-2" class="tabBodyItem">
				<?php
				include("groups_view/02_files.php");
				?>
			</div>
			<div id="tabBody-3" class="tabBodyItem">
				<?php
				include("groups_view/03_details.php");
				?>
			</div>
			<div id="tabBody-4" class="tabBodyItem">
				<?php
				include("groups_view/04_members.php");
				?>
			</div>
			<div id="tabBody-5" class="tabBodyItem">
				<?php
				include("groups_view/05_approvals.php");
				?>
			</div>
		</div>
		<!-- Groups Tab Body END -->
	</div>
	<!-- Groups Viewer END -->

</div>
<!-- Main Groups Container END -->

<script>

	//groups name search functions START ----------------------------------
	function showGroupsNameSearcher() {
		$('#groupsSearcher').removeClass('groupsSearcherHidden');
	}
	function hideGroupsNameSearcher() {
		$('#groupsSearcher').addClass('groupsSearcherHidden');
	}
	//groups name search functions END ----------------------------------

	//groups New modal functions START ----------------------------------
	function addNewGroup() {
		showModal('newGroupModal');
	}
	function closeNewGroup() {
		closeModal()
	}
	//groups New modal functions END ----------------------------------

	//groups New modal functions START ----------------------------------
	function selectColor(cId, container) {
		$('#' + container + ' .colorItemSelected').removeClass('colorItemSelected');
		$('#' + container + ' #color-' + cId).addClass('colorItemSelected');
		$('.group_color').val(cId);
	}
	//groups New modal functions END ----------------------------------
</script>

<?php
include("app/footer.php");
?>


<!-- Modals START -->
<!-- Add New Group -->
<div class="modal" id="newGroupModal">
	<div class="modalHeader">
		<h1><?= lang("Add_New_", "AAR"); ?> <?= $pageGroupTitle; ?></h1>
		<i onclick="closeNewGroup();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newGroupForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= $pageGroupTitle; ?> <?= lang("_Name", "AAR"); ?></label>
					<input type="hidden" value="<?= $isCom; ?>" class="inputer" data-name="is_com" data-den=""
						data-req="1" data-type="hidden">
					<input type="text" class="inputer" data-name="group_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= $pageGroupTitle; ?> <?= lang("_Color", "AAR"); ?></label>
					<input class="inputer group_color" value="1" data-name="group_color_id" data-den="" data-req="1"
						data-type="hidden">
					<div id="newGroupColor" class="colorSelector">
						<?php
						$qu_sys_lists_colors_sel = "SELECT * FROM  `sys_lists_colors` ORDER BY `color_id` ASC";
						$qu_sys_lists_colors_EXE = mysqli_query($KONN, $qu_sys_lists_colors_sel);
						if (mysqli_num_rows($qu_sys_lists_colors_EXE)) {
							$fs = 0;
							while ($sys_lists_colors_REC = mysqli_fetch_assoc($qu_sys_lists_colors_EXE)) {
								$color_id = (int) $sys_lists_colors_REC['color_id'];
								$color_value = $sys_lists_colors_REC['color_value'];
								$thsClass = '';
								if ($fs == 0) {
									$thsClass = 'colorItemSelected';
									$fs = 100;
								}
								?>
								<div id="color-<?= $color_id; ?>" onclick="selectColor(<?= $color_id; ?>, 'newGroupColor');"
									class="colorItem <?= $thsClass; ?>">
									<div class="colorValue" style="background: <?= $color_value; ?>;"></div>
								</div>
								<?php
							}
						}

						?>



					</div>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement">
					<label><?= $pageGroupTitle; ?> <?= lang("_Description", "AAR"); ?></label>
					<textarea type="text" class="inputer" data-name="group_desc" data-den="" data-req="1"
						data-type="text" rows="5"></textarea>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-1">
				<div class="formElement"><br><br></div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newGroupForm', '<?= $addNewGroupController; ?>');newGroupAdded=true;"><?= lang("Add", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeNewGroup();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->


<!-- Modals START -->
<!-- Upload New File -->
<div class="modal" id="newfileModal">
	<div class="modalHeader">
		<h1><?= lang("Upload_File", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newFileForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file_name", "AAR"); ?></label>
					<input type="hidden" class="inputer group-id-service" data-name="group_id" data-den="0" data-req="1"
						data-type="hidden">
					<input type="text" class="inputer" data-name="file_name" data-den="" data-req="1" data-type="text">
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="uploaded_file" data-den="" data-req="1"
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
						onclick="submitForm('newFileForm', '<?= $addNewFileController; ?>');newfileAdded=true;"><?= lang("Upload", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeNewGroup();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->

<!-- Modals START -->
<!-- add New File Version -->
<div class="modal" id="newfileVersionModal">
	<div class="modalHeader">
		<h1><?= lang("Upload_New_File_Version", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newFileVersionForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("file_name", "AAR"); ?></label>
					<input type="hidden" class="inputer group-id-service" data-name="group_id" data-den="0" data-req="1"
						data-type="hidden">
					<input type="hidden" class="inputer file_id_at_version" data-name="file_id" data-den="0"
						data-req="1" data-type="hidden">
					<input type="text" class="" id="file_name_at_version" data-den="" data-req="1" data-type="text"
						readonly disabled>
				</div>
			</div>
			<!-- ELEMENT END -->


			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("New_Version", "AAR"); ?></label>
					<input type="file" class="inputer" data-name="uploaded_file" data-den="" data-req="1"
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
						onclick="submitForm('newFileVersionForm', '<?= $addNewFileVerController; ?>');newfileAdded=true;"><?= lang("Upload", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeNewGroup();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->



<!-- Modals START -->
<!-- Add New Member -->
<div class="modal" id="newMemberModal">
	<div class="modalHeader">
		<h1><?= lang("Add_new_member", "AAR"); ?></h1>
		<i onclick="closeModal();" class="fa-solid fa-times"></i>
	</div>
	<div class="modalBody">
		<div class="row pageForm" id="newMemberForm">

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<label><?= lang("Member", "AAR"); ?></label>
					<input type="hidden" class="inputer group-id-service" data-name="group_id" data-den="0" data-req="1"
						data-type="hidden">
					<select class="inputer" data-name="employee_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_employees_list_sel = "SELECT * FROM  `employees_list` WHERE ((`employee_id` <> $USER_ID) AND (`is_deleted` = 0) AND (`is_hidden` = 0))";
						$qu_employees_list_EXE = mysqli_query($KONN, $qu_employees_list_sel);
						if (mysqli_num_rows($qu_employees_list_EXE)) {
							while ($employees_list_REC = mysqli_fetch_assoc($qu_employees_list_EXE)) {
								$employee_id = (int) $employees_list_REC['employee_id'];
								$namer = $employees_list_REC['first_name'] . " " . $employees_list_REC['last_name'];
								?>
								<option value="<?= $employee_id; ?>"><?= $namer; ?></option>
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
					<label><?= lang("Role", "AAR"); ?></label>
					<select class="inputer" data-name="group_role_id" data-den="0" data-req="1" data-type="text">
						<option value="0" selected><?= lang(data: "Please_Select", trans: "AAR"); ?></option>
						<?php
						$qu_z_groups_list_roles_sel = "SELECT * FROM  `z_groups_list_roles`";
						$qu_z_groups_list_roles_EXE = mysqli_query($KONN, $qu_z_groups_list_roles_sel);
						if (mysqli_num_rows($qu_z_groups_list_roles_EXE)) {
							while ($z_groups_list_roles_REC = mysqli_fetch_assoc($qu_z_groups_list_roles_EXE)) {
								$group_role_id = (int) $z_groups_list_roles_REC['group_role_id'];
								$group_role_name = $z_groups_list_roles_REC['group_role_name'];
								?>
								<option value="<?= $group_role_id; ?>"><?= $group_role_name; ?></option>
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
				<div class="formElement"><br><br></div>
			</div>
			<div class="col-2">
				<div class="formElement">
					<button class="submitBtn" type="button"
						onclick="submitForm('newMemberForm', '<?= $addNewMemberController; ?>');newMemberAdded=true;"><?= lang("Save", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

			<!-- ELEMENT START -->
			<div class="col-2">
				<div class="formElement">
					<button class="cancelBtn" type="button"
						onclick="closeNewGroup();"><?= lang("Cancel", "AAR"); ?></button>
				</div>
			</div>
			<!-- ELEMENT END -->

		</div>
	</div>
</div>
<!-- Modals END -->




<script>
	function afterFormSubmission() {
		if (newGroupAdded == true) {
			newGroupAdded = false;
			loadGroups();
			closeModal();
		} else if (newfileAdded == true) {
			newfileAdded = false;
			loadGroupFiles();
			closeModal();
		} else if (newMemberAdded == true) {
			newMemberAdded = false;
			loadGroupMembers();
			closeModal();
		}
	}
</script>


<script>
	$(".messagesContainer").scrollTop($(".messagesContainer")[0].scrollHeight - 50);



	loadGroups();
</script>

<?php
/*
		 Group Image Element
									   <!-- ELEMENT START -->
									   <div class="col-2">
										   <div class="formElement">
											   <label><?= lang("Group_Image", "AAR"); ?></label>
											   <div id="drop-area" class="fileSelector">
												   <div id="uploadScript" class="uploadScript">
													   <i class="fa-solid fa-file-arrow-up"></i>
													   <span><?= lang("Drop Image Here or click to upload", "AAR"); ?></span>
												   </div>
												   <div id="gallery"></div>
												   <input type="file" id="fileElem" accept="image/*" style="display:none">
											   </div>
										   </div>
									   </div>
									   <script>
										   $(document).ready(function () {
											   let $uploadScript = $("#uploadScript");
											   let $dropArea = $("#drop-area");
											   let $fileInput = $("#fileElem");
											   let $gallery = $("#gallery");

											   // Prevent default drag behaviors
											   $(document).on("dragenter dragover dragleave drop", function (e) {
												   e.preventDefault();
												   e.stopPropagation();
											   });

											   // Highlight drop area
											   $dropArea.on("dragenter dragover", function () {
												   $dropArea.addClass("highlight");
											   });

											   $dropArea.on("dragleave drop", function () {
												   $dropArea.removeClass("highlight");
											   });

											   // Handle drop
											   $dropArea.on("drop", function (e) {

												   let files = e.originalEvent.dataTransfer.files;
												   if (files.length > 0) {
													   handleFile(files[0]); // only first file
												   }

											   });

											   // Click to trigger file input
											   $uploadScript.on("click", function () {
												   $fileInput.click();
											   });

											   // Handle file input change

											   $fileInput.on("change", function () {
												   if (this.files.length > 0) {
													   handleFile(this.files[0]);
												   }
											   });

											   function handleFile(file) {

												   // Clear preview
												   $gallery.empty();

												   // Attach file to file input manually (replace existing selection)
												   let dataTransfer = new DataTransfer();
												   dataTransfer.items.add(file);
												   $fileInput[0].files = dataTransfer.files;

												   // Show preview
												   let reader = new FileReader();
												   reader.onloadend = function () {
													   let img = $("<img>").attr("src", reader.result);
													   $gallery.append(img);
												   };
												   reader.readAsDataURL(file);

											   }
										   });
									   </script>
									   <!-- ELEMENT END -->
										*/
?>
<?php
/*
<div class="groupItem">
<img class="itemImage" src="<?= $POINTER; ?>../uploads/et_logo.png" alt="Group Image">
<div class="itemName"><span>test Group 1</span></div>
<div class="itemNotificationsCount">5</div>
</div>
*/
?>