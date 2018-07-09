<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class editprofile_model extends CI_Model{
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
   
   public function driver_data($user_id)
   {
	    $this->db->select('*');
		$this->db->from('drivermaster');
		$this->db->join('usermaster', 'drivermaster.UserId = usermaster.Id');
		$this->db->join('vehiclemaster', 'drivermaster.Id = vehiclemaster.DriverId');
		$this->db->where('usermaster.Enable',1);
		$this->db->where('drivermaster.UserId',$user_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit();
		$result = $query->result();
		return $result;
   }
	/******* Update Driver Master details *********/
		
	public function update_user_data($user_update_array,$UserId)
	{	
		$this->db->where('Id',$UserId);
		$query = $this->db->update('usermaster',$user_update_array);
		$updated_user_status = $this->db->affected_rows();
		
		if($updated_user_status):
			return $UserId;
		else:
			return false;
		endif;
		
	}
	public function update_driver_data($driver_update_array,$userId,$drvr_id)
	{
		$this->db->where('UserId',$userId);
		$this->db->where('Id',$drvr_id);
		$query2 = $this->db->update('drivermaster',$driver_update_array);
		
		$updated_driver_status = $this->db->affected_rows();
		if($updated_driver_status):
			return $drvr_id;
		else:
			return false;
		endif;
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

/******* Update UserProfile Which RoleId is 1 Or 2 Mohammed *******/
	
	public function edit_profile_data($userid)
	{
		$query =$this->db->query("select ro.Name as companyname,reg.* from usermaster as reg Join companymaster as ro ON reg.CompanyId = ro.id where reg.Id='$userid' ");
		if($query)
		{
          $retailer = $query->result();
          return  $retailer;
        }
		else
		{
            return false;
        } 
    }
	public function update_data($update_array,$Id)
	{
		$this->db->where('Id',$Id);
		$query = $this->db->update('usermaster',$update_array);
		if($query)
		{
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
    }
}
?>