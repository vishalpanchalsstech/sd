<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class companydriver_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	/** This function for Get record from driver company table **/
	public function GetcdData($rcompanyid){
		
		$company = "";
		if(!empty($rcompanyid)){
			$company = " AND cd.CompanyId = '".$rcompanyid."' ";
		}
		//echo $company; exit;
		$query = $this->db->query("select cm.Name as companyname,um.Email as email,um.Name as name, cd.* from companydriver as cd Join companymaster as cm ON cd.CompanyId = cm.Id Join usermaster as um ON um.Id = cd.DriverUserId WHERE cd.Enable= '1'  $company Order BY cd.Id DESC ");
		//echo $this->db->last_query(); exit;
		if($query){
          $retailer = $query->result();
          return  $retailer;
        }
		else{
            return false;
        } 
    }
	/** This function for Get record from driver id company table **/
	public function GetDrivercdData($userid){
		
		//echo $company; exit;
		$query = $this->db->query("SELECT cm.Name as companyname,cd.* FROM `companydriver` cd JOIN companymaster cm on cm.Id = cd.`CompanyId` WHERE cd.`DriverUserId` = '$userid' AND cd.`Enable` = '1' ");
		//echo $this->db->last_query(); exit;
		if($query){
          $retailer = $query->result();
          return  $retailer;
        }
		else{
            return false;
        } 
    }
	/** This function for check email in system**/
	public function email_check($email){
		
		$query = $this->db->query("SELECT Id FROM `usermaster` WHERE `Email` = '$email' AND `RoleId` = 3 AND `Enable` = 1 ");
		if( $query->num_rows() > 0){
			$result = $query->result();
		    $Id = $result['0']->Id;
			return $Id;
	   }else{
			$result=0;
		}
		return $result;
	}
	/** This function for Insert record**/
	public function insert_data($userid,$companyid){
		
		$query = $this->db->query("SELECT * FROM `companydriver` WHERE `CompanyId` = $companyid AND `DriverUserId` = $userid ");
		if( $query->num_rows() > 0){
			$result = $query->result();
			
		    $enable = $result['0']->Enable;
		    $Id = $result['0']->Id;
			if($enable == 0){
				$result=3;
				/*$update_array = array('Enable'=>'1');
			    $this->db->where('Id',$Id);
				$query = $this->db->update('companydriver',$update_array);
				if($query){
					$result=1;
				}
				else{
					$result=2;
				}*/
			}else{
				$result=0;
			}
			
			
		}else{
		   $insert_array = array('CompanyId'=>$companyid,'DriverUserId'=>$userid);
		   $query = $this->db->insert('companydriver',$insert_array);
			if($query){
				$result=1;
			}
			else{
				$result=2;
			}
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
	
	/** function for delete record from companydriver table**/
	public function Delete_data($delid){
		$update_flag = array('Enable'=>'0');
		$this->db->where('Id',$delid);
		$this->db->update('companydriver',$update_flag);
		$del_suces = 1;
		return $del_suces;
	}
	public function update_data($update_array,$UpdateId)
	{
		$this->db->where('Id',$UpdateId);
		$query = $this->db->update('companydriver',$update_array);
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