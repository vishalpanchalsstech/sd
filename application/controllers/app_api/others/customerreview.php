<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class customerreview extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->library('upload');
        $this->load->model('general_model');
        $this->load->model('job_model');
    }
	
	/*Header
		Content-Type:application/json
		apiKey:SST100000000003
	*/
	/*Request Body Parameters
		{			
			"jobid": "SSTVJOB10010",
			"rated":"2.0",
			"reviewdetails":"this is sample review data."
		}
	*/
	public function customerratedreview()
	{
		/*$jobid = $this->input->post('jobid');
		$customertoken = $this->input->post('customertoken');
		$rated = $this->input->post('rated');
		$reviewdetails = $this->input->post('reviewdetails');*/
		
		$request_data = json_decode(file_get_contents('php://input'),true);
		$header =getallheaders();
		//print_r($request_data);exit;
		/***** Check the header and request type and body ******/
		  if($header['Content-Type'] != "application/json")
		  {
			$message['success'] = false; 
			$message['message'] =  'Invalid content-type in header only json allowed.';
			echo json_encode($message); exit;
		  } 
		  if(empty($header['apiKey']))
		  {
			$message['success'] = false; 
			$message['message'] =  'Missing apikey in header.';
			echo json_encode($message); exit;
		  }
		  else{
			  $customertoken = $header['apiKey'];
		  }
		  if(!$this->isJson($request_data)){
			 $error = json_last_error_msg();
			 $message['success'] = false; 
			 $message['message'] =  'Not valid JSON string ($error)';
			 echo json_encode($message); exit;
		  } 
		  if(!isset($request_data) && empty($request_data))
		   {
			$message['success'] = false; 
			$message['message'] =  'Missing request data.';
			echo json_encode($message); exit;
		  }
		 
		
		$verify_token = $this->general_model->verify_token($customertoken,'customer');
		if($verify_token['result']==0){
			$arr_json['success'] = false;
			$arr_json['message'] = $verify_token['msg'];
			echo json_encode($arr_json);exit;
		}
		else{
		  $jobid = $request_data['jobid'];
		  $rated = $request_data['rated'];
		  $reviewdetails= $request_data['reviewdetails'];
		}
		
		if(!empty($jobid) && !empty($customertoken) && !empty($rated) && !empty($reviewdetails))
		{	
			$jobexistcheck_result = $this->job_model->jobexistcheckreview($jobid);
			if(empty($jobexistcheck_result)){
				$arr_json['success'] = false;
				$arr_json['message'] = "Sorry,Job not Exist.";
				echo json_encode($arr_json);exit;
			}
			else{
				//$Rated = $jobexistcheck_result[0]->Rated;
				$ReviewDetails = $jobexistcheck_result[0]->ReviewDetails;
				$Id = $jobexistcheck_result[0]->Id;
				if($ReviewDetails==''){
					
					$length = strlen($reviewdetails);
					if($length>=255){
					   $arr_json['success'] = false;
					   $arr_json['message'] = "Review details not allow more than 255 character.";
					   echo json_encode($arr_json);exit;
					}

					$review_arr = array(
								'Rated'=>$rated,
								'ReviewDetails'=>$reviewdetails,
								'UpdatedAt'=>date('Y-m-d H:i:s')		
								);	
					$update_reviewdata = $this->job_model->review_data_update($review_arr,$Id);	
					if($update_reviewdata==1){
						$reviewdata_result = $this->job_model->get_review_data($Id);
						
						$arr_json['success'] = true;
						$arr_json['message'] = "Review Added Successfully.";
						$arr_json['result'] = $reviewdata_result;
						echo json_encode($arr_json);exit;
					}
					else{
						$arr_json['success'] = false;
						$arr_json['message'] = "Problem occurred while updating data.";
						echo json_encode($arr_json);exit;
					}
						
				}else{
						$arr_json['success'] = false;
						$arr_json['message'] = "Sorry,You have already gave Review and Rating for this Job.";
						echo json_encode($arr_json);exit;
				}
			}
		}
		else
		{
			$arr_json['success'] = false;
			$arr_json['message'] = "Please Enter Parameters.";
			echo json_encode($arr_json);exit;
		}
	}
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
