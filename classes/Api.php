<?php
namespace DanceStudioManager;

class Api
{
    protected $token = null;
    protected $url = null;
	protected $api_key = null;
    protected $api_version = null;
	protected $api_version_list = array('v1' => 'v1');
	protected $id_param = null;

    public function __construct()
    {
        $this->url = get_option('dsm_api_url');
		$this->api_key = get_option('dsm_api_key');
        $this->api_version = get_option('dsm_api_version');

    }

	public function ValidateUrl()
	{
        if (filter_var( $this->url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) !== false)
			return true;
		else
			App::GetError()->Show("Sumbit Valid DSM Url");
    }

	public function ValidateApiVersion()
	{
        if (in_array($this->api_version,$this->api_version_list))
			return true;
		else
			App::GetError()->Show("Select Valid Api Version");
    }

	public function ValidateDSMUrl()
	{
		if (!$this->ValidateUrl())
			return false;

		if (!$this->ValidateApiVersion())
			return false;

		return true;
	}

	public function GetApiVersionList()
	{
		return $this->api_version_list;
	}

	public function SetIdParam($id_param)
	{
		$this->id_param = $id_param;
	}

	public function GetIdParam()
	{
		return  $this->id_param;
	}

    public function GetAuthorizationToken()
	{
		$auth_token = App::GetClient()->GetController('auth')->GetAuthToken();
		if (!empty($auth_token))
			$this->token = $auth_token;

		if (!empty($this->token))
			return "Authorization:Bearer ".$this->token;
		else
			return false;
    }

	public function ClassInfo($class_id)
	{
		if(!$this->ValidateDSMUrl())
			return false;

		$authorization_token = App::GetApi()->GetAuthorizationToken();
		if (!empty($authorization_token) && !empty($class_id)) {
			$response = $this->GetList('classes/'.$class_id);
			if (is_object($response->groupclasses) || is_array($response->groupclasses))
				foreach ($response->groupclasses as $k => $v)
					$result = $v;

			return $result;
		}
		return false;
	}

	public function ClassesListCollection()
	{

		$schedules_list = $this->GetList('classes/');

		if (!$this->ValidateDSMUrl() || $schedules_list == false)
			return false;
		$calsses_list = array();

		foreach ($schedules_list->schedules as $a=>$b) {
			foreach ($b->data as $c => $d) {
					if (!isset($calsses_list[$d->CLASS_ID]))
					{
						$calsses_list[$d->CLASS_ID] = $d;
					}
				}
		}
		return $calsses_list;
	}

	public function Submit($post)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		if (empty($post['dsm_action']))
			return false;

		$post_action = str_replace('_','/',$post['dsm_action']);

		$ch = curl_init();
		if ($post['dsm_action'] != 'auth/login' && $post['dsm_action'] != 'auth/register')
			$authorization_token = App::GetApi()->GetAuthorizationToken();

		if($post_action == 'members/edit' && !empty($this->GetIdParam()))
			$post_action .= '/'.$this->GetIdParam();

		$httpheader = array('Content-Type: application/json');

		if (!empty($this->api_key))
			array_push($httpheader, 'x-api-key: '.$this->api_key);

		if (!empty($authorization_token))
			array_push($httpheader, $authorization_token);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);

		curl_setopt($ch, CURLOPT_URL, $this->url."api/".$this->api_version.'/'.$post_action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		elseif (!empty($response->errors) && !empty($response->errors->message)) {
			App::GetError()->Show($response->errors->message);
			return false;
		}
		elseif (isset($response->success) && $response->success == false) {
			if (!empty($response->message)) {
				App::GetError()->Show($response->message);
				return false;
			}
			elseif (!empty($response->errors))
				if (is_array($response->errors) || is_object($response->errors))
					foreach ($response->errors as $k_error => $v_error) {
						App::GetError()->Show($k_error.":".$v_error);
						echo '<br/>';
						return false;
					}
				else {
					App::GetError()->Show($response->errors);
					return false;
				}
			elseif (!empty($response->system)) {
				App::GetError()->Show($response->system);
				return false;
			}
			return true;
		}
		else {
			if (!empty($response->token))
				$this->token = $response->token;

			return $response;
		}
	}

	public function Delete($post)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		if (empty($post['dsm_action']))
			return false;

		$post_action = str_replace('_','/',$post['dsm_action']);
		unset($post['dsm_action']);

		$ch = curl_init();
		$authorization_token = App::GetApi()->GetAuthorizationToken();

		$httpheader = array('Content-Type: application/json');

		if (!empty($this->api_key))
			array_push($httpheader, 'x-api-key: '.$this->api_key);

		if (!empty($authorization_token))
			array_push($httpheader, $authorization_token);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_URL, $this->url."api/".$this->api_version.'/'.$post_action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		else if (!empty($response->errors) && !empty($response->errors->message)) {
			App::GetError()->Show($response->errors->message);
			return false;
		}
		elseif (isset($response->success) && $response->success == false) {
			if (!empty($response->message))
				App::GetError()->Show($response->message);
			elseif (!empty($response->errors))
				foreach($response->errors as $k_error => $v_error)
					App::GetError()->Show($k_error.":".$v_error);
			elseif (!empty($response->system))
				App::GetError()->Show($response->system);
			return false;
		}
		elseif (isset($response->success) && $response->success == true) {
			$this->SetIdParam(NULL);
			return $response;
		}
		else {
			return $response;
		}
	}

	public function GetList($get)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		$ch = curl_init();

		if (empty($get))
			return false;
		elseif (is_array($get)) {
			$action = $get['dsm_action'];
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($get));
		}
		else
			$action = $get;

		//$action = str_replace('_','/',$action);
		$action = str_replace('schedule/id','schedule_id',$action);

		$dsm_action_path = explode("/",$action);

		if($action != 'auth/login' && $action != 'auth/register' && $action != 'classes/filters/')
			$authorization_token = App::GetApi()->GetAuthorizationToken();

		if($action == 'members/edit' && !empty($this->GetIdParam()))
			$action .= '/'.$this->GetIdParam();

		$httpheader = array('Content-Type: application/json');

		if (!empty($this->api_key))
			array_push($httpheader, 'x-api-key: '.$this->api_key);

		if (!empty($authorization_token))
			array_push($httpheader, $authorization_token);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_URL, $this->url."api/".$this->api_version."/".$action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		$response = json_decode(curl_exec($ch));

		curl_close($ch);
		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		else if (isset($response->success) && $response->success == false) {
			App::GetError()->Show($response->message);
			return false;
		}
		else {
			return $response;
		}
	}
}