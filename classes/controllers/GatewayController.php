<?php
namespace DanceStudioManager;

class GatewayController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
	
	public function SubmitCard($data)
	{
		$data['dsm_action'] = 'gateway/card';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitACH($data)
	{
		$data['dsm_action'] = 'gateway/account';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitDefault($data)
	{
		$data['dsm_action'] = 'gateway/default';
		return parent::Submit($data);
	}
	
	public function SubmitAutopay($data)
	{
		$data['dsm_action'] = 'gateway/auto-pay';
		return parent::Submit($data);
	}
	
	public function Delete($data)
	{
		$data['dsm_action'] = 'gateway/delete';
		return parent::Delete($data);
	}
}