<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// {
//     "apiKey": "MERCHANT_KEY",
//     "jobId":"38befd2d-8c4d-498a-a1ce-07a1c9dde15e",
//     "cancellationNotes" : "Customer left town in a hurry"
// }            
        
class Canceldelivery extends CI_Controller {    
    function __construct() {
        parent::__construct();
        $this->load->model('companymaster_model');
        $this->load->model('general_model');
        $this->load->model('job_model');       
        header("Access-Control-Allow-Origin: *");
    }
	
    public function index()
    {		
			$request_data = json_decode(file_get_contents('php://input'),true);
			// print_r($request_data);exit;
			if(!$this->isJson($request_data)){
					$error = json_last_error_msg();
					$message['success'] = false; 
					$message['message'] =  'Not valid JSON string ($error)';
					echo json_encode($message); exit;
					//echo "Not valid JSON string ($error)";exit;
			}	
			
			if(empty($request_data['apiKey'])){
					$message['success'] = false; 
					//$message['message'] =  'Please enter access key in header';
					$message['message'] =  'Please enter apiKey as Label in Header';
					echo json_encode($message); exit;
			}
			else{
					$token =$request_data['apiKey'];	
			}
			
			
			if(array_key_exists('jobId',$request_data))
			{
				if(empty($request_data['jobId'])){
					$message['success'] = false; 
					$message['message'] =  'jobId empty not allowed';
					echo json_encode($message); exit;
				}	
			}else{
				$message['success'] = false; 
				$message['message'] =  'Please Declare Value of jobId in Request Body';
				echo json_encode($message); exit;	
			}

			if(array_key_exists('cancellationNotes',$request_data))
			{
				if(empty($request_data['cancellationNotes'])){
					$message['success'] = false; 
					$message['message'] =  'cancellationNotes empty not allowed';
					echo json_encode($message); exit;
				}	
			}else{
				$message['success'] = false; 
				$message['message'] =  'Please Declare Value of cancellationNotes in Request Body';
				echo json_encode($message); exit;	
			}			
			
			
			/*** start verify the apitoken with company token weather exist in db or not ***/
			$token_result = $this->general_model->verify_token($token,"driver");
			//echo $token_result['data'][0]->UserId;
			//print_r($token_result);exit;
			// echo $token_result['result'];
			// print_r($token_result);exit;			
			$DriverUserId="";
			if($token_result['result']==0){
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check apikey.';
				// $message['data'] = $token_result;
				echo json_encode($message); exit;	
			}
			else{
				$DriverUserId = $token_result['data'][0]->UserId;
			}
			/*** end verify token ***/
			

			if(isset($request_data['jobId']) && !empty($request_data['jobId']) && !empty($DriverUserId)){
					$jobId = $this->security->xss_clean($request_data['jobId']);
					$JobQuery="SELECT JobId FROM jobmaster where JobId='".$jobId."' ORDER BY Id DESC LIMIT 0,1";
					$execute=$this->db->query($JobQuery);
					$JobResult=$execute->result(); 
					if(!empty($JobResult)){
						$request_data['DriverId']=$DriverUserId;
						$update_array= array(									
										'CancelDriverId' => json_encode($request_data),
										'DriverId' => $DriverUserId,
										'JobStatus'=>2,
										'UpdatedAt'=>date('Y-m-d h:i:s')
									    );
						// print_r($update_array);exit;
						$updated_result= $this->job_model->Canceldelivery($jobId,$update_array);

						if($updated_result){

							// $get_currjob_details = $this->job_model->get_jobmaster_byjobId($jobId);
							$get_jobmaster_details = $this->job_model->get_jobmaster_byjobId($jobId);

							$booking['id'] = $get_jobmaster_details[0]->Id; 

							$booking['JobId'] = $get_jobmaster_details[0]->JobId;

							
							$booking['CancelDetails'] = $get_jobmaster_details[0]->CancelDriverId;

							$booking['DriverId'] = $get_jobmaster_details[0]->DriverId;
							
							
							if(isset($get_jobmaster_details[0]->ScheduleJobTime)){
							$booking['ScheduleJobTime'] = $get_jobmaster_details[0]->ScheduleJobTime;
							}
							$booking['Consignment'] = $get_jobmaster_details[0]->Consignment;

							$booking['JobStatus'] = $get_jobmaster_details[0]->StatusName;	
							$booking['CreatedVia'] = $get_jobmaster_details[0]->CreatedVia;			
							$booking['created'] = $get_jobmaster_details[0]->CreatedAt;	

							$get_jobmaster_details[0]->PickupDetail = json_decode($get_jobmaster_details[0]->PickupDetail,true);
							$get_jobmaster_details[0]->DropoffDetail = json_decode($get_jobmaster_details[0]->DropoffDetail,true);
							$booking['PickupDetail']['name'] = $get_jobmaster_details[0]->PickupDetail['name']; 
							$booking['PickupDetail']['address'] = $get_jobmaster_details[0]->PickupDetail['address']; 
							$booking['PickupDetail']['phone'] = $get_jobmaster_details[0]->PickupDetail['phone']; 
							
							$booking['DropoffDetail']['name'] = $get_jobmaster_details[0]->DropoffDetail['name']; 
							$booking['DropoffDetail']['address'] = $get_jobmaster_details[0]->DropoffDetail['address']; 
							$booking['DropoffDetail']['phone'] = $get_jobmaster_details[0]->DropoffDetail['phone']; 


							$get_jobmaster_details[0]->Distance=json_decode($get_jobmaster_details[0]->Distance,true);
							$get_jobmaster_details[0]->Duration=json_decode($get_jobmaster_details[0]->Duration,true);
							$get_jobmaster_details[0]->DistanceStatus=json_decode($get_jobmaster_details[0]->DistanceStatus,true);

							$booking['distanceKm'] = $get_jobmaster_details[0]->Distance['text'];
							$booking['Duration'] = $get_jobmaster_details[0]->Duration['text'];
							$booking['DistanceStatus'] = $get_jobmaster_details[0]->DistanceStatus;
														
							

							$booking['Compnay']['CompanyId'] = $get_jobmaster_details[0]->CompanyId;
							$booking['Compnay']['CompnayName'] = $get_jobmaster_details[0]->CompnayName;
							$booking['Compnay']['CompanyEmail'] = $get_jobmaster_details[0]->CompanyEmail;
							$booking['Compnay']['CompanyLogo'] = base_url().$get_jobmaster_details[0]->CompanyLogo;
							$booking['Compnay']['CompanyToken'] = base_url().$get_jobmaster_details[0]->CompanyToken;
							$booking['Compnay']['NotificationMethod'] = $get_jobmaster_details[0]->NotificationMethod;

							// $booking['CustomeName'] = $get_jobmaster_details[0]->UserName;
							
							$booking['Customer']['CustomerId'] = $get_jobmaster_details[0]->CustomerId;
							$booking['Customer']['CustomerEmail'] = $get_jobmaster_details[0]->CustomerEmail;
							
							$booking['lastUpdated'] = $get_jobmaster_details[0]->UpdatedAt; 
							
							$booking['Enable'] = $get_jobmaster_details[0]->Enable; 

							
							
							// echo json_encode($booking);

							/******** Completed the Cancel job process and start the new job create with same details with new jobId and status to created @v*************/

								
							$GenerateJobId="";				
							$JobQuery="SELECT JobId FROM jobmaster ORDER BY Id DESC LIMIT 0,1";
							$execute=$this->db->query($JobQuery);
							$JobResult=$execute->result(); 
							$JobId='';
							foreach($JobResult as $JobResultValue){
								$JobId=$JobResultValue->JobId;
							}
							$exp = explode("SSTVJOB",$JobId);
							$exp_job='';
							if(isset($exp[1])){
							 //$exp[1] = null;	
							 $exp_job = $exp[1]+1;
							}
							$JobId1 = $exp_job;
							if($JobId1 < 10000){
								$JobNumberValues=10001;
								$GenerateJobId= "SSTVJOB".$JobNumberValues;
							}
							else{
							$exp = explode("SSTVJOB",$JobId);
							$exp_job = $exp[1]+1;
							$JobNumberValues=$exp_job;
							$GenerateJobId= "SSTVJOB".$JobNumberValues;
							}

							$get_all_jobdata = $this->job_model->get_all_jobdata($jobId);
											
							$insert_array = array(					
								'UserId'=>$get_all_jobdata[0]->UserId,
								'JobId'=>$GenerateJobId,
								'CompanyId'=>$get_all_jobdata[0]->CompanyId,
								'PickupDetail' =>$get_all_jobdata[0]->PickupDetail,
								'DropoffDetail' =>$get_all_jobdata[0]->DropoffDetail,
								'Distance' =>$get_all_jobdata[0]->Distance,
								'Duration' =>$get_all_jobdata[0]->Duration,
								'DistanceStatus' =>$get_all_jobdata[0]->DistanceStatus,
								'Consignment' =>$get_all_jobdata[0]->Consignment,						
								'JobStatus'=>1,
								'CreatedVia'=>'RESTAPI',
								'CreatedBy'=>$get_all_jobdata[0]->CompanyId
							);
							// print_r($insert_array);exit;					
							
							$currenjob_insert_data = $this->job_model->insert_currentjob_data($insert_array);

							$message['success'] = true; 
							$message['message'] =  'Job Cancel successfully.';
							$message['booking'] =  $booking;

						}else{
							$message['success'] = false; 
							$message['message'] =  'Something went wrong';
							
						}

					}
					else{					
						
							$message['success'] = false; 
							$message['message'] =  'Error:Zero Result Found.';
							// echo json_encode($message); 
					}
					
			}
			else{
				$message['success'] = false; 
				$message['message'] = "Invalid JobId or request";
			}		
			echo json_encode($message);exit;
			
    }
   
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}
	public function get_customer_details($Access_Key){
		if(empty($Access_Key)){
			$Access_Key = '';
		}
		$query = $this->db->query("select * from customers where apiToken LIKE'%".$Access_Key."%' ");
		$customer_result = $query->result();
		return $customer_result;
	}
	
}
