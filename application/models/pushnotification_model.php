<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pushnotification_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	/****** Function for getting pushnotification data **/
	public function get_pushnotification_data()
	{	
		$this->db->select("cm.Name as companyname, um.Name as drivername , pn.*",FALSE);
	    $this->db->from('pushnotification as pn');
	    $this->db->join('companymaster AS cm','cm.Id = pn.CompanyId');
	    $this->db->join('usermaster AS um','um.Id = pn.DriverUserId');
	    $this->db->where('um.RoleId',3);
	    $this->db->where('um.Enable',1);
	    $this->db->where('cm.Enable',1);
	    $this->db->where('pn.Enable',1);
	    $this->db->order_by('pn.CreatedAt', 'DESC'); 
	    // $this->db->limit(10);
	    $query = $this->db->get();
		// echo $this->db->last_query();exit;
		
		if($query)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}


	/** function add record in push notification **/
	public function SendPushnotification($add_array)
	{
			// $add_array = array(
			// 	'SenderId'=>$SenderId,
			// 	'ReceiverId'=>$ReceiverId,
			// 	'NotificationMessage'=>$NotificationMessage
			// 	);
          	$this->db->insert('pushnotification',$add_array);
					
            if($this->db->affected_rows() > 0){
				$myresult= array('result'=>1,'msg'=>"pushnotification added successfully.");
			}else{
				$myresult= array('result'=>0,'msg'=>"Something gose wrong. Please Try again");
			}	
			return $myresult;
    }



	/* function for delete record from puch notification **/
	public function pushnotification_delete_row($del_id)
	{
			$update_flag = array('Enable'=>'0');
            $this->db->where('Id',$del_id);
            $query = $this->db->update('pushnotification',$update_flag);
					
            if($query)
			{
                return true;
            }
			else
            {
                return false;
            }
    }
	
}
?>