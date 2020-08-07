<?php
namespace DanceStudioManager;
$groupclasses = array();
$class_id = App::GetApi()->GetIdParam();
$schedule_id = $_POST['schedule_id'];

if (!empty($schedule_id))
	$class_full_info = App::GetClient()->GetController('classes')->GetScheduleInfo($class_id, $schedule_id);
else
	$class_full_info = App::GetClient()->GetController('classes')->GetInfo($class_id);
	
	
if (!empty($class_full_info->groupclasses)) {
	$groupclasses = json_decode(json_encode($class_full_info->groupclasses),true);
}

if (!empty($class_full_info->active_purchases)) {
	$active_purchases = json_decode(json_encode($class_full_info->active_purchases),true);
}

if (!empty($class_full_info->items)) {
	$items = json_decode(json_encode($class_full_info->items),true);
}

?>
<?php foreach($groupclasses as $groupclass): ?>
	Code: <?=$groupclass['CODE']?><br>
	Genre: <?=$groupclass['NAME']?><br>
	Level: <?=$groupclass['LEVEL']?><br>
	Location: <?=$groupclass['LOCATION']?><br>
	<?php
	if ($schedule_id)
		$label_schedule = $schedule_id;
	else if ($groupclass['SCHEDULE_ID'])
		$label_schedule = $groupclass['SCHEDULE_ID'];
	?>
	<?php if ($label_schedule): ?>
		<?php
			$info = App::GetClient()->GetController('classes')->GetScheduleInfo($class_id,$label_schedule);
			$schedule =  json_decode(json_encode($info->schedule),true);
			if ($schedule)
				echo 'Date and time: <span id="selected_schedule_date">'.$schedule['START_DATE'].' '.$schedule['START_TIME'].' - '.$schedule['END_TIME'].'</span><br>';
		?>
	<?php endif; ?>
	<?php if (DSM_OC_CLASS_SHOW_INSTRUCTOR == 1): ?>
		Instructor: <?=$groupclass['INSTRUCTOR']?><br />
		<?= ($groupclass['INSTRUCTOR2'] > 0 ) ? $groupclass['INSTRUCTOR2'].'<br />' : '' ?>
		<?= ($groupclass['INSTRUCTOR3'] > 0 ) ? $groupclass['INSTRUCTOR3'].'<br />' : '' ?>
		<br />
	<?php else: ?>
		<br />
	<?php endif; ?>

	<?= (DSM_OC_CLASS_SHOW_MAX_STUDENTS == 1 ) ? 'Max. Students: '.$groupclass['MAX_STUDENTS'].'<br />' : '' ?>
	<?= ($groupclass['MIN_AGE'] > 0 &&  $groupclass['MAX_AGE'] < 100) ? 'Age: '.$groupclass['MIN_AGE'].'-'.$groupclass['MAX_AGE'].'<br />' : '' ?>
	<br />
	<?= ($groupclass['DESCRIPTION']) ? '<br /><p>'.$groupclass['DESCRIPTION'].'</p>' : '' ?>
	<?= ($groupclass['PAGES']) ? '<div>'.str_replace('[:pg:]','<br /><br />',$groupclass['PAGES']).'</div>' : '' ?>
	<?= ($schedule_id) ? '<input type="hidden" id="selected_schedule_id" value="'.$schedule_id.'">' : '' ?>
	<?= ($groupclass['SCHEDULE_ID'] && empty($schedule_id)) ? '<input type="hidden" id="selected_schedule_id" value="'.$class['SCHEDULE_ID'].'">' : '' ?>
	
	<?php foreach ($items as $student): ?>
		<?php include plugin_dir_path( __FILE__ ) . 'select-class.php'; ?>
	<?php endforeach; ?>
<?php endforeach; ?>