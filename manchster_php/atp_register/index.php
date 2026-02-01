<?php
$page_title = $page_description = $page_keywords = $page_author = lang("Dashboard");
$pageId = 100;
$subPageId = 100;
include("app/assets.php");
?>




<div class="welcomeDiv">
	<div class="texter">
		<h1>Hello, <?=$ATP_NAME; ?></h1>
		<p>Ready to start your day with some new tasks?</p>
	</div>
	<img src="<?= $UPLOADS_DIRECTORY; ?>char.png" alt="" class="char">
</div>


<div class="overAllViewer">
			<h1><?= lang("My_Requests", ""); ?></h1>


			<div class="tableContainer" id="appsListDtd">
				<div class="table">
					<div class="tableHeader">
						<div class="tr">
							<div class="th"><?= lang("REF"); ?></div>
							<div class="th"><?= lang("Name"); ?></div>
							<div class="th"><?= lang("date"); ?></div>
							<div class="th"><i class="fa-solid fa-cog"></i></div>
						</div>
					</div>
					<div class="tableBody" id="">
							<?php
							
							$qu_atps_list_requests_sel = "SELECT * FROM  `atps_list_requests` WHERE ((`is_finished` = 0) AND (`atp_id` = $ATP_ID))";
							$qu_atps_list_requests_EXE = mysqli_query($KONN, $qu_atps_list_requests_sel);
							if(mysqli_num_rows($qu_atps_list_requests_EXE)){
								while($atps_list_requests_REC = mysqli_fetch_assoc($qu_atps_list_requests_EXE)){
									$request_id = ( int ) $atps_list_requests_REC['request_id'];
									$request_name = "".$atps_list_requests_REC['request_name'.$lang_db];
									$request_name_ar = $atps_list_requests_REC['request_name_ar'];
									$added_date = $atps_list_requests_REC['added_date'];
									$added_by = ( int ) $atps_list_requests_REC['added_by'];
									$is_finished = ( int ) $atps_list_requests_REC['is_finished'];
									$atp_id = ( int ) $atps_list_requests_REC['atp_id'];
								?>

								<div class="tr levelRow" id="r-<?= $request_id; ?>">
									<div class="td">ARC-<?= $request_id; ?><?=$ATP_ID; ?></div>
									<div class="td" style="text-transform: capitalize;"><?= lang($request_name, "AAR"); ?></div>
									<div class="td"><?= $added_date; ?></div>
									<div class="td">
										<a href="<?=$POINTER; ?>accreditation/new/" class="tableActionBtn"><?=lang("Continue"); ?></a>
									</div>
								</div>
								<?php
								}
							} else {
								?>

								<div class="tr levelRow" id="r-0">
									<div class="td"><?= lang("You have no active requests", "AAR"); ?></div>
								</div>
								<?php
							}
							?>

					</div>
				</div>
			</div>

		</div>


<?php
include("app/footer.php");
?>
