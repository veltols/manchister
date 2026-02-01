<?php
$qu_hr_documents_types_sel = "SELECT * FROM  `hr_documents_types`";
$qu_hr_documents_types_EXE = mysqli_query($KONN, $qu_hr_documents_types_sel);
if (mysqli_num_rows($qu_hr_documents_types_EXE)) {
	while ($hr_documents_types_REC = mysqli_fetch_assoc($qu_hr_documents_types_EXE)) {
		$idd = (int) $hr_documents_types_REC['document_type_id'];
		$nnn = $hr_documents_types_REC['document_type_name'];
		?>
		<a href="<?= $POINTER; ?>hr_documents/list/?document_type_id=<?= $idd; ?>" class="pageNavLink <?php if ($subPageId == $idd) {
				echo 'activePageNavLink';
			} ?>">
			<span><?= $nnn; ?></span>
			<div class="dec"></div>
		</a>
		<?php
	}
}
?>