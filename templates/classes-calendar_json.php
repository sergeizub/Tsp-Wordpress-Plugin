<?php
namespace TravelSportsPro;

$data = array();
if(!empty($_REQUEST['start']))
	$data['start'] = sanitize_text_field($_REQUEST['start']);
    
if(!empty($_REQUEST['end']))
	$data['end'] = sanitize_text_field($_REQUEST['end']);

$classes_list =  App::GetClient()->GetController('programs')->GetMyEvents($data);

$i = 0;
$monthly_schedules = array();
foreach ($classes_list->schedules as $schedule) {
    $monthly_schedules[] = (array)$schedule;
}

echo json_encode($monthly_schedules);