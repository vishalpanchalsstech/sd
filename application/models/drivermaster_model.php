<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class drivermaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function Get_driver_Data()
	{
		
		//$this->db->select('*');
		$this->db->select('*,usermaster.Enable as UserEnable');
		$this->db->from('drivermaster');
		$this->db->join('usermaster', 'drivermaster.UserId = usermaster.Id');
		$this->db->join('vehiclemaster', 'drivermaster.Id = vehiclemaster.DriverId');
		// $this->db->where('usermaster.Enable',1);
		$this->db->order_by('usermaster.Id', 'DESC'); 
		$this->db->where('drivermaster.Enable', '1'); 
		$query = $this->db->get();
		$result = $query->result();
		//echo "<pre>";print_r($result);exit();
		return $result;
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
	/****** This Function for Edit Company Data  *******/
	
	public function edit_data($edit_id)
	{
		//echo $edit_id;exit;
		$this->db->select('*');
		$this->db->from('drivermaster');
		$this->db->join('usermaster', 'drivermaster.UserId = usermaster.Id');
		$this->db->join('vehiclemaster', 'drivermaster.Id = vehiclemaster.DriverId');
		$this->db->where('drivermaster.Id', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
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
	public function update_driver_data($driver_update_array,$userId)
	{
		$this->db->where('UserId',$userId);
		//$this->db->where('Id',$drvr_id);
		$query2 = $this->db->update('drivermaster',$driver_update_array);
		
		if($query2)
		{
			return $userId;
		}else{
			return false;
		}
	}
	public function update_vehicle_detail($vehicle_update_array,$driver_Id)
	{
		$this->db->where('DriverId',$driver_Id);
		$query = $this->db->update('vehiclemaster',$vehicle_update_array);
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
	
	/******** This Function for Delete Driver Master Data *******/
	
	public function Delete_data($delid)
	{
		//echo $delid;exit;
		$update_flag = array('Enable'=>'0');
		$this->db->where('UserId',$delid);
		$this->db->update('drivermaster',$update_flag);
		// $this->db->update('usermaster',$update_flag);
		$del_suces = 1;
		return $del_suces;
		
	}
	

	/******* Update Driver Working status *********/
		
	public function UpdateWorkingStatus($DriverUserId,$update_array)
	{	
		
		$this->db->where('UserId',$DriverUserId);
		$query = $this->db->update('drivermaster',$update_array);
		if($query)
		{
			return true;
		}else{
			return false;
		}
		
	}

	/*Get Particular Driver Details through the Driver Id */
	public function get_driver_details($driver_id){
		
		if(!empty($driver_id)){
			$this->db->select('drivermaster.Id,usermaster.Name,drivermaster.Phoneno,drivermaster.ProfileImage');
			$this->db->from('drivermaster');
			$this->db->join('usermaster', 'drivermaster.UserId = usermaster.Id');
			$where = '(drivermaster.Enable=1 AND drivermaster.Id = '.$driver_id.')'; 
			$this->db->where($where);
			$query = $this->db->get();
			$result = $query->result();
			//echo "<pre>";print_r($result);exit();
			return $result;
		}
		
	}
	
	/**check jobexist or not in tracking table**/
	public function jobexist_tracking($DriverUserId,$jobid){
		
		$this->db->select('*');
		$this->db->from('tracking');
		$this->db->where('JobId',$jobid);
		$this->db->where('DriverId',$DriverUserId);
		$query=$this->db->get();
		return $query->result();
	}
	 /**Update Job Location in Tracking Table**/
	 public function update_joblocation($location,$jobid,$DriverId){
		$this->db->where('JobId',$jobid);
		$this->db->where('DriverId',$DriverId);
		$query = $this->db->update('tracking',$location);
		if($query)
		{
			return true;
		}else{
			return false;
		}
	 }
}
?>