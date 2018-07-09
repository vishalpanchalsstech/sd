<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class driverregister_model extends CI_Model{
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
   
	public function insert_user_data($user_insert_array)
	{
		$query = $this->db->insert('usermaster',$user_insert_array);
		$lst_user_id = $this->db->insert_id();
		
		return $lst_user_id;
		
	}
	public function insert_driver_data($driver_insert_array)
	{
		$query = $this->db->insert('drivermaster',$driver_insert_array);
		$lst_driver_id = $this->db->insert_id();
		
		return $lst_driver_id;
		
	}
	public function insert_vehicle_detail($vehicle_detail_array)
	{
		$query = $this->db->insert('vehiclemaster',$vehicle_detail_array);
		//$lst_user_id = $this->db->insert_id();
		
		if($query)
		{
			$result=1;
		}
		else
		{
			$result=0;
		}
		return $result;
		
	}
	public function verifydriver($email,$token)
	{
		$query = $this->db->query("SELECT um.Id FROM `usermaster` um JOIN drivermaster dm ON dm.UserId=um.Id WHERE um.Email = '$email' AND dm.EmailVerifyToken = '$token' ");
		//echo $this->db->last_query();
	    $querys = $query->result();
		//print_r($query->num_rows()); exit;
	   if( $query->num_rows() > 0){
		   $Id = $querys['0']->Id;
		   $update_array = array('Enable'=>1);
		 //  print_r($Id); exit;
		    $this->db->where('Id',$Id);
			$query = $this->db->update('usermaster',$update_array);
			
		return true;
	   }else{
			$result=0;
		}
		return $result;
		
	}

	public function get_driver_list($apiKey,$filter)
	{
		if($filter == "ALL")
		{
			//$query = $this->db->query("select um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.* from companydriver as cd Join companymaster as cm ON cd.CompanyId = cm.Id Join usermaster as um ON cd.DriverUserId=um.Id join drivermaster as D ON cd.DriverUserId=D.UserId join preferencesettings as Ps ON cd.CompanyId=Ps.CompanyId  WHERE cd.Enable= '1'  $apiToken");
			$this->db->select('um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.CompanyId as companyID,cd.DriverUserId as DriverUserId');
			$this->db->from('companydriver as cd');
			$this->db->join('companymaster as cm','cd.CompanyId = cm.Id');
			$this->db->join('usermaster as um','cd.DriverUserId=um.Id');
			$this->db->join('drivermaster as D','cd.DriverUserId=D.UserId');
			$this->db->join('preferencesettings as Ps','cd.CompanyId=Ps.CompanyId');
			$this->db->where('cd.Enable',1);
			$this->db->where('Ps.Token',$apiKey);
			$query = $this->db->get();
			
			if($query)
			{
				$allresult = $query->result();
				$All_result =  count($allresult);
				if($All_result > 0)
				{
					$myresult= array('result'=>1,'msg'=>"ALL Driver Data available",'data'=>$allresult);
				}
				else
				{
				  return false; 
				}
			}
			else
			{
				return false;
			}
		}
		elseif($filter == "OnlineNow")
		{
			//$query = $this->db->query("select um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.* from companydriver as cd Join companymaster as cm ON cd.CompanyId = cm.Id Join usermaster as um ON cd.DriverUserId=um.Id join drivermaster as D ON cd.DriverUserId=D.UserId join preferencesettings as Ps ON cd.CompanyId=Ps.CompanyId  WHERE cd.Enable= '1' AND D.WorkingStatus=1  $apiToken");
			
			$this->db->select('um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.CompanyId as companyID,cd.DriverUserId as DriverUserId');
			$this->db->from('companydriver as cd');
			$this->db->join('companymaster as cm','cd.CompanyId = cm.Id');
			$this->db->join('usermaster as um','cd.DriverUserId=um.Id');
			$this->db->join('drivermaster as D','cd.DriverUserId=D.UserId');
			$this->db->join('preferencesettings as Ps','cd.CompanyId=Ps.CompanyId');
			$this->db->where('cd.Enable',1);
			$this->db->where('D.WorkingStatus',1);
			$this->db->where('Ps.Token',$apiKey);
			$query = $this->db->get();
			
			if($query)
			{
				$online_result = $query->result();
				$onli_result =  count($online_result);
				if($onli_result > 0)
				{
					$myresult= array('result'=>1,'msg'=>"OnlineNow Drivers Data available",'data'=>$online_result);
				}
				else
				{
				  return false;
				}
			}
			else
			{
				return false;
			}
		}
		elseif($filter == "Activated")
		{
			//$query = $this->db->query("select um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.* from companydriver as cd Join companymaster as cm ON cd.CompanyId = cm.Id Join usermaster as um ON cd.DriverUserId=um.Id join drivermaster as D ON cd.DriverUserId=D.UserId join preferencesettings as Ps ON cd.CompanyId=Ps.CompanyId  WHERE cd.Enable= '1' AND D.WorkingStatus=0  $apiToken");
			
			$this->db->select('um.Name as name,D.WorkingStatus as DriverStatus,D.Phoneno as Phoneno,D.ProfileImage as ProfileImage,D.DocumentImage as DocumentImage,D.Id as Driver_ID, cd.CompanyId as companyID,cd.DriverUserId as DriverUserId');
			$this->db->from('companydriver as cd');
			$this->db->join('companymaster as cm','cd.CompanyId = cm.Id');
			$this->db->join('usermaster as um','cd.DriverUserId=um.Id');
			$this->db->join('drivermaster as D','cd.DriverUserId=D.UserId');
			$this->db->join('preferencesettings as Ps','cd.CompanyId=Ps.CompanyId');
			$this->db->where('cd.Enable',1);
			$this->db->where('D.WorkingStatus',0);
			$this->db->where('Ps.Token',$apiKey);
			$query = $this->db->get();
			
			if($query)
			{
				$activ_result = $query->result();
				$Acti_result =  count($activ_result);
				if($Acti_result > 0)
				{
					$myresult= array('result'=>1,'msg'=>"Activated Drivers Data available",'data'=>$activ_result);
				}
				else
				{
					return false;
				}
			}
			else
			{
				//$myresult= array('result'=>0,'msg'=>"Something gone wrong. Please Try again"); 
				return false;
			}
		}
		else
		{
			$myresult= array('success'=>0,'message'=>"Plz Enter Proper filter Criteria"); 
			echo json_encode($myresult);exit;		
		}
		return $myresult;
	} 
}
?>