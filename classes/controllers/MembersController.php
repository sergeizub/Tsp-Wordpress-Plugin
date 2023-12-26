<?php
namespace TravelSportsPro;

class MembersController extends BaseController
{
    protected $scheduled_payment_statuses = ['1'=>'Scheduled', '2'=>'Complete', '3'=>'Processing Error', '4'=>'On Hold', '5'=>'Terminated', '6'=>'Processing'];
    
    public function __construct()
    {
      parent::__construct();
    }
	
	public function Submit($data)
	{
		$data['tsp_action'] = 'members/edit';
		
		if ($data['I_AM'] == 'adult-student') {
			$data['IS_STUDENT'] = 1;
			$data['IS_GUARDIAN'] = 0;
		}
		elseif ($data['I_AM'] == 'guardian') {
			$data['IS_STUDENT'] = 0;
			$data['IS_GUARDIAN'] = 1;
		}
		elseif ($data['I_AM'] == 'guardian-student') {
			$data['IS_STUDENT'] = 1;
			$data['IS_GUARDIAN'] = 1;
		}
		
		$result = parent::Submit($data);
		
		$error_fields = array();
		if ($result->success == true) {
			App::GetError()->Success("Profile Updated.");
			unset($_POST);
		}
		elseif ($result->errors) {
			foreach ($result->errors as $k=>$v) {
				array_push($error_fields, $k);
				$msg .= "<br/>".$v;
			}
			App::GetError()->Show("Unable Update Profile.".$msg);
		}
		
		return $result;
	}
	
	public function SubmitStudent($data)
	{
		if ($data['student_id'])
			$data['tsp_action'] = 'members/edit/'.$data['student_id'];
		else
			$data['tsp_action'] = 'members/student';

		$result = parent::Submit($data);
		
		$error_fields = array();
		if ($result->success == true) {
			App::GetError()->Success("Player Submitted.");
			unset($_POST);
		}
		elseif ($result->errors) {
			foreach ($result_register->errors as $k=>$v) {
				array_push($error_fields, $k);
				$msg .= "<br/>".$v;
			}
			App::GetError()->Show("Unable Submit Student.".$msg);
		}
		return $result;
	}

    public function ChangePassword($data)
    {
		$data['tsp_action'] = 'members/change-password';
        $result = parent::Submit($data);
        return $result;
    }
	
	public function DeleteStudent($data)
	{
		$data['tsp_action'] = 'members/student/'.$data['student_id'];
		return parent::Delete($data);
	}
    
    public function RedeemGiftCard($data)
	{
        $data['tsp_action'] = 'members/gift-cards-redeem';
		$result = parent::Submit($data);
        return $result;
    }
	
	public function GetName()
	{
		$profile = $this->GetUserData();
		$name = '';
		if(!empty($profile)) {
			if (!empty($profile->FIRSTNAME))
				$name .= $profile->FIRSTNAME;
			if (!empty($profile->FIRSTNAME) && !empty($profile->LASTNAME))
				$name .= ' ';
			if (!empty($profile->LASTNAME))
				$name .= $profile->LASTNAME;
			}
		return $name;
	}
	
	public function GetUser($id = null)
	{
		
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");

		if(!empty($profile))
			return  $profile;
		else
			return NULL;
	}
	
	public function GetUserData($id = null)
	{
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");
		if(!empty($profile->user_data))
			return  $profile->user_data;
		elseif(!empty($profile->data))
			return $profile->data;
		else
			return NULL;
	}
	
	public function GetUserForm($id = null)
	{
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");
		$form = $profile->form;
		return $form;
	}
	
	public function GetChildList()
	{
		return parent::GetList("members/family");
	}
	
	public function GetStudentForm()
	{
		return parent::GetList("members/student");
	}
    
    public function GetMyClasses()
	{
		return parent::GetList("classes/my");
	}
    
    public function GetPrivateLessonsTotals()
	{
		return parent::GetList("members/private-lessons-totals");
	}
	
	public function GetPayments()
	{
	 return parent::GetList("members/payments");
	}
    
    public function GetScheduledPayments($data = array())
	{
        $data['tsp_action'] = "members/scheduled-payments";
        return parent::GetList($data);
	}
    
    public function GetScheduledPaymentsStatuses()
	{
        return $this->scheduled_payment_statuses;
	}
    
    public function GetScheduledPaymentsStatusLabel($id)
	{
        if (!empty($this->scheduled_payment_statuses[$id]))
            return $this->scheduled_payment_statuses[$id];
        else
            return '';
	}
	
	public function GetCardsAccounts($params = array())
	{
		if (!empty($params))
			$get_string = '?'.http_build_query($params);
		$cards_accounts = json_decode(json_encode(parent::GetList("members/cards-accounts".$get_string)),true);
		return $cards_accounts['payment_sources'];
	}
	
	public function GetCharges()
	{
	 return parent::GetList("members/charges");
	}
	
	public function GetPurchases()
	{
	 return parent::GetList("members/purchases");
	}
	
    public function GetGiftCards()
	{
	 return parent::GetList("members/gift-cards");
	}
    
	public function GetWaivers()
	{
	 return parent::GetList("members/waivers");
	}

	public function GetWaiver($id)
	{
		$waivers =  $this->GetWaivers();

	if (!empty($waivers->data))
    	foreach($waivers->data as $waiver) {
        	if (!empty($waiver->ID) && $waiver->ID == $id)
            	return $waiver;
    	}
	 return false;
	}

	public function SignWaiver($data)
	{
		$data['tsp_action'] = 'members/waivers/'.$data['id'];
		parent::Submit($data);
		exit(true);
	}
    
    public function SubmitPhoto($data)
    {
        if (empty($data['student_id']) || !is_numeric($data['student_id']))
            return false;
        
        $temp_dir = dirname(__FILE__).'/../../tmp';
        if (!file_exists($temp_dir))
			mkdir($temp_dir);
        
        //Delteting files from temp dir
        $files = glob($temp_dir.'/*'); 
        foreach($files as $file)
            if(is_file($file)) 
                unlink($file); 
        
        $savePath = $temp_dir.'/'.$data['student_id'].'.jpg';
        $saveName = $data['student_id'].'.jpg';
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $savePath)) {		
            $data['tsp_action'] = 'members/photo/'.$data['student_id'];
            $data['file'] = $savePath;
            $data['name'] =  "photo";
            $data['filename'] =  $saveName;
            return parent::SubmitFile($data);
        }
        return false;
    }
    
    public function DeletePhoto($data)
	{
		$data['tsp_action'] = 'members/photo/'.$data['student_id'];
		return parent::Delete($data);
	}
}