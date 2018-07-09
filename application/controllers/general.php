<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class general extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('general_model');
		
		if($this->session->userdata('logged_in')) {
			$session_data = $this->session->userdata('logged_in');
			//print_r($session_data);
			$data['email'] = $session_data['email'];
   			$data['name'] = $session_data['name'];
			$data['roleid'] = $session_data['roleid'];
			
		}
		
	}
	
	/******************** Load view layout *********************/
	public function load_my_view($data){
		if($data['section']){
			$total_pages = count($data['section']); 
			$mypages = $data['section'];
			$this->load->view('layout/header',$data);

			for($i=0; $i<$total_pages; $i++){
				$this->load->view($mypages[$i],$data); 
			}
			$this->load->view('layout/footer',$data);    
		}
    }

	
	/***************** Check current session is set or not **********************/
	public function check_current_session(){
		
		if($this->session->userdata('logged_in')) {
			$session_data = $this->session->userdata('logged_in');
			 $data['roleid'] = $session_data['roleid'];
			  /* if($data['roleid']==1)
			   {*/
				$data['email'] = $session_data['email'];
				$data['name'] = $session_data['name'];
				$data['lastlogin'] = $session_data['lastlogin'];
				
				return $data; 			
				/*}
			   else
			   {
			    //$this->session->sess_destroy('$session_data'); 
			    echo '<span style="color:red;font-size: 24;">Only Admin Allowed !!</span>';exit;
			   }*/
		}else{
			return false;
		} 
    }
	
	
	/**** Depended drop dow country state city ****/

		public function get_suburb($country){
		$counttry = explode("-", $country);
		$country = $counttry[1];
		$keyword = $this->input->post('keyword');
		$subrub_array = $this->general_model->get_all_location_details($country,$keyword);
		//print_r($subrub_array); exit;
		
		if($subrub_array)
		{
		echo json_encode($subrub_array);
		}

		}
		
	public function get_suburb_json()
	{
	  $country = $this->input->post('country');
	  $countries = explode("-", $country);
	  $country = $countries[1];
	  $keyword = $this->input->post('keyword');
	  $subrub_array = $this->general_model->get_all_location_details($country,$keyword);
	  if(!empty($subrub_array)){
	   $output = json_encode($subrub_array);
	   }else{
		$output = 0;
	   }
	  echo $output;
	}
	 
	public function validate_address()
	{
		 $country = $_POST['selectcountry']; 
		  $city = $_POST['selectcity'];
		  $state = $_POST['selectstate'];
		  $postcode = $_POST['selectpostcode'];
		  $cn_array=array('Australia-AU','New Zealand-NZ','United States-US');
		
		if(in_array($country, $cn_array))
		{
			if(!empty($_POST['selectcountry']) && !empty($_POST['selectcity']) && !empty($_POST['selectstate']) && !empty($_POST['selectpostcode']))
			{
			  $validate =  $this->general_model->check_customer_address();
				if($validate)
				{
					/** if validate then 0  errors**/
					echo '0';
				}
				else
				{
					/* if validation error occured then 1 response  */
					echo '1';
				}
			}
			else
			{
				echo '1';
			}
		}
		else{ echo '0'; }
	}

	/****************  regenerate token function **********************/
	function re_generatekey($flag='')
	{
			//echo 'here';exit;
			$length=15;
			$flag=$this->input->post('flag');
			$options = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$code = "";
			for($i = 0; $i < $length; $i++)
			{
				$key = rand(0, strlen($options) - 1);
				$code .= $options[$key];
			}
			if($flag=='edit')
			{echo $code;}
			else{return $code;}
	}

	/********** Versatile Access token for all the master based on type ********/
	public function VersatileAccessToken($prefix="",$type="")
	{	
		 	if(!empty($prefix) && !empty($type)) {	
		 		
		 		/********** Start Unique Sequence Number generate process @v *********/
		 		$sequenceerNumber=0;
		 	    $query="SELECT sequencenumber FROM `sequencemaster` ORDER BY id DESC LIMIT 0, 1";

				$execute=$this->db->query($query);
				$sequenceResult=$execute->result();  
				//print_r($sequenceResult);//exit;
				if($sequenceResult){
				$sequenceerNumber = $sequenceResult[0]->sequencenumber;
				}					
				
				if($sequenceerNumber < 100000000000)
					$sequenceNumbervalues=100000000001;
				else
					$sequenceNumbervalues=$sequenceerNumber+1;
				
				$sequenceNumberRow=array(
				   "sequencenumber"=>$sequenceNumbervalues
				); 
				$this->db->insert('sequencemaster',$sequenceNumberRow);

				/*********** End of Unique Sequence Number generate process @v **********/

		   	   $generate="";
			   if($type == "company"){

				   $generate= $prefix.$sequenceNumbervalues;

				   return $generate;
				}
				elseif($type == "user" || $type == "driver" || $type == "customer"){

				  $generate= $prefix.$sequenceNumbervalues;
				   
				  return $generate;
		   		}
		   }
		   else{
			   $code="";
		   	   $code = $this->re_generatekey();
			   $dquery = $this->db->query("SELECT * FROM `drivermaster` WHERE EmailVerifyToken='$code'");
			   if($dquery){
				   $dresult = $dquery->result();
				   $co_dresult =  count($dresult);
				   if($co_dresult > 0){					   
					    $code = $this->re_generatekey();					    
				   }
				   
			   }
			   return $code;
		   }		   
	  
	 }
   
   	/* Check UnAuthorized User Not Access the Other Controller */
	public function AccessPermission(){
		$session_data = $this->session->userdata('logged_in');
		$roleid = $session_data['roleid'];
		if($roleid==1){
			$myarray = array('dashboard','companymaster','usermaster','jobhistory','customermaster','drivermaster','companydriver','findmap','driverregister');
		}
		else if($roleid==4 || $roleid==2 || $roleid==3){
			$myarray = array('dashboard','companydriver','findmap','customermaster');
		}
		$controller_name = $this->router->fetch_class();
		if(!in_array($controller_name,$myarray)){
				echo '<h1><center> Access Denied (403)</center></h1>';exit;
		}		
		
	}

	// function api_logs($request,$response,$responsecode,$starttime)
	// function api_logs($requestby,$request,$response,$responsecode,$starttime,$Request_Type)
	// {
	// 		  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	// 		  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	// 		  //echo $url; // Outputs: Full URL
	// 		  $Id=$this->input->post('Id');
	// 		  //echo $Id;exit;
	// 		  $apiurl=$url;
	// 		  // $request_Type = $controller = $this->router->fetch_class();
	// 		  // $requesttype=$request_Type;
	// 		  $arr_json='';
	// 		  $request=$this->input->post('request');			  
			  
	// 		  $getorigin=$_SERVER['REMOTE_ADDR'];
	// 		  $originip=$getorigin;

	// 		  // $token = $this->VersatileAccessToken(15);   
	// 		  // $requestby=$token;
			  
	// 		  //echo $requestby;exit;
	// 		  $responsetime=$this->input->get('responsetime');
	// 		  date_default_timezone_set("Asia/kolkata");
	// 		  //echo date_default_timezone_get();
	// 		  $currentdt= date('Y-m-d h:i:s');
	// 		  //echo 'Current Time ='.$currentdt;
	// 		  // $starttime = new DateTime($currentdt);
	// 		  $endtime = new DateTime($currentdt);
	// 		  $interval = date_diff($starttime,$endtime);
	// 		  $response_time=  $interval->format('%h:%i:%s');
			  
	// 		  $request_array=array(
	// 		   'apiurl'=>$apiurl,
	// 		   'requesttype'=>$Request_Type,
	// 		   'request'=>$request,
	// 		   'response'=>$response,
	// 		   'responsecode'=>$responsecode,
	// 		   'originip'=>$originip,
	// 		   'requestby'=>$requestby,
	// 		   'responsetime'=>$response_time,
	// 		   'CreatedAt'=>$currentdt,
	// 		   );
	// 		  $request_body= json_encode($request_array);
	// 		  $apilog_insert_array=array(
	// 		   'apiurl'=>$apiurl,
	// 		   'requesttype'=>$Request_Type,
	// 		   'request'=>$request_body,
	// 		   'response'=>$response,
	// 		   'responsecode'=>$responsecode,
	// 		   'originip'=>$originip,
	// 		   'requestby'=>$requestby,
	// 		   'responsetime'=>$response_time,
	// 		   'CreatedAt'=>$currentdt,
	// 		  ); 
	// 		  //print_r($apilog_insert_array);
	// 		  $api_query = $this->general_model->api_log_data($apilog_insert_array);
	// 		  $result = "";
	// 		  if($api_query)
	// 		  {
	// 		   // $msgch ="logged inserted success.";
	// 		   // $arr_json['success'] = "1"; 
	// 		   // $arr_json['message'] =  $msgch;
	// 		  	$result=true;
	// 		  	return  $result;
	// 		  }
	// 		  else
	// 		  {
	// 		   // $msgch ="Something went wrong.";
	// 		   // $arr_json['success'] = "0";
	// 		   // $arr_json['message'] = $msgch;
	// 		  	$result=false;
	// 		  	return  $result;
	// 		  }
	// 		  // echo json_encode($arr_json);
	// 		  return $result;
	// }

	function api_logs($requestby,$apirequest,$response,$responsecode,$starttime,$Request_Type)
	{
			  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];			  
			  $Id=$this->input->post('Id');
			  $apiurl=$url;
			  $arr_json='';
			  
			  $getorigin=$_SERVER['REMOTE_ADDR'];
			  $originip=$getorigin;

			  $responsetime=$this->input->get('responsetime');
			  date_default_timezone_set("Asia/kolkata");
			  $endtime= date('Y-m-d h:i:s');
			  $endtime = new DateTime($endtime);
			  $dtendtime = $endtime->format('Y-m-d h:i:s');
			  $dtstarttime = $starttime->format('Y-m-d h:i:s');
			  $dteStart = new DateTime($dtstarttime); 
			  $dteEnd   = new DateTime($dtendtime); 
			  $dteDiff  = $dteStart->diff($dteEnd); 
			  $response_time = $dteDiff->format("%H:%I:%S"); 
			  
			  $apilog_insert_array=array(
				   'apiurl'=>$apiurl,
				   'requesttype'=>$Request_Type,
				   'request'=>$apirequest,
				   'response'=>$response,
				   'responsecode'=>$responsecode,
				   'originip'=>$originip,
				   'requestby'=>$requestby,
				   'responsetime'=>$response_time,
				   'CreatedAt'=>$dtstarttime,
			  ); 

			  $api_query = $this->general_model->api_log_data($apilog_insert_array);
			  $result = "";
			  if($api_query)
			  {
			  	$result=true;
			  	return  $result;
			  }
			  else
			  {
			  	$result=false;
			  	return  $result;
			  }

			  return $result;
	}
	
		
