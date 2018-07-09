<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class jobprogress extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->model('job_model');
	    $this->load->model('general_model');
    }
	
	public function ProgressJob()
	{
		$JobId = $this->input->post('JobId');
		// $JobStartTime = $this->input->post('JobStartTime');
		$latitude = $this->input->post('Latitude');
		$longitude = $this->input->post('Longitude');
		$Token = $this->input->post('Token');
		$type = "driver";
		
		$JobStartTime = date('Y-m-d h:i:s');
		// print_r($JobStartTime);exit;
		/*start verify the apitoken with company token weather exist in db or not*/
		$token_result = $this->general_model->verify_token($Token,$type);   
		
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
		   $message['message'] =  'Invalid API token, Please Check apikey.';   
		   echo json_encode($message); exit; 
		}
		/*end verify token */
		if(!empty($JobId) && !empty($JobStartTime) && !empty($latitude) && !empty($longitude) && !empty($Token))
		{
				$jobresult = $this->job_model->jobexistcheck($JobId);
				if(!empty($jobresult))
				{	
						$json_accepteddriverid = json_decode($jobresult[0]->AcceptedDriverId);
						$json_accepteddriverid->DriverUserId = $DriverUserId;
						$json_encode = json_encode($json_accepteddriverid);
								
						$insert_inprogress_aray = array (
												'JobId' =>$JobId,
												'CompanyId' =>$jobresult[0]->CompanyId,
												'UserId' =>$jobresult[0]->UserId,
												'pickupDetail' =>$jobresult[0]->PickupDetail,
												'dropoffDetail' =>$jobresult[0]->DropoffDetail,
												'ScheduleJobTime' =>$jobresult[0]->ScheduleJobTime,
												'Distance' =>$jobresult[0]->Distance,
												'Duration' =>$jobresult[0]->Duration,
												'DistanceStatus' =>$jobresult[0]->DistanceStatus,
												'Consignment' =>$jobresult[0]->Consignment,
												'RejectDriverId' =>$jobresult[0]->RejectDriverId,
												'CancelDriverId' =>$jobresult[0]->CancelDriverId,
												'AcceptedDriverId' =>$json_encode,
												'DriverId' =>$DriverUserId,
												'CreatedVia' =>$jobresult[0]->CreatedVia,
												'CreatedBy' =>$jobresult[0]->CreatedBy,
												'DeletedBy' =>$jobresult[0]->DeletedBy,
												'JobStatus' =>4,
												'Enable' =>1
											);
						$insert_tracking_array = array(
												'JobId' =>$JobId,
												'DriverId' =>$DriverUserId,
												'PickupDetail' =>$jobresult[0]->PickupDetail,
												'DropoffDetail' =>$jobresult[0]->DropoffDetail,
												'CurrentLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
												'UpdatedLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
												'StartTime' =>$JobStartTime
												);
						
						/*start Insert Data*/	
						$tracking_result = $this->job_model->insert_tracking_detail($insert_tracking_array);
						$inprogressjob_result = $this->job_model->insert_progressjob_detail($insert_inprogress_aray);
						/*End Insert Data*/		
						
						/*Start Get Data from jobmaster and tracking through the last_id*/	
						$get_progressjob_result = $this->job_model->get_jobmaster_data($inprogressjob_result);
						$get_progressjob_result[0]->PickupDetail = json_decode($get_progressjob_result[0]->PickupDetail);
						$get_progressjob_result[0]->DropoffDetail = json_decode($get_progressjob_result[0]->DropoffDetail);
						$get_progressjob_result[0]->Distance = json_decode($get_progressjob_result[0]->Distance);
						$get_progressjob_result[0]->Duration = json_decode($get_progressjob_result[0]->Duration);
						$get_progressjob_result[0]->AcceptedDriverId = json_decode($get_progressjob_result[0]->AcceptedDriverId);
						
						$get_tracking_result = $this->job_model->get_tracking_data($tracking_result);
						$get_tracking_result[0]->PickupDetail = json_decode($get_tracking_result[0]->PickupDetail);
						$get_tracking_result[0]->DropoffDetail = json_decode($get_tracking_result[0]->DropoffDetail);
						$get_tracking_result[0]->CurrentLocation = json_decode($get_tracking_result[0]->CurrentLocation);
						$get_tracking_result[0]->UpdatedLocation = json_decode($get_tracking_result[0]->UpdatedLocation);
						/*End GetData*/		
						
						$arr_json['ProgressJobDetails'] = $get_progressjob_result;
						$arr_json['TrackingDetails'] = $get_tracking_result;
				}
				else
				{
					$arr_json['success'] = "0";
					$arr_json['message'] = "No Job Found.";
					
				}
		}
		else
		{
				$arr_json['success'] = "0";
				$arr_json['message'] = "All fields are required.";
		}
		echo json_encode($arr_json);
	}
	
	
}
