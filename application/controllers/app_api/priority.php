<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class priority extends CI_Controller {    
    function __construct() {
        parent::__construct();
        $this->load->model('priority_model');
        $this->load->model('general_model');
        $this->load->model('usermaster_model');
        $this->load->model('job_model');
        $general = $this->load->library('../controllers/general');       
        require_once APPPATH."controllers/app_api/notifications.php";      
    }       

	 
	public function JobNotification()
	{
		
		$jobresult = $this->priority_model->getallpriorityjob();
		
		if(!empty($jobresult))
		{	
			
			foreach ($jobresult as $jobresult) 
			{			
				// print_r($jobresult);exit;
				$JobPriority = "";
				$Jobmaster_id = "";
				$CompanyId = "";
				$JobId = "";
				$JobBrodcast = "";
				$JobPriority = $jobresult->JobPriority;
				$Jobmaster_id = $jobresult->Id;
				$CompanyId = $jobresult->CompanyId;
				$JobId = $jobresult->JobId;
				$JobBrodcast = $jobresult->JobBrodcast;

							$get_jobmaster_details = $this->job_model->get_job_data($Jobmaster_id);
							//print_r($get_jobmaster_details);exit;

							$booking['id'] = $get_jobmaster_details[0]->Id; 

							$booking['JobId'] = $get_jobmaster_details[0]->JobId;
							
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
							$booking['Compnay']['CompanyToken'] = $get_jobmaster_details[0]->CompanyToken;
							$booking['Compnay']['NotificationMethod'] = $get_jobmaster_details[0]->NotificationMethod;

							// $booking['CustomeName'] = $get_jobmaster_details[0]->UserName;
							
							$booking['Customer']['CustomerId'] = $get_jobmaster_details[0]->CustomerId;
							$booking['Customer']['CustomerName'] = $get_jobmaster_details[0]->CustomerName;
							$booking['Customer']['CustomerEmail'] = $get_jobmaster_details[0]->CustomerEmail;
							
							$booking['lastUpdated'] = $get_jobmaster_details[0]->UpdatedAt; 
							
							$booking['Enable'] = $get_jobmaster_details[0]->Enable; 

							// echo json_encode($booking);
							$message['success'] = true; 
							$message['message'] =  'Job Details.';
							$message['booking'] =  $booking;
			
					/** To get Notification method for particular company related to job ***/
					$Notification_method = $this->priority_model->Get_Notification_method($CompanyId);
					$Notification_method = $Notification_method[0]->NotificationMethodId;
					//echo $Notification_method;exit;
					
					/******* Priority Job ***********/
					if($Notification_method==2)
					{
						$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($CompanyId);
						// echo "<pre>";print_r($getcmpnydriverdetails);//exit();
						if($JobPriority=='')
						{
							$update_array = array(
													'JobPriority'=>1
												 );
							$Update_jobpriority = $this->priority_model->Update_jobpriority_field($Jobmaster_id,$update_array);
							
							foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
									{
										if($driverdetails->Priority == 1){
											// print_r($Priority_driver);exit;
											$data = array('mtitle' => "New Job(P)!!",
													   'mdesc'=>"Job Notification",
													   'text'=>$message
											);
											/**** iOS Device Push Notification *****/
											if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
											{
												$devicetoken = $driverdetails->DeviceUniquId;		
												$message['iOS'][$driverdetails->DeviceUniquId] = notifications::iOS($data, $devicetoken);

											}
											/**** Android Device Push Notification *****/
											if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
												$FCMRegId = $driverdetails->FCMRegId;
												$message['android'][$driverdetails->FCMRegId] = notifications::android($data,$FCMRegId);
											}
										}
									}
							}	
																
							// $arr_json[$JobId]['success'] = "1";
							// $arr_json[$JobId]['message'] = "Priority wise Notification send Successfully.";
							// $arr_json[$JobId]['RESULT'] = $jobresult;
							$arr_json[$JobId] = $message;
						}
						else
						{
							$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($CompanyId);
							
							//print_r($getcmpnydriverdetails);//exit;

							$Send_Jobresult = $this->priority_model->Check_JobExistance($JobId,$CompanyId);

							$JobPriority = $Send_Jobresult[0]->JobPriority;
							
							$JobPriority_array = explode(",", $JobPriority);

							$JobPriority_array_count= sizeof($JobPriority_array);
							$getcmpnydriverdetails_array_count = sizeof($getcmpnydriverdetails);
							//echo "df";
								if($JobPriority_array_count != $getcmpnydriverdetails_array_count && $JobPriority_array_count<=5)
								{
									$newvalue=0;
									$newvalue = $JobPriority+1;
									$JobPriority = $JobPriority.','.$newvalue;								
									
									$update_array = array(
														'JobPriority'=>$JobPriority
													 );
									$Update_jobpriority = $this->priority_model->Update_jobpriority_field($Jobmaster_id,$update_array);								

									
									$Priority_driver = array();

									$i=0;
									//echo $newvalue;
									foreach ($getcmpnydriverdetails as $details) {
											//print_r($details);
									    if ($details->Priority==$newvalue) {
									        $Priority_driver = $getcmpnydriverdetails[$i];
									        //print_r($getcmpnydriverdetails[$i]);
									    }
									    $i++;
									}
									if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
									{
										// print_r($Priority_driver);exit;
										$data = array('mtitle' => "New Job(P)!!",
												   'mdesc'=>"Job Notification",
												   'text'=>$message
										);
										/**** iOS Device Push Notification *****/
										if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
										{
											$devicetoken = $driverdetails->DeviceUniquId;		
											$message['iOS'][$driverdetails->DeviceUniquId] = notifications::iOS($data, $devicetoken);

										}
										/**** Android Device Push Notification *****/
										if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
											$FCMRegId = $driverdetails->FCMRegId;
											$message['android'][$driverdetails->FCMRegId] = notifications::android($data,$FCMRegId);
										}
									}
									// $arr_json[$JobId]['success'] = "1";
									// $arr_json[$JobId]['message'] = "Priority wise Notification send Successfully.";
									// $arr_json[$JobId]['RESULT'] = $jobresult;
									$arr_json[$JobId] = $message;
								
								}
								else{
									$arr_json[$JobId]['success'] = "0";
									$arr_json[$JobId]['message'] = "No Priority job for send notification.";
									
								}
							
						}
						
					}
					else
					{
						/***** Brodcast ********/
						$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($CompanyId);
						foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1 && empty($JobBrodcast))
								{
									$update_array = array(
													'JobBrodcast'=>1
												 );
									$Update_jobpriority = $this->priority_model->Update_jobpriority_field($Jobmaster_id,$update_array);
									$data = array('mtitle' => "New Job(B)!!",
												   'mdesc'=>"Job Notification",
												   'text'=>$message
									);
									/**** iOS Device Push Notification *****/
									if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
									{
										$devicetoken = $driverdetails->DeviceUniquId;		
										$message['iOS'][$driverdetails->DeviceUniquId] = notifications::iOS($data, $devicetoken);

									}
									/**** Android Device Push Notification *****/
									if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
										$FCMRegId = $driverdetails->FCMRegId;
										$message['android'][$driverdetails->FCMRegId] = notifications::android($data,$FCMRegId);
									}

									// $arr_json[$JobId]['success'] = "1";
									// $arr_json[$JobId]['message'] = "Notification Broadcasted to Company Drivers.";
									// $arr_json[$JobId]['RESULT'] = $jobresult;
									$arr_json[$JobId] = $message;
								}
								
							}
						
					}		
			
			}
		}
		else
		{
			$arr_json['success'] = "0";
			$arr_json['message'] = "No Job Found.";
		}
		if(isset($arr_json)){
			echo json_encode($arr_json);
		}
		else{
			$arr_json['success'] = "0";
			$arr_json['message'] = "No Job Found.";
			echo json_encode($arr_json);	
		}
	}
	
	public function isJson($json)
	{	
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
}
