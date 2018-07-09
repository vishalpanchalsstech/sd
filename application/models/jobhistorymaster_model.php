<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class jobhistorymaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	/** This function for Get record from jobmaster table **/
	public function GetJobHistoryData($roleid)
	{
		if($roleid==1)
		{
			//echo 'AAAAA';exit;
			$this->db->select('js.StatusName as jobstatus,um.Name as UserName,cm.Name as Company,jm.*');
			$this->db->from('jobmaster as jm');
			$this->db->join('companymaster as cm','jm.CompanyId=cm.Id');
			$this->db->join('usermaster as um','jm.UserId=um.Id');
			$this->db->join('jobstatus as js','jm.JobStatus=js.Id');
			$this->db->where('jm.Enable',1);		
			$this->db->order_by('jm.Id',"DESC");
			$query = $this->db->get();
			// echo $this->db->last_query();exit;
			$result = $query->result();
			return $result;
		}
		else
		{
			//echo 'BBBBB';exit;
			$this->db->select('js.StatusName as jobstatus,um.Name as UserName,cm.Name as Company,jm.*');
			$this->db->from('jobmaster as jm');
			$this->db->join('companymaster as cm','jm.CompanyId=cm.Id');
			$this->db->join('usermaster as um','jm.UserId=um.Id');
			$this->db->join('jobstatus as js','jm.JobStatus=js.Id');
			$this->db->where('jm.Enable',1);		
			$this->db->where('jm.CreatedBy',$roleid);		
			$this->db->order_by('jm.Id',"DESC");
			$query = $this->db->get();
			// echo $this->db->last_query();exit;
			$result = $query->result();
			return $result;
		}
		
    }
	
	/********** get company name of login user @KKG@ ********************/
	public function Get_loginUser_companyName($roleid,$email)
	{
		$this->db->select('Ps.Token as Apikey,Cm.*');
		$this->db->from('companymaster as Cm');
		$this->db->join('usermaster as Um','Cm.Id=Um.CompanyId');
		$this->db->join('preferencesettings as Ps','Ps.CompanyId=Cm.Id');
        $this->db->where('Um.RoleId',$roleid);
        $this->db->where('Um.Email',$email);
        $this->db->where('Um.Enable',1);
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
	}
	
	/***** Get Companiwise DriverList @KKG@ ***/
	
	public function Get_companiwise_DriverList($CompanyId)
	{
		//$query = ('SELECT *  FROM `companydriver` as cd JOIN usermaster as um on um.Id = cd.DriverUserId WHERE cd.`CompanyId` = 1');
		$this->db->select('*');
		$this->db->from('companydriver as Cd');
		$this->db->join('usermaster as Um','Um.Id=Cd.DriverUserId');
		//$this->db->join('drivermaster as Dm','Dm.UserId=Cd.DriverUserId');
		$this->db->where('Cd.CompanyId',$CompanyId);
        $this->db->where('Um.Enable', 1);
		$query = $this->db->get();
		
		if($query){
			 $result = $query->result();
			 return $result;
		}else{
			return false;
		}
	}
	/** This function for Insert record**/
	public function insert_data($insert_array){
		$query = $this->db->insert('jobmaster',$insert_array);
		if($query){
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
	}
	
	/** This function for Insert customer**/
	public function insert_customer($insert_array){
		$query = $this->db->insert('usermaster',$insert_array);
		$lst_user_id = $this->db->insert_id();
		if($lst_user_id){
			$result=$lst_user_id;
		}
		else{
			$result=0;
		}
		return $result;
	}
	
	public function edit_CompanyName($UserCompanyId)
	{
		$this->db->select('Name');
		$this->db->from('companymaster');
		$this->db->where('Id', $UserCompanyId);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	/** This function for Insert User record**/
	public function edit_data($edit_id)
	{
		//echo 'here';exit;
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('Id', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		//echo $this->db->last_query();exit;
	}
	/** This function for Update User Record**/
	public function update_data($update_array,$Id){
		$this->db->where('Id',$Id);
		$query = $this->db->update('jobmaster',$update_array);
		 if($query)
		{
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
    }
	/** function for delete record from usermaster table**/
	public function Delete_data($delid){
		$update_flag = array('Enable'=>'0');
		$this->db->where('Id',$delid);
		$this->db->update('jobmaster',$update_flag);
		$del_suces = 1;
		return $del_suces;
	}
	
	/*Email Verify from the UserMaster Table*/
	public function customer_email_verify($email){		
		$this->db->select('*');
		$this->db->from('usermaster');
		$where = '(usermaster.Email="'.$email.'" AND usermaster.RoleId = 4)'; 
		$this->db->where($where);
		$query = $this->db->get();
		$result = $query->result();
		return $result;		
	}
	
	/*Get customer data from the last_id in usermaster table*/
	public function get_customer_details($last_id){
		
		$this->db->select('*');
		$this->db->from('usermaster');
		$this->db->where('Id',$last_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	/*Get JobStatus through the Id*/
	public function get_job_status($id){
		if(!empty($id)){
			$this->db->select('*');
			$this->db->from('jobstatus');
			$this->db->where('Id',$id);
			$query = $this->db->get();
			$result = $query->result();
		}else{
			$this->db->select('*');
			$this->db->from('jobstatus');
			$query = $this->db->get();
			$result = $query->result();
		}
		return $result;
	}
	public function get_loginuser_id($email,$roleid)
	{
		
		$this->db->select('*');
		$this->db->from('usermaster');
		$this->db->where('usermaster.Email',$email);
		$this->db->where('usermaster.RoleId',$roleid);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function Cancel_record_search($Id,$Reason)
	{
		$Job_Status = array('1','3');
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('jobmaster.Id',$Id);
		$this->db->where_in('jobmaster.JobStatus',$Job_Status);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$result = $query->result();
		return $result;exit;
		/*if($result)
		{
			$JobId = $result[0]->JobId;
			$UserId = $result[0]->UserId;
			$Reason;
			
			$canceldriverid = json_encode(array("JobId"=>$JobId,"UserId"=>$UserId,"cancellationNotes"=>$Reason));
			$update_arr = array(
								 'CancelDriverId'=>$canceldriverid,
								 'Enable'=>0
								);
								
			return $result;
		}
		else
		{
			return false;
		}*/
	}
	public function Get_Tick_Data($Id,$Reason)
	{
		
		$Job_Status = array('1','3');
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('jobmaster.Id',$Id);
		$this->db->where_in('jobmaster.JobStatus',$Job_Status);
		$query = $this->db->get();
		$result = $query->result();
		
		if($result)
		{
			$JobId = $result[0]->JobId;
			$UserId = $result[0]->UserId;
			$Reason;
			
			$canceldriverid = json_encode(array("JobId"=>$JobId,"UserId"=>$UserId,"cancellationNotes"=>$Reason));
			$update_arr = array(
								 'JobStatus'=>2,
								 'CancelDriverId'=>$canceldriverid,
								 'Enable'=>0
								);
			$this->db->where('Id',$Id);
			$query = $this->db->update('jobmaster',$update_arr);
			
			if($query){
					$result=1;
				}
				else{
					$result=2;
				}
			return $result;
		}
		else
		{
			return false;
		}
		
	}
	
	/*Filter functionality for JobHistory Master*/
	public function filter_data($jobstatusid,$companyid,$StartJob,$EndJob,$checkbox,$search_key){
		
		if(!empty($companyid)){
			$companyid = 'AND cm.Id IN ("'.$companyid.'")';
		}else{$companyid='';}
		
		if(!empty($jobstatusid)){
			$jobstatusid = 'AND jm.JobStatus IN ("'.$jobstatusid.'")';
		}else{$jobstatusid='';}
		
		if(!empty($checkbox)){
			if(in_array('Scheduled',$checkbox)){
				$checkbox_query = 'AND jm.ScheduleJobTime!="0000-00-00 00:00:00"';
			}
		}else{$checkbox_query='';}
		
		if(!empty($search_key)){
			$search_key = 'AND cm.Name LIKE "%'.$search_key.'%"';
		}else{$search_key='';}
		
		
		
		$query = $this->db->query('select js.StatusName as jobstatus,um.Name as UserName,cm.Name as Company,jm.* from jobmaster as jm
										  JOIN companymaster as cm ON jm.CompanyId=cm.Id
										  JOIN usermaster as um ON jm.UserId=um.Id
										  JOIN jobstatus as js ON jm.JobStatus=js.Id
										  where jm.CreatedAt BETWEEN "'.$StartJob.'" AND "'.$EndJob.'" '.$companyid.' '.$jobstatusid.' '.$checkbox_query.' '.$search_key.' AND jm.Enable=1 order by jm.Id desc');
		//echo $this->db->last_query();exit;
		$result = $query->result();
		return $result;	
		
	}
	
	
	
	/*get modal popup details*/
	public function Get_jobhistoryIdWiseDetails($id){
		
		//echo 'AAAAA';exit;
			$this->db->select('js.StatusName as jobstatus,um.Name as UserName,cm.Name as Company,jm.*');
			$this->db->from('jobmaster as jm');
			$this->db->join('companymaster as cm','jm.CompanyId=cm.Id');
			$this->db->join('usermaster as um','jm.UserId=um.Id');
			$this->db->join('jobstatus as js','jm.JobStatus=js.Id');
			$this->db->where('jm.Id',$id);	
			$this->db->where('jm.Enable',1);		
			$this->db->order_by('jm.Id',"DESC");
			$query = $this->db->get();
			// echo $this->db->last_query();exit;
			$result = $query->result();
			return $result;
	}
	
	
}
?>