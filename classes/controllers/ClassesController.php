<?php
namespace TravelSportsPro;

class ClassesController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }

	public function GetClasses($filter = array())
	{
		//Prepare filter for Api - ignore array values
		foreach ($filter as $k_filter => $v_filter) {
			if (!is_array($v_filter) && !empty($v_filter))
				$data[$k_filter] = $v_filter;
		}

		$data['limit'] = '100000';
		$tsp_classes = parent::GetList('classes/?'.http_build_query($data));

		//Filter Schedules
		if(!empty($filter)) {
			$filter = tsp_array_map('html_entity_decode', $filter);
			foreach ($tsp_classes->schedules as $k_schedules => $schedules) {
				if (is_array($schedules->data)) {
					foreach ($schedules->data as $k_schedule => $schedule) {
						if ((!empty($filter['class_code']) && is_array($filter['class_code']) && !in_array($schedule->CODE, $filter['class_code']))
							|| (!empty($filter['class_code']) && !is_array($filter['class_code']) && $filter['class_code'] != $schedule->CODE)
							|| (!empty($filter['class_name']) && is_array($filter['class_name']) && !in_array($schedule->NAME, $filter['class_name']))
							|| (!empty($filter['class_name']) && !is_array($filter['class_name']) && $filter['class_name'] != $schedule->NAME && $filter['class_name'] != $schedule->NAME_ID)
							|| (!empty($filter['class_level']) && is_array($filter['class_level']) && !in_array($schedule->LEVEL, $filter['class_level']))
							|| (!empty($filter['class_level']) && !is_array($filter['class_level']) && $filter['class_level'] != $schedule->LEVEL  && $filter['class_level'] != $schedule->LEVEL_ID)
							|| (!empty($filter['class_location']) && is_array($filter['class_location']) && !in_array($schedule->LOCATION, $filter['class_location']))
							|| (!empty($filter['class_location']) && !is_array($filter['class_location']) && $filter['class_location'] != $schedule->LOCATION && $filter['class_location'] != $schedule->LOCATION_ID)
							|| (!empty($filter['class_program']) && is_array($filter['class_program']) && !in_array($schedule->PROGRAM, $filter['class_program']))
							|| (!empty($filter['class_program']) && !is_array($filter['class_program']) && $filter['class_program'] != $schedule->PROGRAM)
							)
							unset($tsp_classes->schedules[$k_schedules]->data[$k_schedule]);
					}
				}
			}
		}
		return $tsp_classes;
	}

	public function GetClassesData($filter)
	{
		//Prepare filter for Api - ignore array values
		foreach ($filter as $k_filter => $v_filter) {
			if (!is_array($v_filter) && !empty($v_filter))
				$data[$k_filter] = $v_filter;
		}
		if($data['class_code'])
			$data['class_code'] = htmlspecialchars_decode($data['class_code']);
		
		$data['tsp_action'] = 'classes/data';
		$tsp_classes = parent::GetList($data);
        $all_filters = json_decode(json_encode($tsp_classes->filters),true);
        $name_filters = $level_filters = $location_filters = array();
  
        foreach($all_filters["name"] as $v)
            $name_filters[$v["value"]] = $v['label'];
        
        foreach($all_filters["level"] as $v)
            $level_filters[$v["value"]] = $v['label'];
            
        foreach($all_filters["location"] as $v)
            $location_filters[$v["value"]] = $v['label'];
            
        if (!empty($filter['start_date']))
            $start_date = strtotime($filter['start_date']);
        else
            $start_date = "";
        
        if (!empty($filter['end_date']))
            $end_date = strtotime($filter['end_date']);
        else
            $end_date = "";
        
		if(!empty($filter)) {
			$filter = tsp_array_map('html_entity_decode', $filter);
			foreach ($tsp_classes->groupclasses as $k_groupclass => $groupclass) {
               
				if ((!empty($filter['class_code']) && is_array($filter['class_code']) && !in_array($groupclass->CODE, $filter['class_code']))
                    || (!empty($filter['class_code']) && !is_array($filter['class_code']) && $filter['class_code'] != $groupclass->CODE)
					|| (!empty($filter['class_name']) && is_array($filter['class_name']) && !in_array($groupclass->NAME, $filter['class_name']))
                    || (!empty($filter['class_name']) && !is_array($filter['class_name']) && $filter['class_name'] != $groupclass->NAME && $name_filters[$filter['class_name']] != $groupclass->NAME)	
					|| (!empty($filter['class_level']) && is_array($filter['class_level']) && !in_array($groupclass->LEVEL, $filter['class_level']))
                    || (!empty($filter['class_level']) && !is_array($filter['class_level']) && $filter['class_level'] != $groupclass->LEVEL && $level_filters[$filter['class_level']] != $groupclass->LEVEL)
					|| (!empty($filter['class_location']) && is_array($filter['class_location']) && !in_array($groupclass->LOCATION, $filter['class_location']))
                    || (!empty($filter['class_location']) && !is_array($filter['class_location']) && $filter['class_location'] != $groupclass->LOCATION && $location_filters[$filter['class_location']] != $groupclass->LOCATION)
					|| (!empty($filter['class_program']) && is_array($filter['class_program']) && !in_array($groupclass->PROGRAM, $filter['class_program']))
                    || (!empty($filter['class_program']) && !is_array($filter['class_program']) && $filter['class_program'] != $groupclass->PROGRAM)
					)
					unset($tsp_classes->groupclasses[$k_groupclass]);

                if (!empty($tsp_classes->groupclasses[$k_groupclass]) && !empty($tsp_classes->groupclasses[$k_groupclass]->SCHEDULES)
                        && (!empty($start_date) || !empty($end_date))) {
                    foreach ($tsp_classes->groupclasses[$k_groupclass]->SCHEDULES as $k_schedule => $schedule) {
                        if ((!empty($start_date) && $start_date > strtotime($schedule->TITLE))
                            || (!empty($end_date) && $end_date < strtotime($schedule->TITLE))
                            )
                            unset($tsp_classes->groupclasses[$k_groupclass]->SCHEDULES[$k_schedule]);
                    }
                }
            }
            
		}
		return $tsp_classes;
	}

	public function ClassesCalendar($data)
	{
		$data['tsp_action'] = 'classes-calendar';
		return parent::Submit($data);
	}

	public function GetInfo($data)
	{
		if(is_array($data) && !empty($data['class_id']))
			$id = $data['class_id'];
		else
			$id =  $data;
		if (get_option('tsp_class_cache') == '1')
			$tsp_class_info =  get_transient( 'tsp_class_'.$id );
		if(empty($tsp_class_info)) {
			$tsp_class_info = parent::GetList("classes/".$id);
			if (get_option('tsp_class_cache') == '1')
				set_transient( 'tsp_class_'.$id, $tsp_class_info, 6 * HOUR_IN_SECONDS );
		}

	 return parent::GetList("classes/$id");
	}

	public function GetScheduleInfo($class_id,$schedule_id)
	{
		if ($class_id && $schedule_id)
			return parent::GetList("classes/".$class_id."?schedule_id=".$schedule_id);
		else
			return false;
	}

	public function GetFilters()
	{
	 return parent::GetList("classes/filters");
	}

	public function GetAvailableSchedules($data)
	{
		$data['tsp_action'] = 'classes/available-schedules';
		return parent::GetList($data);
	}
    
    public function GetAvailableSchedulesJson($data)
	{
		echo  json_encode($this->GetAvailableSchedules($data));
	}

	public function SubmitFilter($data)
	{
		$data['tsp_action'] = 'classes';
		$tsp_classes_list = parent::GetList($data);
		return $tsp_classes_list;
	}

	public function RegisterWithPurchasedItem($data)
	{
		$data['tsp_action'] = 'classes/register-purchased';
		return parent::Submit($data);
	}
}