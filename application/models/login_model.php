<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	/***************** Check and validate login user ********************/
	public function validate($email,$password){
		
		$password =  md5($password);
		
		// $query = $this->db->query("SELECT * FROM usermaster WHERE `Email` = '$email' AND `Password` = '$password' AND `Enable` = '1'");
		/*** Only Super Admin and Admin allowed in dashboard login ***********/
		$query = $this->db->query("SELECT * FROM usermaster WHERE `Email` = '$email' AND `Password` = '$password' AND (RoleId='1' OR RoleId='2') AND `Enable` = '1'");

		//echo $this->db->last_query(); exit;
		$result = $query->result();
		$co_result =  count($result);
		if($co_result > 0)
		{
			
			$row = $query->row();
			$data = array(
					'userid' => $row->Id,
			       	'companyid' => $row->CompanyId,
					'name' => $row->Name,
					'email' => $row->Email,
					'roleid' => $row->RoleId,
					'lastlogin' => $row->LastLogin
					);
			$TIMESTAMP = date('Y-m-d H:i:s');
			$update_data=array('LastLogin'=>$TIMESTAMP);
			$this->db->where('Id',$row->Id);
			$this->db->update('usermaster',$update_data);
			$this->session->set_userdata('logged_in',$data);
			
			$myresult= array('result'=>1,'msg'=>"Login Successfully");
		}else{
			$myresult= array('result'=>0,'msg'=>"Invalid Username or Password");
		}
		
		return $myresult;
	}
	
	/****** login Check and validate Through API role wise *******/	
	public function login_validate($email,$password,$roleid)
	{
		//print_r($password);
		$password =  md5($password);
		//echo $password;

		$query = $this->db->query("SELECT * FROM usermaster as um
		join drivermaster as dm on dm.UserId = um.Id  
		join vehiclemaster as vm on vm.DriverId = dm.Id  
		WHERE um.Email = '$email' AND um.Password = '$password' AND um.RoleId='$roleid' AND dm.WorkingStatus = '1' AND um.Enable = '1'");
		
		$result = $query->result();
		$co_result =  count($result);
		//echo $this->db->last_query();exit;
		if($co_result > 0)
		{ 
	        // echo '1';exit;
			$row = $query->row();
			//print_r($row);exit;
			$data = array(
					'UserId' => $row->Id,
			       	'Companyid' => $row->CompanyId,
					'Name' => $row->Name,
					'Email' => $row->Email,
					'Roleid' => $row->RoleId,
					'Token' => $row->Token,
					'FCMRegId' => $row->FCMRegId,
					'DeviceUniquId' => $row->DeviceUniquId,
					'DeviceType' => $row->DeviceType,
					'Country' => $row->Country,
					'Building' => $row->Building,
					'Street' => $row->Street,
					'Suburb' => $row->Suburb,
					'State' => $row->State,
					'Postcode' => $row->Postcode,
					'Phoneno' => $row->Phoneno,
					'LicenceNo' => $row->LicenceNo,
					'ProfileImage' => base_url().$row->ProfileImage,
					'DocumentImage' => base_url().$row->DocumentImage,
					'EmailVerifyToken' => $row->EmailVerifyToken,
					'WorkingStatus' => $row->WorkingStatus,
					'VehicleType' => $row->VehicleType,
					'VehicleNumber' => $row->VehicleNumber,
					'CreatedAt' => $row->CreatedAt,
					'Lastlogin' => $row->LastLogin
					);
			// $data = $row;
			//print_r($data);exit;
			$TIMESTAMP = date('Y-m-d H:i:s');
			$update_data=array('LastLogin'=>$TIMESTAMP);
			$this->db->where('Id',$row->Id);
			$this->db->update('usermaster',$update_data);
			$this->session->set_userdata('logged_in');
			
			$myresult= array('result'=>1,'data'=>$data);
		}
		else
		{
			$myresult= array('result'=>0);
		}
		
		return $myresult;
	}

	/****** login Check and validate Through API role wise *******/	
	public function login_validate_customer($email,$password,$roleid)
	{
		//print_r($password);
		$password =  md5($password);
		//echo $password;
		
		$query = $this->db->query("SELECT * FROM usermaster as um
		join customermaster as cm on cm.UserId = um.Id
		WHERE um.Email = '$email' AND um.Password = '$password' AND um.RoleId='$roleid' AND um.Enable = '1'");
		$result = $query->result();
		$co_result =  count($result);
		//echo $this->db->last_query();exit;
		if($co_result > 0)
		{ 
	        // echo '1';exit;
			$row = $query->row();
			//print_r($row);//exit;
			$data = array(
					'UserId' => $row->Id,
			       	'Companyid' => $row->CompanyId,
					'Name' => $row->Name,
					'Email' => $row->Email,
					'Roleid' => $row->RoleId,
					'Token' => $row->Token,
					'FCMRegId' => $row->FCMRegId,
					'DeviceUniquId' => $row->DeviceUniquId,
					'DeviceType' => $row->DeviceType,
					'Country' => $row->Country,
					'Building' => $row->Building,
					'Street' => $row->Street,
					'Suburb' => $row->Suburb,
					'State' => $row->State,
					'Postcode' => $row->Postcode,
					'Phoneno' => $row->Phoneno,
					'ForgotEmailToken' => $row->ForgotEmailToken,					
					'CreatedAt' => $row->CreatedAt,
					'UpdatedAt' => $row->UpdatedAt,					
					'Lastlogin' => $row->LastLogin
					);
			// $data = $row;
			//print_r($data);exit;
			$TIMESTAMP = date('Y-m-d H:i:s');
			$update_data=array('LastLogin'=>$TIMESTAMP);
			$this->db->where('Id',$row->Id);
			$this->db->update('usermaster',$update_data);
			$this->session->set_userdata('logged_in');
			
			$myresult= array('result'=>1,'data'=>$data);
		}
		else
		{
			$myresult= array('result'=>0);
		}
		
		return $myresult;
	}
	
	/*****************fmcregister update fcm pushnotification details user ********************/
	public function fmcregister($userid,$fcmregid,$deviceuniquid,$devicetype){
		/*find email if already exits*/
		// $query = $this->db->query("SELECT * FROM usermaster WHERE Id=$userid AND Enable=1 AND RoleId=3");
		$query = $this->db->query("SELECT * FROM usermaster WHERE Id=$userid AND Enable=1");
		$result = $query->result();
		$co_result =  count($result);
	
		if($co_result > '0'){			
				
					$query_update = $this->db->query("UPDATE usermaster SET FCMRegId='".$fcmregid."',DeviceUniquId='".$deviceuniquid."',DeviceType='".$devicetype."' WHERE Id = '".$userid."'");					
					
					if($query_update){
					$myresult= array('result'=>1,'message'=>"Firebase Details Added Successfully");
					}else{
						$myresult= array('result'=>0,'message'=>"Something gone wrong. Please Try again");
					}					
				
			}else{
				/*no user are find related to current email so direct insert record to new id*/
				
				
				$myresult= array('result'=>0,'message'=>"No user details found.");
				
			}
			return $myresult;
		
		
	}
		
}
?>