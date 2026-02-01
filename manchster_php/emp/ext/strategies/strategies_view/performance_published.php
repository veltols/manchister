

<div class="strategyContainer">


<?php
	$qu_employees_list_departments_sel = "SELECT * FROM  `employees_list_departments`";
	$qu_employees_list_departments_EXE = mysqli_query($KONN, $qu_employees_list_departments_sel);
	if(mysqli_num_rows($qu_employees_list_departments_EXE)){
		while($employees_list_departments_REC = mysqli_fetch_assoc($qu_employees_list_departments_EXE)){
			$department_id = ( int ) $employees_list_departments_REC['department_id'];
			$main_department_id = ( int ) $employees_list_departments_REC['main_department_id'];
			$department_name = $employees_list_departments_REC['department_name'];
			$department_code = $employees_list_departments_REC['department_code'];
			$user_type = $employees_list_departments_REC['user_type'];
			$line_manager_id = ( int ) $employees_list_departments_REC['line_manager_id'];
			
			$totalTasksMapped = 0;
			$totalTasksAssigned = 0;
			$totalTasksProgress = 0;

			$qu_m_strategic_plans_internal_maps_sel = "SELECT * FROM  `m_strategic_plans_internal_maps` WHERE ((`plan_id` = $plan_id) AND (`department_id` = $department_id))";
			$qu_m_strategic_plans_internal_maps_EXE = mysqli_query($KONN, $qu_m_strategic_plans_internal_maps_sel);
			if(mysqli_num_rows($qu_m_strategic_plans_internal_maps_EXE)){
				while($m_strategic_plans_internal_maps_REC = mysqli_fetch_assoc($qu_m_strategic_plans_internal_maps_EXE)){
					$local_map_id = ( int ) $m_strategic_plans_internal_maps_REC['local_map_id'];
					$department_id = ( int ) $m_strategic_plans_internal_maps_REC['department_id'];
					
					$task_title = $m_strategic_plans_internal_maps_REC['task_title'];
					$task_description = $m_strategic_plans_internal_maps_REC['task_description'];
					$start_date = $m_strategic_plans_internal_maps_REC['start_date'];
					$end_date = $m_strategic_plans_internal_maps_REC['end_date'];
					$is_task_assigned = ( int ) $m_strategic_plans_internal_maps_REC['is_task_assigned'];
					
					
					$totalTasksMapped++;
					if( $is_task_assigned == 1 ){
						$totalTasksAssigned++;
						//get total progress
						$qu_tasks_list_sel = "SELECT * FROM  `tasks_list` WHERE ((`strategic_plan_id` = $plan_id) AND (`local_map_id` = $local_map_id))";
						$qu_tasks_list_EXE = mysqli_query($KONN, $qu_tasks_list_sel);
						if(mysqli_num_rows($qu_tasks_list_EXE)){
							while($tasks_list_REC = mysqli_fetch_assoc($qu_tasks_list_EXE)){
								
								$totalTasksProgress += ( int ) $tasks_list_REC['task_progress'];
								
								
							}
						}
					
					}
				}
			}
		


			$totalMappedPercentage = calculatePercentage($totalTasksMapped, $totalTasksAssigned);

			$totalProgress = 0;
			if( $totalTasksMapped != 0 ){
				$totalProgress = number_format( $totalTasksProgress / $totalTasksMapped, 1);
			}

			if( $totalProgress > 100 ){
				$totalProgress = 100;
			}
			if( $totalProgress < 0 ){
				$totalProgress = 0;
			}

			if( $totalTasksMapped > 0 ){

?>

<div class="strategyBox" style="width: 96%;">
		<div class="titler">
			<h1> <i class="fa-solid fa-university"></i> <?= $department_name; ?></h1>
		</div>
		<div class="leveler">
			<span class="namer">Total Milestones:</span>
			<span class="valer"><?=$totalTasksMapped; ?></span>
			

			&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="namer">Total Progress:</span>
			<span class="valer"><?=$totalProgress; ?> %</span>
		</div>
		
		
		<div class="mapper">
			<div class="mapperTitle">Milestones Assigned to Employees</div>
			<div class="mapperKeys">
				<div class="mapperKeyItem">
					<span class="greyBox boxer"></span>
					<span class="itemName">Not Assigned</span>
				</div>
				<div class="mapperKeyItem">
					<span class="primaryBox boxer"></span>
					<span class="itemName">Assigned</span>
				</div>
				<div class="mapperKeyItem">
					<span class="progressBox boxer"></span>
					<span class="itemName">Progress</span>
				</div>
			</div>
			<div class="mapperBar">
				<div class="mapperBarMapped" style="width: <?= $totalMappedPercentage; ?>%;"></div>
			</div>
			<div class="mapperBar">
				<div class="mapperBarMapped mapperBarProgress" style="width: <?= $totalProgress; ?>%;"></div>
			</div>
		</div>
	</div>
<?php
			}



		}
	}


?>







</div>



















<script>
	
</script>