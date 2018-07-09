<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class usermaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	/** This function for Get record from usermaster table **/
	public function GetuserData(){
		$query =$this->db->query('select ro.Name as companyname,reg.* from usermaster as reg Join companymaster as ro ON reg.CompanyId = ro.Id where reg.Enable=1 Order BY Id DESC ');
		if($query){
          $retailer = $query->result();
          return  $retailer;
        }
		else{
            return false;
        } 
    }
	
	public function same_company_emailvalidate($email,$CompanyId)
	{
		$this->db->select('Um.*');
		$this->db->from('usermaster as Um');
		$this->db->join('companymaster as Cm','Um.CompanyId=Cm.Id');
		$this->db->where('Um.Email',$email);
		$this->db->where('Um.CompanyId',$CompanyId);
		$this->db->where('Um.Enable',1);
		$query = $this->db->get();
		//print_r($query->num_rows());//exit;
		//echo $this->db->last_query();exit;
		if($query->num_rows() >= 1)
		{
		   return '1';
	    }
		else
		{
			//	echo "yes";exit;
		   return '0';
	    }
	}

    public function adminemailvalidate($Email,$RoleId)
    {
        $this->db->where('Email',$Email);
        $this->db->where('RoleId',$RoleId);
        $this->db->where('Enable',1);
        $this->db->from('usermaster');
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        if($query->num_rows() > 0)
        {
            return '1';
        }
        else
        {
            return '0';
        }
    }


	/** This function for Insert record**/
	public function insert_data($insert_array){
		$query = $this->db->insert('usermaster',$insert_array);
		if($query){
			$result=1;
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
		$this->db->from('usermaster');
		$this->db->where('Id', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		//echo $this->db->last_query();exit;
	}
	/** This function for Update User Record**/
	public function update_data($update_array,$Id){
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

	/** function for delete record from usermaster table**/
	public function Delete_data($delid){
		$update_flag = array('Enable'=>'0');
		$this->db->where('Id',$delid);
		$this->db->update('usermaster',$update_flag);
		$del_suces = 1;
		return $del_suces;
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

}
?>