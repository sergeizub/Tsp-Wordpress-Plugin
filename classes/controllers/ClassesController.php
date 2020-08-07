<?php
namespace DanceStudioManager;

class ClassesController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
	
	public function GetClassesList($filter = array())
	{
		$dsm_classes_list =  get_transient( 'dsm_classes_list');
		if(empty($dsm_classes_list)) {
			$dsm_classes_list = parent::GetList("classes/list");
			set_transient( 'dsm_classes_list', $dsm_classes_list, 24 * HOUR_IN_SECONDS );
		}
		
		$filters = $this->GetFilters();
		$programs = $programs_rev = array();
		foreach ($filters->name as $name) 
			$names[$name->value] = $name->label;
		foreach ($filters->level as $level) 
			$levels[$level->value] = $level->label;
		foreach ($filters->location as $location) 
			$locations[$location->value] = $location->label;
		foreach ($filters->program as $program) {
			$programs_rev[$program->label] = $program->value;
		}
		$dsm_classes_list->programs_rev = $programs_rev;

		
		
		//Filter Classes
		if(!empty($filter)) {
			$filter = dsm_array_map('html_entity_decode', $filter);
			foreach ($dsm_classes_list->groupclasses as $k_class => $class) {
				if ((!empty($filter['class_code']) && is_array($filter['class_code']) && !in_array($class->CODE, $filter['class_code']))
							|| (!empty($filter['class_code']) && !is_array($filter['class_code']) && $filter['class_code'] != $class->CODE)
							|| (!empty($filter['class_name']) && is_array($filter['class_name']) && !in_array($class->NAME, $filter['class_name']))
							|| (!empty($filter['class_name']) && !is_array($filter['class_name']) && $names[$filter['class_name']] != $class->NAME && $filter['class_name'] != $class->NAME)
							|| (!empty($filter['class_level']) && is_array($filter['class_level']) && !in_array($class->LEVEL, $filter['class_level']))
							|| (!empty($filter['class_level']) && !is_array($filter['class_level']) && $levels[$filter['class_level']] != $class->LEVEL  && $filter['class_level'] != $class->LEVEL)
							|| (!empty($filter['class_location']) && is_array($filter['class_location']) && !in_array($class->LOCATION, $filter['class_location']))
							|| (!empty($filter['class_location']) && !is_array($filter['class_location']) && $locations[$filter['class_location']] != $class->LOCATION && $filter['class_location'] != $class->LOCATION)
							|| (!empty($filter['class_program']) && is_array($filter['class_program']) && !in_array($class->PROGRAM, $filter['class_program']))
							|| (!empty($filter['class_program']) && !is_array($filter['class_program']) && $filter['class_program'] != $class->PROGRAM)
							)
						unset($dsm_classes_list->groupclasses[$k_class]);
			}
		}

		return $dsm_classes_list;
	}
	
	public function GetClasses($filter = array())
	{
		//$dsm_classes =  get_transient( 'dsm_classes' );
		if (empty($dsm_classes)) {
			$dsm_classes = parent::GetList("classes/?limit=100000");
			//set_transient( 'dsm_classes', $dsm_classes, 6 * HOUR_IN_SECONDS );
		}

		//Filter Schedules
		if(!empty($filter)) {
			$filter = dsm_array_map('html_entity_decode', $filter);
			foreach ($dsm_classes->schedules as $k_schedules => $schedules) {
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
							unset($dsm_classes->schedules[$k_schedules]->data[$k_schedule]);
					}
				}
			}
		}
		return $dsm_classes;
	}
	
	public function ClassesCalendar($data)
	{
		$data['dsm_action'] = 'classes-calendar';
		return parent::Submit($data);
	}
	
	public function GetInfo($data)
	{
		if(is_array($data) && !empty($data['class_id']))
			$id = $data['class_id'];
		else
			$id =  $data;
		
		$dsm_class_info =  get_transient( 'dsm_class_'.$id );
		if(empty($dsm_class_info)) {
			$dsm_class_info = parent::GetList("classes/".$id);
			set_transient( 'dsm_class_'.$id, $dsm_class_info, 6 * HOUR_IN_SECONDS );
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
		$data['dsm_action'] = 'classes/available-schedules';
		return parent::GetList($data);
	}
	
	public function SubmitFilter($data)
	{
		$data['dsm_action'] = 'classes';
		$dsm_classes_list = parent::GetList($data);
		return $dsm_classes_list;
	}
	
	public function GetCollection()
	{
		
		$schedules_list = $this->GetClasses();
	
		if ($schedules_list == false)
			return false;
		
		$calsses_list = array();
		foreach ($schedules_list->schedules as $a=>$b)
			foreach ($b->data as $c => $d) 
					if (!isset($calsses_list[$d->CLASS_ID]))
						$calsses_list[$d->CLASS_ID] = $d;
		
		return $calsses_list;
	}
	
	public function RegisterWithPurchasedItem($data)
	{
		$data['dsm_action'] = 'classes/register-purchased';
		return parent::Submit($data);
	}
}
