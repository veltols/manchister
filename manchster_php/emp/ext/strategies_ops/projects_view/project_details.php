

<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("REF"); ?></div>
				<div class="th"><?= lang("Title"); ?></div>
				<div class="th"><?= lang("Period"); ?></div>
				<div class="th"><?= lang("Date"); ?></div>
				<div class="th"><?= lang("Level"); ?></div>
				<div class="th"><?= lang("Status"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $project_code; ?></div>
				<div class="td"><?= $project_name; ?></div>
				<div class="td"><?= $project_period; ?></div>
				<div class="td"><?= $project_start_date; ?> - <?= $project_end_date; ?></div>
				<div class="td"><?= $department_name; ?></div>
<?php
if( !isset($viewOnly) ){
?>
				<div class="td">Draft</div>
<?php
} else {
?>
				<div class="td">Published</div>
<?php
}
?>
			</div>

		</div>
	</div>
</div>

<div class="tableContainer" id="appsListDtd">
	<div class="table">
		<div class="tableHeader">
			<div class="tr">
				<div class="th"><?= lang("description"); ?></div>
				<div class="th"><?= lang("analysis"); ?></div>
				<div class="th"><?= lang("recommendations"); ?></div>
			</div>
		</div>
		<div class="tableBody" id="appsListDt">

			<div class="tr levelRow" id="levelRow-sss">
				<div class="td"><?= $project_description; ?></div>
				<div class="td"><?= $project_analysis; ?></div>
				<div class="td"><?= $project_recommendations; ?></div>
			</div>

		</div>
	</div>
</div>
