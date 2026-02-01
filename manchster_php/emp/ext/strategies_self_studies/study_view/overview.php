
<?php
$pageController = $POINTER . 'ext/update_self_study_list';
?>
<div class="studyPageView">



	<div class="studyViewTitle">
		<div class="textTitle">
			<h1><?=lang("study_overview", "AAR"); ?></h1>
			<p>
				<?=lang("In here you can update study overview and title", "AAR"); ?>
			</p>
		</div>
		<div class="actionTitle">
			<a onclick="submitForm('updateStudyForm', '<?= $pageController; ?>');" class="ationButtons"><?=lang("save", "AAR"); ?></a>
		</div>
	</div>
	
	<div class="studyViewContent">
		
	



	<div class="row pageForm" id="updateStudyForm">
		
		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("Self_study_title", "AAR"); ?></label>
				<input type="hidden" class="inputer" data-name="study_id" value="<?=$study_id; ?>" data-den="0" data-req="1"
					data-type="hidden">
				<input type="text" class="inputer" data-name="study_title" value="<?=$study_title; ?>" data-den="" data-req="1"
					data-type="text">
			</div>
		</div>
		<!-- ELEMENT END -->


		<!-- ELEMENT START -->
		<div class="col-1">
			<div class="formElement">
				<label><?= lang("Self_study_overview", "AAR"); ?></label>
				<textarea type="text" class="inputer" data-name="study_overview" data-den="" rows="8" data-req="1"
					data-type="text"><?=$study_overview; ?></textarea>
			</div>
		</div>
		<!-- ELEMENT END -->
		 
		
	</div>



</div>