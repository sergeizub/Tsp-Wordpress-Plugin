<?php
namespace DanceStudioManager;

if(!empty($_SESSION['dsm_client_attrs']))
	$classes_list = App::GetClient()->GetController('classes')->GetClassesList((array)$_SESSION['dsm_client_attrs'] + (array)$_REQUEST['filter']);
else
	$classes_list = App::GetClient()->GetController('classes')->GetClassesList($_REQUEST['filter']);
	
if (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || $_SESSION['dsm_client_attrs']['view'] == "List" )
	$schedules_list = App::GetClient()->GetController('classes')->GetClasses();


//Parse schedules per classes id
if(!empty($schedules_list) && $schedules_list->schedules) {
	usort($schedules_list->schedules, 'dsm_class_schedules_sort');
	foreach($schedules_list->schedules as $day) {
		if($day->data)
			foreach($day->data as $schedule) {
				$classes_schedules[$schedule->CLASS_ID][$schedule->ID] = $schedule;
				$classes_schedules[$schedule->CLASS_ID][$schedule->ID]->title = $day->title;
			}
	}
}

foreach ($classes_list->groupclasses as $class) {
	if ($classes_schedules && $classes_schedules[$class->ID])
		$class->SCHEDULES = $classes_schedules[$class->ID];
    $classes_tabs[$class->PROGRAM][] =  $class;
}

//Sort By Location Asc
foreach ($classes_tabs as $k => $v_array) {
	usort($classes_tabs[$k], 'dsm_location_sort');
}

?>
<div id="tab-classes-list" class="tab-pane">
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/class-filters.php'; ?>
<?php if (!empty($classes_tabs)): ?>
<div id="tabs">
	<ul class="nav nav-tabs" role="tablist">
        <?php foreach ($classes_tabs as $key => $item): ?>
		<?php reset($classes_tabs);?>
		<li role="presentation" class="<?=($key === key($classes_tabs) ? 'active' : '')?>">
			<a href="#tab<?=$classes_list->programs_rev[$key]?>" aria-controls="<?=$key?>" 
				role="tab" data-toggle="tab" data-program_id="<?=$classes_list->programs_rev[$key]?>"><?=$key?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</div> 
<div class="tab-content classes-list">
		<?php foreach ($classes_tabs as $key => $item): ?>
			<?php reset($classes_tabs);?>
			<div role="tabpanel" class="tab-pane <?=($key === key($classes_tabs) ? 'active' : '')?>" id="tab<?=$classes_list->programs_rev[$key]?>">
			<div class="tab-content">	
			<div class="get-page" data-relation="categories" data-relation_id="<?=$classes_list->programs_rev[$key]?>"></div>
			<?php 
				include plugin_dir_path( __FILE__ ) . 'snippets/select-class-table.php';
			?>
			</div>
			</div>
		<?php endforeach; ?>
</div>
</div>
<?php else: ?>
	<h4>No classes scheduled at this time</h4>
<?php endif; ?>