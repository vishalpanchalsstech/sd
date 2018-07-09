<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class priority_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}

/****** This function used for the get company details by token from preference settings *****/
	public function GetCompanyByTokenPreference($token)
	{
		$this->db->select('*');
		$this->db->from('preferencesettings as ps');
		$this->db->join('companymaster as cm', 'cm.Id = ps.CompanyId');
		$this->db->where('Token',$token);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
/**** To Check That job is Exist in jobmaster table Or Not @ KKG @ ****/

	public function Check_JobExistance($JobId,$CompanyId)
	{
		/*$this->db->select("*");
		$this->db->from("jobmaster");
		$where = '(JobId="'.$JobId.'" AND JobStatus = 1)'; 
		$this->db->where($where);
		$this->db->where('CompanyId',$CompanyId);
		$query = $this->db->get();
		return $query->result(); */
		
		$query = $this->db->query("SELECT * FROM `jobmaster` WHERE CompanyId='".$CompanyId."' AND jobstatus=1 AND JobId='".$JobId."' AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE  jobstatus IN('2','3','4','5'))");
		$result = $query->result();
		return $result;
	}
	
	public function Get_Notification_method($CompanyId)
	{
		$this->db->select("*");
		$this->db->from("preferencesettings");
		$this->db->where('preferencesettings.CompanyId',$CompanyId);
		$query = $this->db->get();
		return $query->result();
	}
	
/***** Get CompanyDriver Details @ KKG @ *****/

	public function get_companydriver_details($companyid)
	{
		$this->db->select('*');
		$this->db->from('companydriver as Cd');
		$this->db->join('usermaster as Um','Um.Id=Cd.DriverUserId');
		$this->db->join('drivermaster as Dm','Dm.UserId=Cd.DriverUserId');
		$this->db->where('Cd.CompanyId',$companyid);
        $this->db->where('Um.Enable', 1);
        $this->db->where('Um.RoleId', 3);
		//$this->db->order_by("Cd.Priority", "asc");
		$query = $this->db->get();
		
		if($query)
		{
			$result = $query->result();
			return $result;
		}
		else
		{
			return false;
		}
		
		/*	  $this->db->select('*');
			  $this->db->from('companydriver');
			  $this->db->where('CompanyId',$companyid);
			  $query = $this->db->get();
			  $result = $query->result();
			  return $result;
		  
		 */
	}
	
	public function Update_jobpriority_field($Jobmaster_id,$update_array)
	{
		
		$this->db->where('Id',$Jobmaster_id);
		$query = $this->db->update('jobmaster',$update_array);
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
	public function jobpriority_field_update($Jobmaster_id,$update_JobPriority_array)
	{
		$this->db->where('Id',$Jobmaster_id);
		$query = $this->db->update('jobmaster',$update_JobPriority_array);
		//echo $this->db->last_query();exit;
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
	
	public function notify_driver_prioritywise($Priority,$CompanyId)
	{
		$this->db->select('*');
		$this->db->from('companydriver as Cd');
		$this->db->join('usermaster as Um','Um.Id=Cd.DriverUserId');
		$this->db->join('drivermaster as Dm','Dm.UserId=Cd.DriverUserId');
		$this->db->where('Cd.CompanyId',$CompanyId);
        $this->db->where('Um.Enable', 1);
        $this->db->where('Um.RoleId', 3);
        $this->db->where('Cd.Priority',$Priority);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query)
		{
			$result = $query->result();
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	public function getallpriorityjob()
	{	
		$query = $this->db->query("SELECT * FROM `jobmaster` WHERE jobstatus=1 AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE  jobstatus IN('2','3','4','5'))");
		$result = $query->result();
		return $result;
	}
}
?>