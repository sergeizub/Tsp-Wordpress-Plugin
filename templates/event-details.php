<?php
namespace TravelSportsPro;

$groupclasses = array();
$schedule_id = sanitize_key($_POST['schedule_id']);

$event_full_info = App::GetClient()->GetController('programs')->GetEventInfo($schedule_id);
$schedule = $event_full_info->schedule;
$location = $event_full_info->location;
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title"><?php echo $schedule->EVENT_TYPE; ?>:<?php echo $schedule->NAME; ?></h3>
</div>
<div class="modal-body">
	<?php if ($schedule->students) :?>
		<h5><?php echo $schedule->students; ?></h5>
		<br/>
	<?php endif; ?>
	<?php if ($schedule->teams) :?>
	<h5>Teams: <?php echo $schedule->teams; ?></h5>
	<br/>
	<?php endif; ?>
	<?php if ($schedule->START_F) :?>	
		<h5><span id="selected_schedule_date"><?php echo $schedule->START_F; ?> - <?php echo $schedule->END_F; ?></span></h5>
	<?php endif; ?>
	<?php if (TSP_OC_CLASS_DETAILS_LOCATION == '1'): ?>
	<br/>
	<h5><?php echo $location->NAME; ?> <?php if (!empty($location->GMAP_LINK)) : ?>   <a href="<?php echo $location->GMAP_LINK; ?>" target="_blank"><i class="fa fa-map-marker" aria-hidden="true"></i></a><?php endif; ?></h5>
	<h6><?php echo $location->ADDRESS; ?></h6>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	
</div>