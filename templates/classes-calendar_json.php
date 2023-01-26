<?php
namespace TravelSportsPro;

$data = array();
if(!empty($_REQUEST['start']))
	$data['start'] = sanitize_text_field($_REQUEST['start']);
    
if(!empty($_REQUEST['end']))
	$data['end'] = sanitize_text_field($_REQUEST['end']);

$classes_list = App::GetClient()->GetController('classes')->GetClasses($data);

$i = 0;
$monthly_schedule = array();
foreach ($classes_list->schedules as $schedules) {
    if (is_array($schedules->data)) {
        foreach ($schedules->data as $schedule) {
            $title = '';
           
            
            if (!empty($schedule->NAME)) {
                $title .= $schedule->NAME . ', ';
            }
				
            $title = rtrim($title, ', ');
            
            if ($title == '') {
                $title = $schedule->CODE;
            }
            
			if ($schedule->STATUS == "2") {
                $title .= '- Cancelled';
            }
            
            $staff_title = ' ('.$schedule->INSTRUCTOR.') ';
            
            $student = explode('::', $schedule->STUDENT);

            $color = $schedule->COLOR ? '#'.$schedule->COLOR : '';
            $monthly_schedules[$i]['title'] = ($schedule->CLASS_TYPE != 'private' && $schedule->CLASS_TYPE != 'coaching') ? $title : $student[1].$staff_title.$notes;
            if (!empty($schedule->TEAMS_NAME))
                $monthly_schedules[$i]['title'] .= " - ".$schedule->TEAMS_NAME;
            $monthly_schedules[$i]['schedule_id'] = $schedule->ID;
            $monthly_schedules[$i]['class_id'] = $schedule->CLASS_ID;
            $monthly_schedules[$i]['class_type'] = $schedule->CLASS_TYPE;
            $monthly_schedules[$i]['start_date'] = $schedule->START_DATE;
            $monthly_schedules[$i]['start'] = $schedule->START;
            $monthly_schedules[$i]['end'] = $schedule->END;
            $monthly_schedules[$i]['num_students'] = str_replace(' +0', '', $schedule->STUDENTS_QUANTITY);
            $monthly_schedules[$i]['max_students'] = $schedule->MAX_STUDENTS;
            $monthly_schedules[$i]['instructor_id'] = $schedule->INSTRUCTOR_ID;
            $monthly_schedules[$i]['instructor_name'] =  $schedule->INSTRUCTOR;
            $monthly_schedules[$i]['allDay'] = false;
            $monthly_schedules[$i]['backgroundColor'] = $color;
            $monthly_schedules[$i]['borderColor'] = $color;
            $monthly_schedules[$i]['menu'] = 1;
            $i++;
		}
    }
}

echo json_encode($monthly_schedules);