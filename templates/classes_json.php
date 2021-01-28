<?php
namespace DanceStudioManager;
use \DateTime;

$add_data = array();
if(!empty($_REQUEST['start']))
	$add_data['start'] = $_REQUEST['start'];
	
if(!empty($_SESSION['dsm_client_attrs']))
	$classes_list = App::GetClient()->GetController('classes')->GetClasses((array)$_SESSION['dsm_client_attrs'] + json_decode(str_replace('\"','"',$_REQUEST['filter']),true) + $add_data);
else
	$classes_list = App::GetClient()->GetController('classes')->GetClasses(json_decode(str_replace('\"','"',$_REQUEST['filter']),true) + $add_data);


$i = 0;
$current_date = new DateTime($_REQUEST['start']);
$day = new DateTime($_REQUEST['start']);
$data = [
			'schedules' => [],
			'current_date' => $day->format('l, '.DSM_PHPDATE),
			'prev_date' => $day->modify('- 1 day')->format(DSM_PHPDATE),
			'next_date' => $day->modify('+ 2 day')->format(DSM_PHPDATE)
		];


foreach ($classes_list->schedules as $schedules) {
    if (is_array($schedules->data)) {
        foreach ($schedules->data as $schedule) {
			$schedule_start = new DateTime($schedule->START_DATE);
			if ($schedule_start->format(DSM_PHPDATE) == $current_date->format(DSM_PHPDATE)){
				$data['schedules'][$i] = $schedule;
			}
            $i++;
		}
    }
}

usort($data['schedules'], 'dsm_location_sort');

echo json_encode($data);