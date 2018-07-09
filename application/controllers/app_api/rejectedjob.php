<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class rejectedjob extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->model('job_model');
	    $this->load->model('general_model');
	    $general = $this->load->library('../controllers/general');
    }
	
	public function RejectedJob()
	{	
		$request_data = json_decode(file_get_contents('php://input'),true);
		$header =getallheaders();
		if($header['Content-Type'] != "application/json")
		{
				$message['success'] = false; 
				$message['message'] =  'Invalid content-type in header only json allowed.';
				echo json_encode($message); exit;
		}
		if(empty($header['Apikey']))
		{
				$message['success'] = false; 
				$message['message'] =  'Missing Apikey in header.';
				echo json_encode($message); exit;
		}
		else{
			$Token = $header['Apikey'];
		}
			
		if(!$this->isJson($request_data))
		{
					$error = json_last_error_msg();
					$message['success'] = false; 
					$message['message'] =  'Not valid JSON string ($error)';
					echo json_encode($message); exit;
					//echo "Not valid JSON string ($error)";exit;
		}
		if(!isset($request_data) && empty($request_data))
		{
				$message['success'] = false; 
				$message['message'] =  'Missing request data.';
				echo json_encode($message); exit;
		}
		
		// $JobId = $this->input->post('JobId');
		// $Token = $this->input->post('Apikey');
		$JobId = $request_data['JobId'];
		
		//print_r($request_data['JobId']);exit;
		$type = "driver";
		
		/*start verify the apitoken with company token weather exist in db or not*/
		$token_result = $this->general_model->verify_token($Token,$type);   
		//print_r($token_result);exit;
		if($token_result['result']!=0){
			$DriverUserId =$token_result['data'][0]->UserId;
		}else{
			$message['success'] = false; 
		    $message['message'] =  'Result Not Found,'.$token_result['msg'];   
		    echo json_encode($message); exit; 
		}

		if($token_result['result']==0)
		{
		   $message['success'] = false; 
		   $message['message'] =  'Invalid API token, Please Check Apikey.';   
		   echo json_encode($message); exit; 
		}
		
		/*From the JobId Get CompanyId*/
		$jobresult = $this->job_model->get_all_jobdata($JobId);
		if(!empty($jobresult)){
			$companyid = $jobresult[0]->CompanyId;
		}
		
		/*end verify token */
		if(!empty($JobId)&&!empty($Token))
		{
				$jobresult = $this->job_model->get_companydriver_details($companyid);
				
				$flag=0;
				foreach($jobresult as $val){
					$CompanyDriverUserId = $val->DriverUserId;
						if($CompanyDriverUserId==$DriverUserId){
							//Compnay driver found with given token
							$flag=1;
						}
				}
				if($flag==1){					
					/*Check the job exist or not*/
					$jobresult = $this->job_model->rejectedjobexist($companyid,$JobId);
					$FilterResult = $this->getArraybyKeyValue($jobresult,'JobStatus',1);
					
					/*** get current time by conpmay timezone ********/
					$getTimeZone = $this->general->getCompanyTimeZonebyId($companyid);
					$currentdt="";
				    if($getTimeZone['success']==1){
				     $CompanyTimezone = $getTimeZone['TimeZone'][0];
				     $currentdt = $this->general->getDatetimeNow($CompanyTimezone);
				    }					
				    /*** end get current time by conpmay timezone ********/

					if(!empty($FilterResult)){
						$explode =  $FilterResult->RejectDriverId;
						$exp = explode(',',$explode);
						
						if(in_array($DriverUserId,$exp)){
							
							 $message['success'] = false; 
							 $message['message'] =  'Job Already Rejected by You.';   
							 echo json_encode($message); exit; 
							
						}
						
						if($jobresult[0]->JobStatus==1 || $jobresult[0]->JobStatus==2){
							$data="";
							$exp[] =$DriverUserId;
							$data = implode(",",$exp);
							$data = ltrim($data, ",");
							$jobstatus = $jobresult[0]->JobStatus;

							$update_array = array('RejectDriverId'=>$data,
												  'UpdatedAt'=>$currentdt
							);
							// $update_rejected_status = $this->job_model->update_job_status($companyid,$data,$JobId,$jobstatus);

							$update_rejected_status = $this->job_model->update_job_status($companyid,$update_array,$JobId,$jobstatus);
							
							 $message['success'] = true; 
							 $message['message'] =  'Job Updated Succesfully.';   
							 echo json_encode($message); exit; 
						}
					}
				}
				else{
					 $message['success'] = false; 
					 $message['message'] =  'Invalid Driver.';   
					 echo json_encode($message); exit; 
				}
				
		}
		else
		{
				$message['success'] = false; 
				$message['message'] =  'All fields are required.';   
				echo json_encode($message); exit; 
		}
		//echo json_encode($message);
	}
	
	/************ Get array by key & value ******************/
	public function getArraybyKeyValue($array,$key,$value){
		$i=0;
		$newarray=array();
		$jobresult=$array;
		
		foreach($jobresult as $j){
			foreach($j as $key1 => $value1){
					
				if($key1==$key){
					//echo $key.$value;
					if($value1==$value){
						$newarray = $jobresult[$i];
					}
				}															
			}
			$i++;
		}
		return $newarray;
	}

	/****** Validate json *********/
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}

	
	
}
