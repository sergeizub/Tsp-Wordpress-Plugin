<?php
namespace TravelSportsPro;

class GatewayController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
	
	public function SubmitCard($data)
	{
		$data['tsp_action'] = 'gateway/card';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitACH($data)
	{
		$data['tsp_action'] = 'gateway/account';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitDefault($data)
	{
		$data['tsp_action'] = 'gateway/default';
		return parent::Submit($data);
	}
	
	public function SubmitAutopay($data)
	{
		$data['tsp_action'] = 'gateway/auto-pay';
		return parent::Submit($data);
	}
	
	public function Delete($data)
	{
		$data['tsp_action'] = 'gateway/delete';
		return parent::Delete($data);
	}
}