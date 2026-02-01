<?php
	$qu_employees_services_sel = "SELECT `employees_services`.`service_id`,
                                    `employees_list_services`.`service_name`,
                                    `employees_list_services`.`service_icon`,
                                    `employees_list_services`.`service_link`,
                                    `employees_list_services`.`order_no`

                                    FROM  `employees_services` 
                                    INNER JOIN `employees_list_services` ON `employees_services`.`service_id` = `employees_list_services`.`service_id`
                                    WHERE `employees_services`.`employee_id` = $USER_ID ORDER BY `employees_list_services`.`order_no` ASC";
	$qu_employees_services_EXE = mysqli_query($KONN, $qu_employees_services_sel);
	if(mysqli_num_rows($qu_employees_services_EXE)){
		while($employees_services_REC = mysqli_fetch_assoc($qu_employees_services_EXE)){
			$service_id = ( int ) $employees_services_REC['service_id'];
            $service_name = $employees_services_REC['service_name'];
            $service_icon = $employees_services_REC['service_icon'];
            $service_link = $employees_services_REC['service_link'];
		?>
        
		<!-- ------------------------------------------------------ -->
		<!-- Link Start -->
		<a href="<?= $POINTER; ?><?= $service_link; ?>" class="navItem <?php if ($pageId == $service_id) {
			  echo 'navItemSelected';
		  } ?>">
			<i class="<?=$service_icon; ?>"></i>
			<span class="navItemText"><?=$service_name; ?></span>
		</a>
		<!-- Link End -->
		<!-- ------------------------------------------------------ -->
		<?php
		}
	}
?>


<?php
/*
//check if line manager
if( $USER_ID == $empDeptLineManagerId ){
	//check if there is unmapped strategic tasks
	$is_task_assigned = 0;
	$qu_m_strategic_plans_internal_maps_sel = "SELECT COUNT(`local_map_id`), `plan_id` FROM  `m_strategic_plans_internal_maps` WHERE ((`is_task_assigned` = 0) AND (`department_id` = $empDeptId))";
	$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
	if(mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE)){
		$m_strategic_plans_internal_maps_DATA = mysqli_fetch_array($qu_m_strategic_plans_internal_maps_EXE);
		$totCount = ( int ) $m_strategic_plans_internal_maps_DATA[0];
		$thsPlanId = ( int ) $m_strategic_plans_internal_maps_DATA[1];




		$is_published = 0;
	$qu_m_strategic_plans_sel = "SELECT `is_published` FROM  `m_strategic_plans` WHERE `plan_id` = $thsPlanId";
	$qu_m_strategic_plans_EXE = mysqli_query($KONN, $qu_m_strategic_plans_sel);
	if(mysqli_num_rows($qu_m_strategic_plans_EXE)){
		$m_strategic_plans_DATA = mysqli_fetch_assoc($qu_m_strategic_plans_EXE);
		$is_published = ( int ) $m_strategic_plans_DATA['is_published'];
	}






		if( $is_published > 0 ){
			if( $totCount > 0 ){
?>
	<a href="<?= $POINTER; ?>ext/strategies/task_assign/" class="navItem <?php if ($pageId == 6950) {
		echo 'navItemSelected';
	} ?>">
		<i class="fa-solid fa-folder-open"></i>
		<span class="navItemText"><?=lang("Strategy Mapping"); ?></span>
	</a>
<?php
			}
		}
	}

}
*/
?>

