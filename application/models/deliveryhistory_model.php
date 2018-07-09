<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class deliveryhistory_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

/** This function for Get ALL driver deliveryhistory record @ KRUSHNA @ **/

	public function Get_deliveryhistory($apiKey,$DriverUserId)
	{
		$d= '"DriverUserId":"[[:<:]]'.$DriverUserId.'[[:>:]]"';
		$query = $this->db->query("SELECT * FROM `jobmaster` main where main.Enable=1 AND (RejectDriverId like '%$DriverUserId%' OR AcceptedDriverId REGEXP '".$d."' OR CancelDriverId REGEXP '".$d."') and CreatedAt = (SELECT MAX(CreatedAt) FROM jobmaster j GROUP BY JobId HAVING main.JobId = j.JobId) order by Id;");
		//echo $this->db->last_query();exit;
		if($query)
		{
			$allresult = $query->result();
			$All_result =  count($allresult);
			if($All_result > 0)
			{
				//print_r($allresult);exit;
				$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$allresult);
			}
			else
			{
				return false; 
			}
		}
		else
		{
			$myresult= array('success'=>0,'message'=>"Plz Enter Proper filter Criteria"); 
			echo json_encode($myresult);exit;
		}
		return $myresult;
    }
}
?>