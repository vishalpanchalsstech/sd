<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class job_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}


	/****** Get jobmaster all data through the id @v***********/
	public function get_job_data($id)
	{
		$this->db->select("cm.Name as CompnayName,cm.Logo as CompanyLogo,cm.Email as CompanyEmail,cm.Country as CompanyCountry,cm.State as CompanyCity,cm.State as CompanyCity,cm.City as CompanyCity,um.Name as CustomerName,um.id as CustomerId,um.Email as CustomerEmail,ps.Token as CompanyToken,js.StatusName,nm.Name as NotificationMethod,jm.*");
		$this->db->from("jobmaster jm");
		$this->db->join("usermaster um","um.Id=jm.UserId");		
		$this->db->join("companymaster cm","cm.Id=jm.CompanyId");
		$this->db->join("preferencesettings ps","ps.CompanyId=cm.Id");
		$this->db->join("notificationmethod nm","nm.Id=ps.NotificationMethodId");
		$this->db->join("jobstatus js","js.Id=jm.JobStatus");
		$this->db->where('jm.Id',$id);
		$this->db->where('um.Enable',1);
		$this->db->where('cm.Enable',1);
		$this->db->where('nm.Enable',1);
		$this->db->where('jm.Enable',1);
		$this->db->order_by("jm.CreatedAt", "DESC");
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		return $query->result(); 
	}

	/****** Get jobmaster all data through the jobId @v***********/
	public function get_jobmaster_byjobId($JobId)
	{
		$this->db->select("cm.Name as CompnayName,cm.Logo as CompanyLogo,cm.Email as CompanyEmail,cm.Country as CompanyCountry,cm.State as CompanyCity,cm.State as CompanyCity,cm.City as CompanyCity,um.Name as CustomerName,um.id as CustomerId,um.Email as CustomerEmail,ps.Token as CompanyToken,js.StatusName,nm.Name as NotificationMethod,jm.*");
		$this->db->from("jobmaster jm");
		$this->db->join("usermaster um","um.Id=jm.UserId");		
		$this->db->join("companymaster cm","cm.Id=jm.CompanyId");
		$this->db->join("preferencesettings ps","ps.CompanyId=cm.Id");
		$this->db->join("notificationmethod nm","nm.Id=ps.NotificationMethodId");
		$this->db->join("jobstatus js","js.Id=jm.JobStatus");
		$this->db->where('jm.JobId',$JobId);
		$this->db->where('um.Enable',1);
		$this->db->where('cm.Enable',1);
		$this->db->where('nm.Enable',1);
		$this->db->where('jm.Enable',1);
		$this->db->order_by("jm.CreatedAt", "DESC");
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		return $query->result(); 
	}

	/****** Update Job with cancel job details *****/
	public function Canceldelivery($jobId,$update_array)
	{
		$this->db->where('JobId',$jobId);
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


	/****** Get Coverjob detail List *****/
	public function coverjob_List()
	{
		$query = $this->db->query("select * from jobmaster where JobStatus='1'");
		$result = $query->result();
		if($query)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	public function Acceptjob_detail_list($token,$JobId,$DriverUserId,$AcceptTime)
	{
		//$query = $this->db->query("select pickupDetail from jobmaster where JobId='".$JobId."' AND UserId='".$DriverUserId."' AND CreatedAt='".$AcceptTime."' ");
		$this->db->select('*');
		$this->db->from('drivermaster as Dm');
		$this->db->join('usermaster as Um','Um.Id=Dm.UserId');
		$this->db->join('jobmaster as cjo','cjo.UserId=Um.Id');
		$this->db->where('Um.Token',$token);
		$this->db->where('Um.Id',$DriverUserId);
		$this->db->where('cjo.JobId',$JobId);
		$this->db->where('cjo.JobStatus',3);
		$this->db->where('Dm.Enable',1);
		$query = $this->db->get();
		
		echo $this->db->last_query();exit;

		if($query)
		{
			$allresult = $query->result();
			$All_result =  count($allresult);
			if($All_result > 0)
			{
				$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$allresult);
				return $myresult; 
			}
			else
			{
				return false; 
			}
		}
		else
		{
			return false;
		}
	}

	/*Current Job Insert Data through the API in jobmaster table*/
	public function insert_currentjob_data($insert_array){
		$query = $this->db->insert('jobmaster',$insert_array);
		$insert_id = $this->db->insert_id();
		if($query==1)
		{
			$result=$insert_id;
		}
		else
		{
			$result=0;
		}
		return $result;
	}
	
	/*Get jobmaster data through the last_id*/
	public function get_jobmaster_data($last_id){
		$this->db->select("*");
		$this->db->from("jobmaster");
		$this->db->where('Id',$last_id);
		$query = $this->db->get();
		return $query->result(); 
	}

	/*Insert AcceptJob Details in Jobmaster*/
	public function insert_jobmaster_by_jobid($JobId,$insert_aray)
	{
		$query = $this->db->insert('jobmaster',$insert_aray);
		$insert_id = $this->db->insert_id();
		if($insert_id)
		{
			return $insert_id;
		}
		else
		{
			return false;
		}
	}
	
	/*Insert ProgressJob Details in Jobmaster*/
	public function insert_progressjob_detail($insert_inprogress_aray)
	{
		$query = $this->db->insert('jobmaster',$insert_inprogress_aray);
		$insert_id = $this->db->insert_id();
		if($insert_id)
		{
			return $insert_id;
		}
		else
		{
			return false;
		}
	}
	
	/*Insert Tracking Details in Tracking Table*/
	public function insert_tracking_detail($insert_tracking_array)
	{
		$query = $this->db->insert('tracking',$insert_tracking_array);
		$insert_id = $this->db->insert_id();
		if($insert_id)
		{
			return $insert_id;
		}
		else
		{
			return false;
		}
	}
	
	public function chk_driverId_wise_status($JobId,$Chk_Driver_UserId)
	{
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('JobId',$JobId);
		$this->db->where('DriverId',$Chk_Driver_UserId);
		$this->db->where('JobStatus',3);
		$query = $this->db->get();
		$result = $query->result();
		if($query)
		{	
			//$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$result);
			return $result;
		}
		else
		{
			return false;
		}
		
	}

	public function empty_col_ckeck($JobId)
	{
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('JobId',$JobId);
		//$this->db->where_in('JobStatus', array('3'));
		$this->db->where_in('JobStatus',array(1,2));
		$query = $this->db->get();
		$result = $query->result();
		if($query)
		{	
			//$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$result);
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	public function get_all_jobdata($JobId)
	{
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('JobId',$JobId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$result = $query->result();
		if($query)
		{	
			//$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$result);
			return $result;
		}
		else
		{
			return false;
		}
	}

	/*Get Tracking Table data through the last_id*/
	public function get_tracking_data($last_id){
		$this->db->select("*");
		$this->db->from("tracking");
		$this->db->where('Id',$last_id);
		$query = $this->db->get();
		return $query->result(); 
	}

	/*job already exist check jobmaster*/
	public function jobexistcheck($JobId){
		
		$this->db->select("*");
		$this->db->from("jobmaster");
		$where = '(JobId="'.$JobId.'" AND JobStatus = 3)'; 
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result(); 
	}

	/*JobId and Jobstatus Exist or not Check*/
	public function JobIdStatusCheck($jobid,$jobstatus){
		$this->db->select("*");
		$this->db->from("jobmaster");
		$where = '(JobId="'.$jobid.'" AND JobStatus = '.$jobstatus.')'; 
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result(); 
	}

	/*Get CompanyDriver Details*/
	public function get_companydriver_details($companyid){
		  $this->db->select('*');
		  $this->db->from('companydriver');
		  $this->db->where('CompanyId',$companyid);
		  $query = $this->db->get();
		  $result = $query->result();
		  return $result;
	}

	/*get scheduled job*/
	 public function get_scheduledjob($JobId){
	  $this->db->select('*');
	  $this->db->from('jobmaster');
	  $this->db->where('JobId',$JobId);
	  $query = $this->db->get();
	  return $query->result();
	 }

	 /*find accepted job*/
	 public function findacceptedjob($DriverUserId){
	  
	  $query = $this->db->query('SELECT * FROM `jobmaster` WHERE DriverId='.$DriverUserId.' AND jobstatus>=3 AND Enable=1 AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE DriverId='.$DriverUserId.' AND jobstatus IN (4,5))');
	  // echo $this->db->last_query();exit;
	  $result = $query->result();
	  return $result;
	 }

	 /*find job start or not*/
	  public function find_startjobexist($DriverUserId){
	   $query = $this->db->query('SELECT * FROM `jobmaster` WHERE DriverId='.$DriverUserId.' AND jobstatus>=4 AND Enable=1 AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE DriverId='.$DriverUserId.' AND jobstatus=5)');
	   //echo $this->db->last_query();exit;
	   $result = $query->result();
	   return $result;
	  }

	  /*Check JobId Exist or not in Review Table*/
	 public function jobexistcheckreview($jobid){
	  $this->db->select("*");
	  $this->db->from("review");
	  $this->db->where('JobId',$jobid);
	  $query = $this->db->get();
	  return $query->result(); 
	 }

	 /*Update Review Table Data*/
	 public function review_data_update($review_arr,$Id){
		  $this->db->where('Id',$Id);
		  $query = $this->db->update('review',$review_arr);
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

	 /*Get Data from Review Table through the Id*/
	 public function get_review_data($Id){
	  $this->db->select("*");
	  $this->db->from("review");
	  $this->db->where('Id',$Id);
	  $query = $this->db->get();
	  return $query->result(); 
	 }

	/*Update tracking table endtime */
	public function update_trackingdetails($JobId,$tracking_array)
	{
		  $this->db->where('JobId',$JobId);
		  $query = $this->db->update('tracking',$tracking_array);
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

	/*Get Driver and User Details*/
	public function rejectedjobexist($companyid,$JobId)
	{
			$this->db->select('*');
			$this->db->from('jobmaster');
			$this->db->where('CompanyId',$companyid);
			//$this->db->where('UserId',$DriverUserId);
			$this->db->where('JobId',$JobId);
			$this->db->where('Enable',1);
			$query = $this->db->get();
			$result = $query->result();
			return $result;
	}

	/*Update Job Status for the Rejected*/
	public function update_job_status($companyid,$update_array,$JobId,$jobstatus){
			$this->db->where('CompanyId',$companyid);
			$this->db->where('JobId',$JobId);		
			$this->db->where('JobStatus',$jobstatus);
			// $query = $this->db->update('jobmaster',array('RejectDriverId'=>$DriverUserId));
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

	/*Get Job when jobstatus=1 from jobmaster*/
	public function findJob(){
		//SELECT * FROM `jobmaster` WHERE jobstatus=1 AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE  jobstatus IN('2','3','4','5'))

		$query = $this->db->query("SELECT * FROM `jobmaster` WHERE jobstatus=1 AND Enable=1 AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE  jobstatus IN('2','3','4','5'))");
	   //echo $this->db->last_query();exit;
	   $result = $query->result();

		/*$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('JobStatus',1);
		//$this->db->where('RejectDriverId =','');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		//$result = $query->result(); */
		return $query->result();
	}

	/*get data when jobstatus=2,3 from jobmaster*/
	// public function findjob_status(){
	// 	$this->db->select('*');
	// 	$this->db->from('jobmaster');
	// 	$this->db->or_where('JobStatus',2);
	// 	$this->db->or_where('JobStatus',3);
	// 	$query = $this->db->get();
	// 	//$result = $query->result();
	// 	return $query->result();
	// }

	/*Update RejectDriverId*/
	public function update_rejectedid($update_array,$jobmaster_id){
		
		$this->db->where('Id',$jobmaster_id);
		// $query = $this->db->update('jobmaster',array('RejectDriverId'=>$rejectedid_result));
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

	/****** Update Job with cancel job details ADMIN Side Krushna Date 08/06/2018*****/

	public function Canceldelivery_Admin($jobId,$update_array,$Id)
	{
		$this->db->where('JobId',$jobId);
		$this->db->where('Id',$Id); 
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

	/*** Get All jobdata To insert record after cancel through ADMIN Side Krushna Date 08/06/2018***/
	
	public function get_all_jobdata_new($JobId,$Id)
	{
		$this->db->select('*');
		$this->db->from('jobmaster');
		$this->db->where('JobId',$JobId);
		$this->db->where('Id',$Id);  
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$result = $query->result();
		if($query)
		{	
			//$myresult= array('result'=>1,'msg'=>"ALL Drivers DeliveryHistory available",'data'=>$result);
			return $result;
		}
		else
		{
			return false;
		}
	}


}
?>