<?php
namespace TravelSportsPro;

class TeamsController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
    
    public function LoadTeams($data = array())
	{
		$data['tsp_action'] = 'teams/';
        if (!empty($data['program_id']))
            $data['tsp_action'] .= 'by-program/'.$data['program_id'];
		return parent::GetList($data);
	}
}