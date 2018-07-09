<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class companymaster_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	 public function GetcompanyData(){
		$query =$this->db->query('SELECT * FROM `companymaster` WHERE `Enable` = 1 ');
        //echo $this->db->last_query();
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
	
    public function company_emailvalidate($email)
	{
		$this->db->select('*');
		$this->db->from('companymaster');
		$this->db->where('companymaster.Email',$email);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
		   return '1';
	    }
		else
		{
		   return '0';
	    }
	}
	
	public function insert_data($insert_array)
	{
		$query = $this->db->insert('companymaster',$insert_array);
		$lst_company_id = $this->db->insert_id();
		
		return $lst_company_id;
	}
	public function insert_data_pre_setti($insert_array_ps)
	{
		$query = $this->db->insert('preferencesettings',$insert_array_ps);
		if($query)
		{
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
	}
	/******************* This Function for Edit Company Data  *******************/
	
	public function edit_data($edit_id)
	{
		
		$this->db->select('*');
		$this->db->from('companymaster');
		$this->db->where('Id', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		 
	}
	/******************* This Function for get state Data  *******************/
	
	public function edit_state_data($country)
	{
		$query = $this->db->query("SELECT cs.CountryStateId,cs.State FROM `countrystate` as cs JOIN countriesmaster cm on cm.SortName=cs.Country WHERE cm.Name = '$country'");
		//echo $this->db->last_query(); exit;
		$result = $query->result();
		return $result;
		 
	}
	
	/*******************update company details**********************/
	 public function update_data($update_array,$Id){
		 
		$this->db->where('Id',$Id);
		$query = $this->db->update('companymaster',$update_array);
      
		 if($query)
		{
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
    }
	/*******************update company preference settings details**********************/
	 public function update_ps_data($update_ps_array,$Id){
		 
		$this->db->where('CompanyId',$Id);
		$query = $this->db->update('preferencesettings',$update_ps_array);
      
		 if($query)
		{
			$result=1;
		}
		else{
			$result=0;
		}
		return $result;
    }
	/******************** This Function for Delete Company Data ****************/
	
	public function Delete_data($delid)
	{
		    $update_flag = array('Enable'=>'0');
            $this->db->where('Id',$delid);
            $this->db->update('companymaster',$update_flag);
            $del_suces = 1;
            return $del_suces;
		
	}
	/******************** This Function for Notification Method Get ****************/
	
	public function NotiMethod()
	{
		$this->db->select('*');
		$this->db->from('notificationmethod');
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		
	}
	/******************* This Function for Edit Company Data to get preference settings data  *******************/
	
	public function psdata($edit_id)
	{
		
		$this->db->select('*');
		$this->db->from('preferencesettings');
		$this->db->where('CompanyId', $edit_id);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		 
	}

	/****** This function used for the get company details by token from preference settings *****/

	public function GetCompanyByTokenPreference($token)
	{
		//SELECT * FROM `preferencesettings` as ps inner join companymaster cm on cm.Id = ps.CompanyId WHERE Token='0EtbHr7L4PHBUcK' 
		$this->db->select('*');
		$this->db->from('preferencesettings as ps');
		$this->db->join('companymaster as cm', 'cm.Id = ps.CompanyId');
		$this->db->where('Token',$token);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
		 
	}

	 /*Check Duplication Prefix in Companymaster Table*/
	 public function prefixduplicationcheck($companyprefix){
			$this->db->select('Prefix');	
			$this->db->from('companymaster');	
			$this->db->where('Prefix',$companyprefix);
			$query = $this->db->get();
			$result = $query->result();
			return $result;
	 }
	 
}
?>