<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completedjob extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->library('upload');
        $this->load->model('general_model');
        $this->load->model('job_model');
		$general = $this->load->library('../controllers/general'); 
    }
		
	public function InsertCompeltedJob()
	{
		/*Request for this Api Parameters
		
		Header
		apiKey:SST100000000019
		Form-data
		jobid:SSTVJOB10001
		notes:this is my test data
		Signature:upload image
		
		*/
		
		$header =getallheaders();
		if(empty($header['apiKey']))
		{
			$message['success'] = false; 
			$message['message'] =  'Missing apikey in header.';
			echo json_encode($message); exit;
		}
		else{
			  $token = $header['apiKey'];
		}
		$verify_token = $this->general_model->verify_token($token,'driver');
		if($verify_token['result']==0){
			$arr_json['success'] = false;
			$arr_json['message'] = $verify_token['msg'];
			echo json_encode($arr_json);exit;
		}
		else{
			$jobid = $this->input->post('jobid');
			$notes = $this->input->post('notes');
		}
		
		if(!isset($_FILES['Signature']))
		{
			$arr_json['success'] = false;
			$arr_json['message'] = "Please insert Image.";
			echo json_encode($arr_json);exit;
		}
		
		if(!empty($jobid) && !empty($notes))
		{	
			$jobstatus=5;
			$jobcompletedexistcheck = $this->job_model->JobIdStatusCheck($jobid,$jobstatus);
			if(!empty($jobcompletedexistcheck)){
				$arr_json['success'] = false;
				$arr_json['message'] = "You have already completed this job.";
				echo json_encode($arr_json);exit;
			}
		
			$JobQuery="SELECT * FROM jobmaster where JobId='".$jobid."' AND JobStatus=4";
			$execute=$this->db->query($JobQuery);
			$JobResult=$execute->result(); 
			//print_r($JobResult);exit;
			if(!empty($JobResult)){
				$JobId=$JobResult[0]->JobId;
				$UserId=$JobResult[0]->UserId;
				$CompanyId=$JobResult[0]->CompanyId;
				$DriverId=$JobResult[0]->DriverId;
				$RejectDriverId=$JobResult[0]->RejectDriverId;
				$OriginDetails=$JobResult[0]->PickupDetail;
				$DestinationDetails=$JobResult[0]->DropoffDetail;
				$Distance=$JobResult[0]->Distance;
				$Duration=$JobResult[0]->Duration;
				$DistanceStatus=$JobResult[0]->DistanceStatus;
				$AcceptedDriverId=$JobResult[0]->AcceptedDriverId;
				$CreatedVia=$JobResult[0]->CreatedVia;
				$CreatedBy=$JobResult[0]->CreatedBy;
				$DeletedBy=$JobResult[0]->DeletedBy;
				$TimeZone=$JobResult[0]->TimeZone;
				
				if(isset($_FILES['Signature']['name'])){
				$profile_target_path = 'assets/uploads/images/driver/';
				//$ext = pathinfo($_FILES['Signature']['name'], PATHINFO_EXTENSION);
				$profile_name = $profile_target_path.$_FILES['Signature']['name'];
				move_uploaded_file($_FILES["Signature"]["tmp_name"], $profile_name);
				}
				
				
				$getTimeZone = $this->general->getCompanyTimeZonebyId($CompanyId);
				if($getTimeZone['success']==1){
					$CompanyTimezone = $getTimeZone['TimeZone'][0];
					$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
				}
				
				$completejob_array = array(
				'CompanyId'=>$CompanyId,
				'JobId'=>$JobId,
				'UserId'=>$UserId,
				'PickupDetail'=>$OriginDetails,
				'DropoffDetail'=>$DestinationDetails,
				'Distance'=>$Distance,
				'Duration'=>$Duration,
				'DistanceStatus'=>$DistanceStatus,
				'AcceptedDriverId'=>$AcceptedDriverId,
				'CreatedVia'=>$CreatedVia,
				'CreatedBy'=>$CreatedBy,
				'CreatedAt'=>$currentdt,
				'DeletedBy'=>$DeletedBy,
				'TimeZone'=>$TimeZone,
				'DriverId'=>$DriverId,
				'RejectDriverId'=>$RejectDriverId,
				'Signature'=> base_url().$profile_name,
				'JobStatus'=>5,
				'Notes'=>$notes
				);
				$review_array = array(
				'JobId'=>$JobId,		
				'DriverId'=>$DriverId,
				'CustomerId'=>1
				//'Rated'=>$Rated,
				//'ReviewDetails'=>$ReviewDetails
				);
				//$completejob_result = $this->general_model->insert_completejob_data($completejob_array);
				$completejob_result = $this->job_model->insert_currentjob_data($completejob_array);
				
				if($completejob_result!=0){
					//$get_completejob_data = $this->general_model->Get_completejob_data($completejob_result);
					$get_completejob_data = $this->job_model->get_jobmaster_data($completejob_result);
					if(!empty($get_completejob_data)){
						$jsondecode=json_decode($get_completejob_data[0]->PickupDetail);
						$get_completejob_data[0]->PickupDetail = $jsondecode;
						$jsondecode=json_decode($get_completejob_data[0]->DropoffDetail);
						$get_completejob_data[0]->DropoffDetail = $jsondecode;
						$jsondecode=json_decode($get_completejob_data[0]->Distance);
						$get_completejob_data[0]->Distance = $jsondecode;
						$jsondecode=json_decode($get_completejob_data[0]->Duration);
						$get_completejob_data[0]->Duration = $jsondecode;
						$jsondecode=json_decode($get_completejob_data[0]->DistanceStatus);
						$get_completejob_data[0]->DistanceStatus = $jsondecode;
						$jsondecode=json_decode($get_completejob_data[0]->AcceptedDriverId);
						$get_completejob_data[0]->AcceptedDriverId = $jsondecode;
						
						$myarray['completejob'] = $get_completejob_data;
					}
					else{
						$arr_json['success'] = false;
						$arr_json['message'] = "No Record Found.";
						echo json_encode($arr_json);exit;
					}
				}
				else{
					$arr_json['success'] = false;
					$arr_json['message'] = "Error Occured for Storing Data";
					echo json_encode($arr_json);exit;
				}
				$review_result = $this->general_model->insert_review_data($review_array);
				if($review_result!=0){
					$get_review_data = $this->general_model->Get_review_data($review_result);
					if(!empty($get_review_data)){
						$myarray['review'] = $get_review_data;
					}
					else{
						$arr_json['success'] = false;
						$arr_json['message'] = "No Record Found.";
						echo json_encode($arr_json);exit;
					}
				}
				else{
					$arr_json['success'] = false;
					$arr_json['message'] = "Error Occured for Storing Data";
					echo json_encode($arr_json);exit;
				}
				
				echo json_encode($myarray);exit;
			}
			else{
				$arr_json['success'] = false;
				$arr_json['message'] = "No Record Found.";
				echo json_encode($arr_json);exit;
			}
			
			
		}
		else
		{
			
			$arr_json['success'] = false;
			$arr_json['message'] = "Please Enter Parameters.";
			echo json_encode($arr_json);exit;
		}
		//echo json_encode($arr_json);
	}
}