/**** Start Time-Zone Js @KKG Date : 18/05/2018 ****/
	
	public function tz_list() 
	{
		$zones_array = array();
		$timestamp = time();
		foreach(timezone_identifiers_list() as $key => $zone) {
			date_default_timezone_set($zone);
			$zones_array[$key]['zone'] = $zone;
			$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
		}
			/*	
				$html_list = ''; 
				foreach($zones_array as $zone)
				{
					$html_list .= '<option><strong class="name">'.strtoupper($zone['zone']).' - '.$zone['diff_from_GMT'].'</strong></option>';	
				}
			*/
		return  $zones_array;
	}
		
	public function tz_list_option() 
	{
	   $zones_array = array();
	   $timestamp = time();
		foreach(timezone_identifiers_list() as $key => $zone) 
		{
			date_default_timezone_set($zone);
			$zones_array[$key]['zone'] = $zone;
			$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
		}
		   $html_list = ''; 
		   $html_list .= '<option value="">Select TimeZone</option>';
		   
		foreach($zones_array as $zone)
		{
			$html_list .= '<option value="'.strtoupper($zone['zone']).'" >'.strtoupper($zone['zone']).'-'.$zone['diff_from_GMT'].'</option>'; 
		}
		echo $html_list;
	}
	
	public function mytimezone($zone='')
	{
		if($zone=='')
		{
		  $zone = 'Asia/Kolkata';
		}
		$zones_array = array();
		$timestamp = time();
		date_default_timezone_set($zone);
		$timezone = $zone.' '.'UTC/GMT ' . date('P', $timestamp);
		return $timezone;
	}

/**** End Time-Zone Js @KKG Date : 18/05/2018 *****/   

	public function getCompanyTimeZonebyId($companyId){
		if(!empty($companyId)){
			$TimeZone = $this->general_model->getTimeZoneCompanyWise($companyId);
			if($TimeZone){
				//$result['success'] = $TimeZone;
				$TimeZone = explode('-',$TimeZone[0]->TimeZone);
				//print_r($TimeZone);exit;				
				$result['success'] = 1;
				$result['TimeZone'] = $TimeZone;
				$result['message'] = "TimeZone Found";
			}
			else{
				$result['success'] = 0;
				$result['error'] = "Timezone not found with this company.";	
			}
		}else{
			$result['success'] = 0;
			$result['error'] = "empty companyId not allowed.";
		}
		return $result;
	}


	/************** Get the Current Time by Timezone with 24 hours formatt @v *************/
	public function getDatetimeNow($timezone) {
	  //$tz_object = new DateTimeZone("Asia/kolkata");
	  $tz_object = new DateTimeZone($timezone);
	  $datetime = new DateTime();
	  $datetime->setTimezone($tz_object);
	  return $datetime->format('Y\-m\-d\ H:i:s');
	}
	/************** Get the Current Time by Timezone @v *************/
}
?>