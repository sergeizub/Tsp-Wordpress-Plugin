<?php
namespace TravelSportsPro;

class ProgramsController extends ClassesController
{
    public function __construct()
    {
      parent::__construct();
    }
    
    public function RegisterInit($data = array())
	{
		$data['tsp_action'] = 'programs/registration';
		return parent::GetList($data);
	}
    
    public function LoadPrograms($data = array())
	{
		$data['tsp_action'] = 'programs/';
        if (!empty($data['team_id']))
            $data['tsp_action'] .= 'by-team/'.$data['team_id'];
		return parent::GetList($data);
	}
    
    public function LoadPaymentPlans($data = array())
	{
		$data['tsp_action'] = 'programs/payment-plans/';
        if (!empty($data['program_id']))
            $data['tsp_action'] .= $data['program_id'];
		return parent::GetList($data);
	}
}