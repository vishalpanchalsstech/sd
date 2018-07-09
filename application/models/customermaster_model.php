<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class customermaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function Get_customer_list()
	{
		$this->db->select('*');
		$this->db->from('customermaster');
		$this->db->join('usermaster', 'customermaster.UserId = usermaster.Id');
		$this->db->where('usermaster.Enable',1);
		$this->db->order_by('customermaster.Id', 'DESC'); 
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$result = $query->result();
		
		return $result;
    }
	
	public function insert_user_data($user_insert_array)
	{
		$query = $this->db->insert('usermaster',$user_insert_array);
		$lst_user_id = $this->db->insert_id();
		
		return $lst_user_id;
		
	}
	public function insert_customer_data($customer_insert_array)
	{
		$query = $this->db->insert('customermaster',$customer_insert_array);
		//$lst_driver_id = $this->db->insert_id();
		
		if($query)
		{
			$result=1;
		}
		else
		{
			$result=0;
		}
		return $result;
		//return $lst_driver_id;
		
	}
	
	/****** This Function for Edit Company Data  *******/
	
	public function edit_data($edit_id)
	{
		//echo $edit_id;exit;
		$this->db->select('*');
		$this->db->from('customermaster');
		$this->db->join('usermaster', 'customermaster.UserId = usermaster.Id');
		$this->db->where('customermaster.UserId', $edit_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$result = $query->result();
		return $result;
		
		/*
		$this->db->select('*');
		$this->db->from('drivermaster');
		$this->db->join('usermaster', 'drivermaster.UserId = usermaster.Id');
		$this->db->where('drivermaster.Id', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		*/
	}
	
	/******* Update Driver Master details *********/
		
	public function update_user_data($user_update_array,$UserId)
	{	
		$this->db->where('Id',$UserId);
		$query = $this->db->update('usermaster',$user_update_array);
		if($query)
		{
			return $UserId;
		}else{
			return false;
		}
		
	}
	public function update_customer_data($customer_update_array,$userId,$cstmr_id)
	{
		$this->db->where('UserId',$userId);
		$query2 = $this->db->update('customermaster',$customer_update_array);
		if($query2)
		{return $cstmr_id;
		}else{
			return false;
		}
	}
	
	
	/******** This Function for Delete Driver Master Data *******/
	
	public function Delete_data($delid)
	{
		$update_flag = array('Enable'=>'0');
		$this->db->where('Id',$delid);
		//$this->db->update('drivermaster',$update_flag);
		$this->db->update('usermaster',$update_flag);
		$del_suces = 1;
		return $del_suces;
		
	}
	
	/***************** Check and email duplicate or validate********************/
	 public function customer_email_validate($email)
	 {
		  $this->db->select('*');
		  $this->db->from('usermaster');
		  $where = '(usermaster.Email="'.$email.'" AND usermaster.RoleId = 4)'; 
		  $this->db->where($where);
		  $query = $this->db->get();
		  $result = $query->result();
		  return $result;
	 }

	 public function Get_customer_jobhistory($CustomerUserId)
	{
		$query = $this->db->query("SELECT * FROM `jobmaster` main where UserId=$CustomerUserId and main.Enable=1 AND CreatedAt = (SELECT MAX(CreatedAt) FROM jobmaster j GROUP BY JobId HAVING main.JobId = j.JobId) order by Id");
		// echo $this->db->last_query();exit;
		if($query)
		{
			$allresult = $query->result();
			$All_result =  count($allresult);
			if($All_result > 0)
			{
				$myresult= array('result'=>1,'msg'=>"Customer Job History available",'data'=>$allresult);
			}
			else
			{
				return false; 
			}
		}
		else
		{
			$myresult= array('success'=>0,'message'=>"No data found."); 
			echo json_encode($myresult);exit;
		}
		return $myresult;
	}

	/*********Get Customer Details through the UserId from customer_master table*/
	 public function get_customer_details($UserId,$customerid){
		 
		  if(!empty($UserId)){
			  $where='(UserId='.$UserId.')';
		  }	
		  if(!empty($customerid)){
			  $where='(Id='.$customerid.')';
		  }	
		  $this->db->select('*');
		  $this->db->from('customermaster');
		  //$this->db->where('UserId',$UserId);
		  $this->db->where($where);
		  $query = $this->db->get();
		  $result = $query->result();
		  return $result;
	 }
	 
	 /*****Update Customer Data through the customer Id*****/
	 public function update_customer_details($customerid,$custome_arr){
		 
		$this->db->where('Id',$customerid);
		$this->db->update('customermaster',$custome_arr);
		$updated_row = $this->db->affected_rows();
		if($updated_row==1)
		{
			$result = $customerid;
		}
		else
		{
			$result= false;
		}
		return $result;
	 }

	 public function Get_customer_jobTracking($UserId,$JobId)
	{
		$this->db->select('T.*');
		$this->db->from('tracking as T');
		$this->db->join('usermaster as U','U.Id=T.UserId');
		$this->db->where('T.UserId',$UserId);
		$this->db->where('T.JobId',$JobId);
		$this->db->where('T.Enable',1);
		$query = $this->db->get();
		
		if($query)
		{ 
			$Result = $query->result();
			$All_result =  count($Result);
			if($All_result > 0)
			{
				$myresult= array('result'=>1,'msg'=>"Customer jobTracking Data available",'data'=>$Result);
			}
			else
			{
				return false; 
			}
		}
		else
		{
			$myresult= array('success'=>0,'message'=>"No data found."); 
			echo json_encode($myresult);exit;
		}
		return $myresult;
	}
}
?>