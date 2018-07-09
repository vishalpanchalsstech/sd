<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class forgotpassword_model extends CI_Model{
    function __construct() 
	{
        parent::__construct();
        $this->load->database();
    }
    public function chk_mail_roleId($Email,$RoleId)
	{
		//$this->db->select('Email,RoleId');
		$this->db->select('*');
		$this->db->from('usermaster');
		$this->db->where('usermaster.Email',$Email);
		//$this->db->where_in('usermaster.RoleId',array('3','2'));
		$this->db->where('usermaster.RoleId',$RoleId);
		$this->db->where('usermaster.Enable',1);
		$query = $this->db->get();
		$result = $query->result();
		
		if(!empty($result))
		{
		  $myresult= array('result'=>1,'msg'=>"Email available",'data'=>$result);
		}
		else
		{
		  $myresult= array('result'=>0,'msg'=>"Invalid Email"); 
	    }
		
		return $myresult;
	}
	
	public function insert_driver_ForgotEmailToken($driver_ForgotEmailToken,$userId)
	{
		$this->db->where('UserId',$userId);
		$query = $this->db->update('drivermaster',$driver_ForgotEmailToken);
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
	public function insert_customer_ForgotEmailToken($customer_ForgotEmailToken,$userId)
	{
		$this->db->where('UserId',$userId);
		$query = $this->db->update('customermaster',$customer_ForgotEmailToken);
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

/***Chk customer ForgotEmailToken with Roleid=4 is Exist in customermaster table or not @KKG ***/	

	public function chk_customer_frgtemailtoken($ForgotEmailToken,$RoleId)
	{
		// $this->db->select('C.*');
		$this->db->select('C.*,U.Token as Token');
		$this->db->from('customermaster as C');
		$this->db->join('usermaster as U','U.Id=C.UserId');
		$this->db->where('C.ForgotEmailToken',$ForgotEmailToken);
		$this->db->where('U.RoleId',$RoleId);
		$this->db->where('U.Enable',1);
		$query = $this->db->get();
		$result = $query->result();
		
		if(!empty($result))
		{
		  $myresult= array('result'=>1,'msg'=>"Customer ForgotEmailToken match",'data'=>$result);
		}
		else
		{
		  $myresult= array('result'=>0,'msg'=>"Invalid ForgotEmailToken"); 
	    }
		
		return $myresult;
		
	}
	public function update_pass($UserId,$update_cust_array)
	{
		$this->db->where('Id',$UserId);
		$query = $this->db->update('usermaster',$update_cust_array);
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
	
/***Chk Driver ForgotEmailToken with Roleid=3 is Exist in drivermaster table or not @KKG ***/	

	public function chk_driver_frgtemailtoken($ForgotEmailToken,$RoleId)
	{
		// $this->db->select('D.*');
		$this->db->select('D.*,U.Token as Token');
		$this->db->from('drivermaster as D');
		$this->db->join('usermaster as U','U.Id=D.UserId');
		$this->db->where('D.ForgotEmailToken',$ForgotEmailToken);
		$this->db->where('U.RoleId',$RoleId);
		$this->db->where('U.Enable',1);
		$query = $this->db->get();
		$result = $query->result();
		
		if(!empty($result))
		{
		  $myresult= array('result'=>1,'msg'=>"Driver ForgotEmailToken match",'data'=>$result);
		}
		else
		{
		  $myresult= array('result'=>0,'msg'=>"Invalid ForgotEmailToken for Driver"); 
	    }
		
		return $myresult;
		
	}
	public function update_driver_pass($UserId,$update_driver_array)
	{
		$this->db->where('Id',$UserId);
		$query = $this->db->update('usermaster',$update_driver_array);
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
}
?>