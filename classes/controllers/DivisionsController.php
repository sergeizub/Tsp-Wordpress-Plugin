<?php
namespace TravelSportsPro;

class DivisionsController extends BaseController
{
  public function __construct()
    {
      parent::__construct();
    }
    
  public function LoadDivisions($data = array())
	{
		$data['tsp_action'] = 'divisions/';
        if (!empty($data['program_id']))
            $data['tsp_action'] .= 'by-program/'.$data['program_id'];
		return parent::GetList($data);
	}
}