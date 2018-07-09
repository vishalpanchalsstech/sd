<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class jobhistorymaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	/** This function for Get record from jobmaster table **/
	public function GetJobHistoryData(){
		
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
	
}
?>