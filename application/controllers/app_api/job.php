<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends CI_Controller {    
    function __construct() {
        parent::__construct();
        $this->load->model('companymaster_model');
        $this->load->model('customermaster_model');
        $this->load->model('general_model');
        $this->load->model('usermaster_model');
        $this->load->model('job_model');   
        $this->load->model('priority_model');
        $general = $this->load->library('../controllers/general');        
        require_once APPPATH."controllers/app_api/notifications.php";
    }       

    /********** Create New Normal or Schedule Job API ***************/
    public function Create()
    {
    		/** Normal job Request **/
    		/*
    		header 
    		Content-Type:application/json
			Apikey:SST100000000003		
			
			/** Normal Job Body  **/
			/*
    		{			    
				"email": "sstech.vishal@gmail.com",
			    "booking":{
			        "pickupDetail": {
			            "name": "Rupert",
			            "phone": "1234567890",
			            "address": "57 luscombe st, brunswick, melbourne"
			        },
			        "dropoffDetail": {
			            "name": "Igor",
			            "phone": "0987654321",
			            "address": "105 collins st, 3000" 
			        }
			    }
			} 
			*/

			/** Schedule job Request **/
			/*	header 
    		Content-Type:application/json
			Apikey:SST100000000003	
			*/
			/*
    		 {			
				"email": "sstech.vishal@gmail.com",
				"pickupTime":"2018-06-02 22:23:54",
			    "booking":{
			        "pickupDetail": {
			            "name": "Rupert",
			            "phone": "1234567890",
			            "address": "57 luscombe st, brunswick, melbourne"
			        },
			        "dropoffDetail": {
			            "name": "Igor",
			            "phone": "0987654321",
			            "address": "105 collins st, 3000"
			        }
			    }
			} 
			*/

			// $headers = apache_request_headers();
			// $hdr = array();
			$request_data = json_decode(file_get_contents('php://input'),true);
			$header =getallheaders();
			//print_r($header);exit;
			/****** Check the header and request type and body @v *******/
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
			
			if(!$this->isJson($request_data)){
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
			
			
			$currentdt= date('Y-m-d h:i:s');
			$starttime = new DateTime($currentdt);
			
			if(empty($request_data['email'])){
					$message['success'] = false; 
					$message['message'] = 'Please enter parameter email';
					echo json_encode($message); exit;
			}
			else{
					$email =$request_data['email'];	
			}
			
			if(empty($header['Apikey'])){
					$message['success'] = false; 					
					$message['message'] =  'Please enter Apikey as Label in Header';
					echo json_encode($message); exit;
			}
			else{
					$token =$header['Apikey'];	
			}	
			$requestby= $token;	
			
			/*** start verify the apitoken with company token weather exist in db or not @v ***/
			$company_result = $this->companymaster_model->GetCompanyByTokenPreference($token);
		
			if(empty($company_result)){
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check Apikey.';
				echo json_encode($message); exit;	
			}
			/*** end verify token @v***/

			/*PickupDetail Request Body Some Value Empty Check*/
			foreach($request_data['booking']['pickupDetail'] as $key=>$value){
				 $value = trim($value);
				 if (empty($value)){
					 $message['success'] = false; 
					 $message['message'] =  'Please Declare Value of PickupDetail in Request Body';
					 echo json_encode($message); exit;
				 }
			}
			
			/*** DropoffDetail Request Body Some Value Empty Check ***/
			foreach($request_data['booking']['dropoffDetail'] as $key=>$value){
				 $value = trim($value);
				 if (empty($value)){
					 $message['success'] = false; 
					 $message['message'] =  'Please Declare Value of DropoffDetail in Request Body';
					 echo json_encode($message); exit;
				 }
			}
			
			/*** start verify the email with user table email weather exist in db or not ***/
			// print_r($email);exit;
			$customer_emailverify_result = $this->usermaster_model->customer_email_verify($email);
			if(empty($customer_emailverify_result)){
				/*if email not exist then create customer*/
				$original_password = $this->general_model->random_password(8);
				$create_customer = $this->create_customer($email,$original_password);
				$get_last_customer_details = $this->get_customer_data($create_customer); 
				$email = $get_last_customer_details[0]->Email;
				$password = $get_last_customer_details[0]->Password;
				if($create_customer!=0){
					$customername = explode('@',$email);
					$customername = $customername[0];
					$to = $email;
					$from = '';		
					$cc = '';
					$subject = 'SSTech Customer Created.';
					$body="
					 <div >
									<p>Hello $customername</p>
									<p>Thank you so much for allowing us to help you with your logistic needs. We are committed to provide our customers with the highest level of service and live tracking of your parcel using our iphone APP.</p>
									<p>Please download our app using [link]. Use the following credential to log in.</p>
									<table>
									<tr>
									<td><label>Email/Username :</label></td>
									<td><label type='text' style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;' name='email'>$email</label></td>
									</tr>
									<tr>
										<td><label>Password :</label></td>
										<td><label type='text'  style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;'  name='password'>$original_password</label></td>
									</tr>
									</table>
									<p>For more detailed information about any of our products or services, please refer to our website, www.sstechdriver.com, or visit any of our convenient locations.  </p>
									<p>Please do not hesitate to contact us, should you have any questions. We will contact you in the very near future to ensure you are completely satisfied with the services you have received thus far. </p>
									<p>Regards,</p>
									<p>SSTECH DRIVER APP</p>
					 </div>
					";
					$mail_sent = $this->general_model->mail_setup($to,$cc,$from,$subject,$body);
					if($mail_sent==1){
						$booking['EmailStatus'] = 'Email Sent Succesfully.';
					}
				}
			}
			//print_r($customer_emailverify_result);exit;			
			
			$to = $request_data['booking']['pickupDetail']['address'];
			$from = $request_data['booking']['dropoffDetail']['address'];
			
			// $from = "sr nagar,hyderabad";
			// $to = "kukatpalle,hyderabad";
			$from = urlencode($from);
			$to = urlencode($to);
			try {
			 $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
			}
			//catch exception
			catch(Exception $e) {
				if(isset($e)){
					 $message['success'] = false; 
					 $message['message'] = $e->getMessage();
					 echo json_encode($message); exit;
				}
			  	//echo 'Message: ' .$e->getMessage();
			}

			// $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			// $data = json_decode($data);
			
			if(isset($data->status) && (isset($data->rows[0]->elements[0]->status) && $data->rows[0]->elements[0]->status!= "ZERO_RESULTS")){
				//echo '<pre>';print_r($data);
				if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0])){
					$time = 0;
					$distance = 0;
					foreach($data->rows[0]->elements as $road) {
					    //$time += $road->duration->value;
					    //$distance += $road->distance->value;
					    if(isset($road->duration)){
						$time = $road->duration->text;
						}
						if(isset($road->distance)){
						$distance  = $road->distance->text;
						}
						if(isset($road->status)){
							$status = $road->status;
						}
					}		
					
					$job_status='';
					if(!empty($time) && !empty($distance)){
						$job_status=1;
					}else{
						$job_status=0;
					}
					
					$company_id = $company_result[0]->CompanyId;
		
					foreach($data->rows[0]->elements as $road) {
						//$key['row']=$val;
						if(isset($road->distance)){
						$distance = $road->distance;
						}else{$distance=null;}
						if(isset($road->duration)){
						$duration = $road->duration;
						}else{$duration=null;}
						$status = $road->status;
					}
					
					// $JobQuery="SELECT JobId FROM jobmaster ORDER BY Id DESC LIMIT 0,1";
					$JobQuery="SELECT sequencenumber FROM sequencemaster ORDER BY Id DESC LIMIT 0,1";
					$execute=$this->db->query($JobQuery);
					$JobResult=$execute->result(); 
					$JobId='';
					foreach($JobResult as $JobResultValue){
						$JobId=$JobResultValue->sequencenumber;
					}
					//$exp = explode("SSTVJOB",$JobId);
					//$exp = $JobId;
					$exp_job='';
					if(isset($JobId)){
					 //$exp[1] = null;	
					 $exp_job = $JobId+1;
					}
					$JobId1 = $exp_job;
					if($JobId1 < 100000000000){
						$JobNumberValues=100000000001;
						$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
					else{
					$exp = explode("SSTVJOB",$JobId);
					$exp_job = $JobId+1;
					$JobNumberValues=$exp_job;
					$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
					
					$sequenceNumberRow=array(
					   "sequencenumber"=>$JobNumberValues
					); 
					$this->db->insert('sequencemaster',$sequenceNumberRow);
					
					$pickupDetail['pickupDetail'][] = $data->destination_addresses;
					$dropoffDetail['dropoffDetail'][] = $data->origin_addresses;
					
					/*Get UserId through the Email*/
					$userdetails = $this->usermaster_model->customer_email_verify($email);
					if(isset($userdetails[0]->Id)){
						$userid = $userdetails[0]->Id;
					}else{ $userid='';}

					/******* Start:  Get the company time zone and then compare time diffrence @v****/
					// $ScheduleJobTime ="0000-00-00 00:00:00";
					$ScheduleJobTime ="";
					$currentdt="";
					$CompanyTimezone="";
					if(isset($request_data['pickupTime'])){
						if($this->validateDatetime($request_data['pickupTime']))
						{					
							//echo "valide time";exit;
							$getTimeZone = $this->general->getCompanyTimeZonebyId($company_id);
							//print_r($getTimeZone);//exit;
							$currentdt="";
							if($getTimeZone['success']==1){
								$CompanyTimezone = $getTimeZone['TimeZone'][0];
								//echo $CompanyTimezone;
								$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
							}else{
								$message['success'] = false; 
								$message['message'] =  'Error : Timezone not set in company.';
								$message['error'] =  $getTimeZone['error'];
								echo json_encode($message); exit;
							}		

							$ScheduleJobTime = $request_data['pickupTime'];							

							/****** 60 second buffer *******/
							$add_time=strtotime($currentdt)-60;
							$currentdtn= date('Y-m-d H:i:s',$add_time);
							
							$dtCurrent = new DateTime($currentdtn);
							
							$dtSchdule = new DateTime($ScheduleJobTime);

							/******** Check Current TimeZone **********/							
							if ( $dtCurrent > $dtSchdule ) {
    							$message['success'] = false; 
								$message['message'] =  'Company timezone '.$CompanyTimezone.' & Time:'.$currentdt.' and given Schedule JobTime '.$ScheduleJobTime.' is past datetime, Please add future time to create Schedule job, Past time not allowed.';
								echo json_encode($message); exit;	
							}							
						}
						else
						{
							$message['success'] = false; 
							$message['message'] = 'Invalid Time format, Please Check pickupTime.';
							echo json_encode($message); exit;	
						}						
					}		
					/******* End : Get the company time zone and then compare time diffrence @v****/
	 				
	 				$getTimeZone = $this->general->getCompanyTimeZonebyId($company_id);
				    if($getTimeZone['success']==1){
				        $CompanyTimezone = $getTimeZone['TimeZone'][0];
				        //echo $CompanyTimezone;
				        $currentdt = $this->general->getDatetimeNow($CompanyTimezone);
				    }

					$timezone = $company_result[0]->TimeZone;

					/****** if pickup time set the add to schdule value *********/					
					$insert_array = array(					
						'UserId'=>$userid,
						'JobId'=>$GenerateJobId,
						'CompanyId'=>$company_id,
						'PickupDetail' =>json_encode($request_data['booking']['pickupDetail']),
						'DropoffDetail' =>json_encode($request_data['booking']['dropoffDetail']),
						'Distance' =>json_encode($distance),
						'Duration' =>json_encode($duration),
						'DistanceStatus' =>json_encode($status),						
						'JobStatus'=>$job_status,
						'CreatedVia'=>'RESTAPI',
						'CreatedBy'=>$company_id,
						'TimeZone'=>$timezone,
						'CreatedAt'=>$currentdt
					);
					//echo $ScheduleJobTime;
					if(isset($ScheduleJobTime) && !empty($ScheduleJobTime)){
						$insert_array['ScheduleJobTime'] = $ScheduleJobTime;						
					}
					// print_r($insert_array);exit;					
					
					$currenjob_insert_data = $this->job_model->insert_currentjob_data($insert_array);
					if($currenjob_insert_data==0){
						
						if(empty($time) && empty($distance)){
							$message['success'] = false; 
							$message['message'] =  'Error:Zero Result Found.';
							echo json_encode($message);exit;
						}
					}	
					else{
							$get_jobmaster_details = $this->job_model->get_job_data($currenjob_insert_data);
							// $booking['details'] = $get_jobmaster_details;
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
							$message['message'] =  'Job created successfully.';
							$message['booking'] =  $booking;


					/** To get Notification method for particular company related to job ***/
					$Notification_method = $this->priority_model->Get_Notification_method($get_jobmaster_details[0]->CompanyId);
					if(isset($Notification_method[0]->NotificationMethodId))
					{
						$Notification_method = $Notification_method[0]->NotificationMethodId;
						//echo $Notification_method;exit;
						
						/******* Brodcast Job *******/
						if($Notification_method==1)
						{
							$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($get_jobmaster_details[0]->CompanyId);
							//print_r($getcmpnydriverdetails);exit;
							foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
								{
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
								}
							}
						}
						/******* Priority Job *******/
						if($Notification_method==2)
						{
							//echo "Yes";exit;
							$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($get_jobmaster_details[0]->CompanyId);

							//print_r($getcmpnydriverdetails);//exit;
							foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
								{
									if($driverdetails->Priority == 1)
									{
										$data = array('mtitle' => "New Job(P)!!",
												   'mdesc'=>"Job Notification",
												   'text'=>$message
										);
										/**** iOS Device Push Notification *****/
										if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
										{
											$devicetoken = $driverdetails->DeviceUniquId;		
											notifications::iOS($data, $devicetoken);
										}
										/**** Android Device Push Notification *****/
										if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
												$FCMRegId = $driverdetails->FCMRegId;
												notifications::android($data,$FCMRegId);
										}
									}
								
								}
							
							}
							
						}
					}
					/******** END Notification ***********/
					//echo json_encode($data);exit;
				 }

				}
				else{
					//echo "Invalid address or request";
					$message['success'] = false; 
					$message['message'] =  'Invalid address or request,address not found by google address.';
				}
			}
			else{
				// echo "Invalid address or request";
				$message['success'] = false; 
				$message['message'] =  'Invalid address or request';
			}
			
			// $this->general->api_logs($request_data,json_encode($message),$message['success'],$starttime);
			// $Request_Type = "Create Job";
   			/* 			$this->general->api_logs($requestby,$request_data,json_encode($message),$message['success'],$starttime,$Request_Type);*/

			$request_parameter['Parameteres']['Header'] =  array('Content-Type'=>$header['Content-Type'],'Apikey'=>$header['Apikey']);				
			$request_parameter['Parameteres']['Body'] =  array($request_data);
			$apirequest = json_encode($request_parameter);
			$Request_Type = "Create Job";
   			$this->general->api_logs($requestby,$apirequest,json_encode($message),$message['success'],$starttime,$Request_Type);

			echo json_encode($message);	
    }
    

    /*** Accpted Job API ***********/
    public function Acceptedjob()
	{
		/*
	    Header
	    Content-Type:application/json
	   	Apikey:SST100000000003  
	    Body
	    {
	     "JobId":"SSTVJOB10011",
			 "AcceptTime":"2018-05-25 15:59:49" 
	    }   
	   */
		$request_data = json_decode(file_get_contents('php://input'),true);
     	$header =getallheaders();
	     //print_r($header);exit;
     	$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);

	     /**** Check the header and request type and body @v *****/
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
	     
	    if(!$this->isJson($request_data)){
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
		    
		//$JobId = $this->input->post('JobId');
		//$AcceptTime = $this->input->post('AcceptTime');
		//$token = $this->input->post('Apikey');
		// $DriverUserId = $this->input->post('DriverUserId');
		  
		$token = $header['Apikey'];
		$JobId = $request_data['JobId'];
		$AcceptTime = $request_data['AcceptTime'];
		$type = "driver";
		$requestby= $token;	
		/*start verify the apitoken with company token weather exist in db or not*/
		$token_result = $this->general_model->verify_token($token,$type);   
		// echo $token_result['data'][0]->UserId;
		//print_r($token_result);exit;
		if($token_result['result']==0)
		{
		   $message['success'] = false; 
		   $message['message'] =  'Invalid API token, Please Check Apikey.';   
		   echo json_encode($message); exit; 
		}
		/*end verify token */

		$DriverUserId=$token_result['data'][0]->UserId;

		/******* start code for check which driver belong to this company or not ********/
		$getcompanylist = $this->job_model->get_all_jobdata($JobId);
		if(empty($getcompanylist)){
			$message['success'] = false; 
			$message['message'] =  'No job found.';   
			echo json_encode($message);exit; 
		}
		//print_r($getcompanylist);exit;		
		$CompanyId = $getcompanylist[0]->CompanyId;
		$getcmpnydriverdetails = $this->job_model->get_companydriver_details($CompanyId);
		//echo "Driver User Id -> ".$DriverUserId.'<br/>';
		//print_r($getcmpnydriverdetails);exit;
		$flag=0;
		foreach($getcmpnydriverdetails as $cmpnylist){
			$cmpnydriveruserid = $cmpnylist->DriverUserId;
			if($cmpnydriveruserid==$DriverUserId){
				$flag=1;				
			}
		}
		if($flag==0){
			$message['success'] = false; 
			$message['message'] =  'Invalid company driver or not belong to this company.';   
			echo json_encode($message);exit; 
		}
		// echo "got driver";exit;
		/*end code */		

		if(isset($AcceptTime)){	
			if($this->validateDatetime($AcceptTime))
			{	
							$getTimeZone = $this->general->getCompanyTimeZonebyId($CompanyId);
							// print_r($getTimeZone);exit;
							$currentdt="";
							if($getTimeZone['success']==1){
								$CompanyTimezone = $getTimeZone['TimeZone'][0];
								//echo $CompanyTimezone;
								$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
							}
							//echo $currentdt;exit;
							/****** 60 second buffer *******/
							$add_time=strtotime($currentdt)-60;
							$currentdtn= date('Y-m-d H:i:s',$add_time);

							$dtCurrent = new DateTime($currentdtn);
							$dtAccept = new DateTime($AcceptTime);

							if ( $dtCurrent > $dtAccept) {
								$message['success'] = false; 
								$message['message'] = 'Please add future time to accept job, Past time not allowed.';
								echo json_encode($message); exit;	
							}							
			}
			else
			{
				$message['success'] = false; 
				$message['message'] = 'Invalid Time format, Please Check AcceptTime.';
				echo json_encode($message); exit;	
			}						
		}
		else{
			$message['success'] = false; 
			$message['message'] = 'Empty AcceptTime, Please Check AcceptTime.';
			echo json_encode($message); exit;
		}	

		/*********** Check the any driver already accpted this job or not ********/
		$jobresult = $this->job_model->jobexistcheck($JobId);
		
		// print_r($jobresult);exit;

		if(!empty($jobresult)){			

			$accpeteddriverid = $jobresult[0]->AcceptedDriverId;
			$jsondecode = json_decode($accpeteddriverid);
			$job_accepted_time = $jsondecode->AcceptedTime;
			//$job_accepted_time_exp = explode(' ',$job_accepted_time);
			$oldTime = new DateTime($job_accepted_time);
			$newtime = new DateTime($AcceptTime);
			$time_interval = date_diff($oldTime,$newtime);
			$difference_second = $time_interval->s;
			$difference_minutes = $time_interval->m;

			//print_r($time_interval);exit;
			$accepteddriver_JobId = $jsondecode->JobId;
			$accepteddriver_DriverUserId = $jsondecode->DriverUserId;
			
			//check driverid and jobid and job accepted same found
			if($JobId==$accepteddriver_JobId && $DriverUserId == $accepteddriver_DriverUserId){
					$arr_json['success'] = "0";
					$arr_json['message'] = "Sorry,you have already accepted this job.";
			}
			//check jobid same but driverid not same and job accepted and time difference not 1 second
			else if($JobId==$accepteddriver_JobId && $DriverUserId != $accepteddriver_DriverUserId && $difference_second!=1)
			{
					$arr_json['success'] = "0";
					$arr_json['message'] = "Sorry,Job Already Assign To Someone else.";
			}
			//check jobid same but driverid not same and job accepted and time difference not 1 second
			/*else if($JobId==$accepteddriver_JobId && $DriverUserId != $accepteddriver_DriverUserId && $difference_minutes!=30){
					$arr_json['success'] = "0";
					$arr_json['message'] = "Sorry,You have already assigned one job.";
			}*/
			else
			{
					$arr_json['success'] = "0";
					//$arr_json['message'] = "Sorry,Job Already Assign To Someone else.";
					$arr_json['message'] = "both condition false";
			}
			//print_r($difference);exit;
		}
		else{
			
			/*********** if no one driver yet accepted this job then allow to accpet driver *****/
			if(!empty($JobId) && !empty($DriverUserId) && !empty($AcceptTime) && !empty($token))
			{

				$currentjobdetails = $this->job_model->get_all_jobdata($JobId);
				// echo "Current job details-";print_r($currentjobdetails);//exit;
				/***** check created job is noraml or schedule job **********/
				$current_schedule_jobtime="";
				if(!empty($currentjobdetails[0]->ScheduleJobTime)){
				$current_schedule_jobtime = $currentjobdetails[0]->ScheduleJobTime;
				}
				// echo $current_schedule_jobtime;exit;
				/**** Get Previous Running and accepted job of current driver **********/
				$driverprevious_job = $this->job_model->findacceptedjob($DriverUserId);
				// print_r($driverprevious_job);//exit;
				$scheduled_difference_minutes="";
				$previous_schedulejob="";
				foreach($driverprevious_job as $job){
					//print_r($job);exit;
					$accpteddriver = json_decode($job->AcceptedDriverId);
					$previous_acceptjobtime = $accpteddriver->AcceptedTime;
					if(isset($job->ScheduleJobTime) && !empty($job->ScheduleJobTime))
					{
						$previous_schedulejob = $job->ScheduleJobTime;
					}

					// echo "======schedule Job with previous normal job accept time=======<br>";
					// echo '<br>current schedule jobtime->';print_r($current_schedule_jobtime);echo '<br>';
					// // echo "current job accept time - ";print_r($AcceptTime);echo'<br>';
					// echo "previous job accepted time -> ";print_r($previous_acceptjobtime);echo'<br>';
					
					/*Scheduled Job*/					
					if(!empty($current_schedule_jobtime) && empty($previous_schedulejob)){
						$oldTime = strtotime($current_schedule_jobtime);
						$newtime = strtotime($previous_acceptjobtime);
						$scheduled_difference_minutes= round(abs($oldTime - $newtime) / 60,2);//. " minute";exit;
						// echo "Schdule Job Time Diffrence with Previous Job Accepted Time - ".$scheduled_difference_minutes;echo'<br>';//exit;
						if($scheduled_difference_minutes <= 30){
							// echo "first one";							
							$message['success'] = 'false';							
							// $message['message'] =  'Sorry,you have already accepted one scheduled job.';
							$message['message'] =  'Sorry,you have already one job accepted.(N)';
							echo json_encode($message);exit;
						}
					}
					
					// echo "======schedule Job with previous schdule job=======<br>";
					// echo '<br>current schedule jobtime->';print_r($current_schedule_jobtime);echo '<br>';
					// // echo "current job accept time - ";print_r($AcceptTime);echo'<br>';
					// echo "previous schedule job time->";print_r($previous_schedulejob);echo'<br>';

					if(!empty($current_schedule_jobtime) && !empty($previous_schedulejob)){
						$oldTime = strtotime($current_schedule_jobtime);
						$newtime = strtotime($previous_schedulejob);
						$scheduled_difference_minutes= round(abs($oldTime - $newtime) / 60,2);//. " minute";exit;					
						// echo "Schdule Job Time Diffrence with Previous Job Accepted Time - ".$scheduled_difference_minutes;echo'<br>';//exit;
						if($scheduled_difference_minutes <= 30){
							// echo "second one";							
							$message['success'] = 'false';							
							// $message['message'] =  'Sorry,you have already accepted one scheduled job with same time.';
							$message['message'] =  'Sorry,you have already one job accepted.(S)';
							echo json_encode($message);exit;
						}
					}
					
					/*Normal Job*/
					// echo "======Normal Job=======<br>";
					// echo "current job accept time - ";print_r($AcceptTime);echo'<br>';
					// echo "previous job accepted time -";print_r($previous_acceptjobtime);echo'<br>';
					// echo "previous schedule job time -> ";print_r($previous_schedulejob);echo'<br>';

					if(empty($current_schedule_jobtime) && !empty($previous_acceptjobtime) && $previous_schedulejob==""){
						// echo "Normal Job Condition true <br>";						
						$oldTime = strtotime($previous_acceptjobtime);
						$newtime = strtotime($AcceptTime);
						$difference_minutes= round(abs($oldTime - $newtime) / 60,2);//. "minute 

						// echo 'Normal time diffrent->'.$difference_minutes;echo'<br>';//exit;

						// if($difference_minutes <= 30){
							// echo "third one";
							$message['success'] = 'false';
							$message['message'] =  'Sorry,you have already accepted one normal job.';
							echo json_encode($message);exit;
						// }
					
					}					
					//exit('stop....');
					
					// if(isset($scheduled_difference_minutes) && !empty($scheduled_difference_minutes) && !empty($current_schedule_jobtime)){
					// 	if($scheduled_difference_minutes <= 30){
					// 		echo "first one";
							
					// 		$message['success'] = 'false';							
					// 		$message['message'] =  'Sorry,you have already accepted one scheduled job.';
					// 		echo json_encode($message);exit;
					// 	}
					// }
					// if(isset($scheduled_difference_minutes) && !empty($scheduled_difference_minutes) && !isset($current_schedule_jobtime) && empty($current_schedule_jobtime)){
					// 	if($scheduled_difference_minutes <= 30){
					// 		echo "second one";
					// 		$message['success'] = 'false';							
					// 		$message['message'] =  'Sorry,you have already accepted one normal job.';
					// 		echo json_encode($message);exit;
					// 	}
					// }
					
					// if(empty($current_schedule_jobtime)){	
					// 	if($difference_minutes <= 30){
					// 		echo "third one";
					// 		$message['success'] = 'false';
					// 		$message['message'] =  'Sorry,you have already accepted one normal job.';
					// 		echo json_encode($message);exit;
					// 	}
					// }					
					//exit;
				}

				$chk_AcceptedDriverId = $this->job_model->empty_col_ckeck($JobId);
					 //echo "<pre>";print_r($chk_AcceptedDriverId);exit();
					//$JobStatus = $chk_AcceptedDriverId[0]->JobStatus;
					//print_r($chk_AcceptedDriverId);exit;
				if(!empty($chk_AcceptedDriverId))
				{	
			
						$get_all_jobdata = $this->job_model->get_all_jobdata($JobId);
						// print_r($get_all_jobdata);exit();
					
						$JobId = $get_all_jobdata[0]->JobId;
						$CompanyId = $get_all_jobdata[0]->CompanyId;
						$UserId = $get_all_jobdata[0]->UserId;
						$ScheduleJobTime = $get_all_jobdata[0]->ScheduleJobTime;
						$pickupDetail = $get_all_jobdata[0]->PickupDetail;
						$dropoffDetail = $get_all_jobdata[0]->DropoffDetail;
						$Distance = $get_all_jobdata[0]->Distance;
						$Duration = $get_all_jobdata[0]->Duration;
						$DistanceStatus = $get_all_jobdata[0]->DistanceStatus;
						$Consignment = $get_all_jobdata[0]->Consignment;
						$RejectDriverId = $get_all_jobdata[0]->RejectDriverId;
						$CancelDriverId = $get_all_jobdata[0]->CancelDriverId;
						$CreatedAt = $get_all_jobdata[0]->CreatedAt;
						$CreatedBy = $get_all_jobdata[0]->CreatedBy;
						$CreatedVia = 'RESTAPI';
						$TimeZone = $get_all_jobdata[0]->TimeZone;

						$update_array_json = array(
													'DriverUserId' =>$DriverUserId,
													'JobId' =>$JobId,
													'AcceptedTime' =>$AcceptTime
												  );
						$update_currjob_data = json_encode($update_array_json);
							
								
											
						$insert_aray = array (
												'JobId' =>$JobId,
												'CompanyId' =>$CompanyId,
												'UserId' =>$UserId,
												'pickupDetail' =>$pickupDetail,
												'dropoffDetail' =>$dropoffDetail,
												'Distance' =>$Distance,
												'Duration' =>$Duration,
												'DistanceStatus' =>$DistanceStatus,
												'Consignment' =>$Consignment,
												'RejectDriverId' =>$RejectDriverId,
												'CancelDriverId' =>$CancelDriverId,
												'AcceptedDriverId' =>$update_currjob_data,
												'DriverId' =>$DriverUserId,
												'JobStatus' =>3,
												//'CreatedAt' =>$CreatedAt,
												'CreatedAt' =>$currentdt,
												'CreatedBy' =>$CreatedBy,
												'CreatedVia' =>$CreatedVia,
												'TimeZone' =>$TimeZone,
												'Enable' =>1,
											);
									
						if(isset($ScheduleJobTime) && !empty($ScheduleJobTime))
					    {
					       $insert_aray['ScheduleJobTime'] = $ScheduleJobTime;      
					    }		

						$acceptedjob_result = $this->job_model->insert_jobmaster_by_jobid($JobId,$insert_aray);
						
						/*Start Get Result of AcceptedJob Details*/		
						// $acceptedjob_result = $this->job_model->get_jobmaster_data($acceptedjob_result);
						// /*End Get Result of AcceptedJob Details*/		
						
						// $acceptedjob_result[0]->PickupDetail = json_decode($acceptedjob_result[0]->PickupDetail);
						// $acceptedjob_result[0]->DropoffDetail = json_decode($acceptedjob_result[0]->DropoffDetail);
						// $acceptedjob_result[0]->Distance = json_decode($acceptedjob_result[0]->Distance);
						// $acceptedjob_result[0]->Duration = json_decode($acceptedjob_result[0]->Duration);
						// $acceptedjob_result[0]->AcceptedDriverId = json_decode($acceptedjob_result[0]->AcceptedDriverId);
						$get_jobmaster_details = $this->job_model->get_job_data($acceptedjob_result);
							// $booking['details'] = $get_jobmaster_details;
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
							$arr_json['success'] = true; 
							$arr_json['message'] = "Job Accepted Succesfully.";
							$arr_json['booking'] = $booking;

						// $arr_json['success'] = "1";
						// $arr_json['AcceptedJobDetails'] = $acceptedjob_result;
						// $arr_json['message'] = "Job Accepted Succesfully.";
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
		
		}

			$request_parameter['Parameteres']['Header'] =  array('Content-Type'=>$header['Content-Type'],'Apikey'=>$header['Apikey']);				
			$request_parameter['Parameteres']['Body'] =  array($request_data);
			$apirequest = json_encode($request_parameter);
			
			$Request_Type = "Accept Job";
   			$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
   			
			echo json_encode($arr_json);
	}

	/*** Cancel Job API ***********/
	public function Canceljob()
    {		

    	/*
    		Header
    		Content-Type:application/json
			Apikey:SST100000000009		
			Body				
			{			    
			    "jobId":"SSTVJOB10001",
			    "cancellationNotes" : "Customer left town in a hurry"
			}   		
		*/
		$request_data = json_decode(file_get_contents('php://input'),true);
		$header =getallheaders();

		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);
		//print_r($header);exit;
		/****** Check the header and request type and body @v *******/
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
		
		if(!$this->isJson($request_data)){
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

			// $request_data = json_decode(file_get_contents('php://input'),true);
			// // print_r($request_data);exit;
			// if(!$this->isJson($request_data)){
			// 		$error = json_last_error_msg();
			// 		$message['success'] = false; 
			// 		$message['message'] =  'Not valid JSON string ($error)';
			// 		echo json_encode($message); exit;
			// 		//echo "Not valid JSON string ($error)";exit;
			// }	
			
			if(empty($header['Apikey'])){
					$message['success'] = false; 					
					$message['message'] =  'Please enter Apikey as Label in Header';
					echo json_encode($message); exit;
			}
			else{
					$token =$header['Apikey'];	
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
				$message['message'] =  'Invalid API token, Please Check Apikey.';
				// $message['data'] = $token_result;
				echo json_encode($message); exit;	
			}
			else{
				$DriverUserId = $token_result['data'][0]->UserId;
			}
			/*** end verify token ***/

			if(isset($request_data['jobId']) && !empty($request_data['jobId']) && !empty($DriverUserId)){

					$jobId = $this->security->xss_clean($request_data['jobId']);
					//SELECT * FROM `jobmaster` where JobId='SSTVJOB10043' AND DriverId=21 AND JobStatus=3 ORDER BY `Id` DESC 
					$JobQuery="SELECT * FROM `jobmaster` WHERE JobId='".$jobId."' AND DriverId=$DriverUserId AND JobStatus=3 ORDER BY Id DESC LIMIT 0,1";

					$execute=$this->db->query($JobQuery);
					 // echo $this->db->last_query();exit;
					$JobResult=$execute->result(); 
					// print_r($JobResult);exit;
					/****** check job accepted by driver or not ************/
					if(!empty($JobResult)){						
						//print_r($JobResult);exit;
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
							
							$booking['CancelDetails'] = json_decode($get_jobmaster_details[0]->CancelDriverId);

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
							$booking['Compnay']['CompanyToken'] = $get_jobmaster_details[0]->CompanyToken;
							$booking['Compnay']['NotificationMethod'] = $get_jobmaster_details[0]->NotificationMethod;

							// $booking['CustomeName'] = $get_jobmaster_details[0]->UserName;
							
							$booking['Customer']['CustomerId'] = $get_jobmaster_details[0]->CustomerId;
							$booking['Customer']['CustomerName'] = $get_jobmaster_details[0]->CustomerName;
							$booking['Customer']['CustomerEmail'] = $get_jobmaster_details[0]->CustomerEmail;
							
							$booking['lastUpdated'] = $get_jobmaster_details[0]->UpdatedAt; 
							
							$booking['Enable'] = $get_jobmaster_details[0]->Enable; 

							
							
							// echo json_encode($booking);

							/******** Completed the Cancel job process and start the new job create with same details with new jobId and status to created @v*************/

								
							$GenerateJobId="";				
							$JobQuery="SELECT sequencenumber FROM sequencemaster ORDER BY Id DESC LIMIT 0,1";
							$execute=$this->db->query($JobQuery);
							$JobResult=$execute->result(); 
							$JobId='';
							foreach($JobResult as $JobResultValue){
								$JobId=$JobResultValue->sequencenumber;
							}
							//$exp = explode("SSTVJOB",$JobId);
							//$exp = $JobId;
							$exp_job='';
							if(isset($JobId)){
							 //$exp[1] = null;	
							 $exp_job = $JobId+1;
							}
							$JobId1 = $exp_job;
							if($JobId1 < 100000000000){
								$JobNumberValues=100000000001;
								$GenerateJobId= "SSTVJOB".$JobNumberValues;
							}
							else{
							$exp = explode("SSTVJOB",$JobId);
							$exp_job = $JobId+1;
							$JobNumberValues=$exp_job;
							$GenerateJobId= "SSTVJOB".$JobNumberValues;
							}
							
							$sequenceNumberRow=array(
							   "sequencenumber"=>$JobNumberValues
							); 
							$this->db->insert('sequencemaster',$sequenceNumberRow);

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
								'CreatedBy'=>$get_all_jobdata[0]->CompanyId,
								'TimeZone'=>$get_all_jobdata[0]->TimeZone
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
							$message['message'] =  'Sorry, No accepted job result found.';
							// echo json_encode($message); 
					}
					
			}
			else{
				$message['success'] = false; 
				$message['message'] = "Invalid JobId or request";
			}

			$requestby = $token;
			$request_parameter['Parameteres']['Header'] =  array('Content-Type'=>$header['Content-Type'],'Apikey'=>$header['Apikey']);				
			$request_parameter['Parameteres']['Body'] =  array($request_data);
			$apirequest = json_encode($request_parameter);
			$Request_Type = "Cancel Job";
   			$this->general->api_logs($requestby,$apirequest,json_encode($message),$message['success'],$starttime,$Request_Type);

			echo json_encode($message);exit;
			
    }

	/*** Start Job API ***********/
	public function Startjob()
	{
		   /*
		   Header
		   Content-Type:application/json
		   Apikey:SST100000000011 
		     
		   Body
		   {
		    "JobId":"SSTVJOB10011",
		    "JobStartTime":"2018-05-29 11:47:20",
		    "latitude":"1.50",
		    "longitude":"4.55"
		   }  
		  */
		  
		    $request_data = json_decode(file_get_contents('php://input'),true);
		    $header =getallheaders();
		    $currentdt= date('Y-m-d h:i:s');
			$starttime = new DateTime($currentdt);
		     //echo "<pre>";print_r($header);exit;
		     /**** Check the header and request type and body @v *****/
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
					$token =$header['Apikey'];	
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
    
	  		/* $JobId = $this->input->post('JobId');
			 $DriverUserId = $this->input->post('DriverUserId');
			 $JobStartTime = $this->input->post('JobStartTime');
			 $latitude = $this->input->post('latitude');
			 $longitude = $this->input->post('longitude');
			 $Token = $this->input->post('Token');
			 $type = "driver";
			*/
    
		    $Token = $header['Apikey'];
			$JobId = $request_data['JobId'];
			$JobStartTime = $request_data['JobStartTime'];
			$latitude = $request_data['latitude'];
			$longitude = $request_data['longitude'];
			$type = "driver";
		
		
			/*start verify the apitoken with company token weather exist in db or not*/
			$token_result = $this->general_model->verify_token($Token,$type);   
			//print_r($token_result);exit;
			if($token_result['result']==0)
			{
			   $message['success'] = false; 
			   $message['message'] =  'Invalid API token, Please Check Apikey.';   
			   echo json_encode($message); exit; 
			}
			else{
				$DriverUserId = $token_result['data'][0]->UserId;
			}
			/*end verify token */

				

			$startjob_result = $this->job_model->find_startjobexist($DriverUserId);   
			if(!empty($startjob_result)){
			   $message['success'] = false; 
			      $message['message'] =  'Job already started.';   
			      echo json_encode($message); exit; 
			}

			if(!empty($JobId) && !empty($DriverUserId) && !empty($JobStartTime) && !empty($latitude) && !empty($longitude) && !empty($Token))
			{
					$jobresult = $this->job_model->jobexistcheck($JobId);
					//print_r($jobresult);exit;
					
					if(!empty($jobresult))
					{		
							$CompanyId = $jobresult[0]->CompanyId;
							if(isset($JobStartTime)){
									if($this->validateDatetime($JobStartTime))
									{	
													$getTimeZone = $this->general->getCompanyTimeZonebyId($CompanyId);
													//print_r($getTimeZone);//exit;
													$currentdt="";
													if($getTimeZone['success']==1){
														$CompanyTimezone = $getTimeZone['TimeZone'][0];
														//echo $CompanyTimezone;
														$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
													}
													//echo $currentdt;exit;

													/****** 60 second buffer *******/
													$add_time=strtotime($currentdt)-60;
													$currentdtn= date('Y-m-d H:i:s',$add_time);

													$dtCurrent = new DateTime($currentdtn);
													$dtAccept = new DateTime($JobStartTime);

													if ( $dtCurrent > $dtAccept) {
														$message['success'] = false; 
														$message['message'] = 'Please add future time to Start job, Past time not allowed.';
														echo json_encode($message); exit;	
													}							
									}
									else
									{
										$message['success'] = false; 
										$message['message'] = 'Invalid Time format, Please Check JobStartTime.';
										echo json_encode($message); exit;	
									}						
							}
							else{
								$message['success'] = false; 
								$message['message'] = 'Empty JobStartTime, Please Check JobStartTime.';
								echo json_encode($message); exit;
							}


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
													//'CreatedAt' =>$jobresult[0]->CreatedAt,
													'CreatedAt' =>$currentdt,
													'CreatedVia' =>$jobresult[0]->CreatedVia,
													'CreatedBy' =>$jobresult[0]->CreatedBy,
													'DeletedBy' =>$jobresult[0]->DeletedBy,
													'TimeZone' =>$jobresult[0]->TimeZone,
													'JobStatus' =>4,
													'Enable' =>1
												);
						/*  $insert_tracking_array = array(
													'JobId' =>$JobId,
													'DriverId' =>$DriverUserId,
													'PickupDetail' =>$jobresult[0]->PickupDetail,
													'DropoffDetail' =>$jobresult[0]->DropoffDetail,
													'CurrentLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
													'UpdatedLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
													'StartTime' =>$JobStartTime
													); */
							$insert_tracking_array = array(
										            'JobId' =>$JobId,
										            'DriverId' =>$DriverUserId,
										            'UserId' =>$jobresult[0]->UserId,
										            'PickupDetail' =>$jobresult[0]->PickupDetail,
										            'DropoffDetail' =>$jobresult[0]->DropoffDetail,
										            'StartLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
										            'CurrentLocation' =>json_encode(array('latitude'=>$latitude,'longitude'=>$longitude)),
										            'StartTime' =>$JobStartTime,
										            'CreatedAt' =>$currentdt
				            );
							
							/*start Insert Data*/	
							$tracking_result = $this->job_model->insert_tracking_detail($insert_tracking_array);
							$inprogressjob_result = $this->job_model->insert_progressjob_detail($insert_inprogress_aray);
							/*End Insert Data*/		
							
							/*Start Get Data from jobmaster and tracking through the last_id*/	
							//$get_progressjob_result = $this->job_model->get_jobmaster_data($inprogressjob_result);
							// $get_progressjob_result[0]->PickupDetail = json_decode($get_progressjob_result[0]->PickupDetail);
							// $get_progressjob_result[0]->DropoffDetail = json_decode($get_progressjob_result[0]->DropoffDetail);
							// $get_progressjob_result[0]->Distance = json_decode($get_progressjob_result[0]->Distance);
							// $get_progressjob_result[0]->Duration = json_decode($get_progressjob_result[0]->Duration);
							// $get_progressjob_result[0]->AcceptedDriverId = json_decode($get_progressjob_result[0]->AcceptedDriverId);
							$get_jobmaster_details = $this->job_model->get_job_data($inprogressjob_result);
							// $booking['details'] = $get_jobmaster_details;
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

							/***** Tracking details ***********/
							
							$get_tracking_result = $this->job_model->get_tracking_data($tracking_result);
							$get_tracking_result[0]->PickupDetail = json_decode($get_tracking_result[0]->PickupDetail);
							$get_tracking_result[0]->DropoffDetail = json_decode($get_tracking_result[0]->DropoffDetail);
							$get_tracking_result[0]->StartLocation = json_decode($get_tracking_result[0]->StartLocation);
							$get_tracking_result[0]->CurrentLocation = json_decode($get_tracking_result[0]->CurrentLocation);
							
							/*End GetData*/		
							
							// $arr_json['ProgressJobDetails'] = $get_progressjob_result;
							$arr_json['message'] = "Job Started Succesfully.";
							$arr_json['success'] = "1";
							$arr_json['ProgressJobDetails'] = $booking;
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

			$requestby = $token;
			$request_parameter['Parameteres']['Header'] =  array('Content-Type'=>$header['Content-Type'],'Apikey'=>$header['Apikey']);				
			$request_parameter['Parameteres']['Body'] =  array($request_data);
			$apirequest = json_encode($request_parameter);
			$Request_Type = "Start Job";
   			$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);

			echo json_encode($arr_json);
	}
	
	/*** Complete Job API ***********/	
	public function CompleteJob()
	{
		/*Request for this Api Parameters
		
		Header
		Apikey:SST100000000019
		Form-data
		jobid:SSTVJOB10001
		notes:this is my test data
		Signature:upload image
		
		*/
		
		$header =getallheaders();
		if(empty($header['Apikey']))
		{
			$message['success'] = false; 
			$message['message'] =  'Missing Apikey in header.';
			echo json_encode($message); exit;
		}
		else{
			  $token = $header['Apikey'];
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
		
		$length = strlen($notes);
		if($length>=255){
			$arr_json['success'] = false;
			$arr_json['message'] = "Notes not allow more than 255 character.";
			echo json_encode($arr_json);exit;
		}

		if(!isset($_FILES['Signature']))
		{
			$arr_json['success'] = false;
			$arr_json['message'] = "Please Add Signature Image.";
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
				$profile_name = $profile_target_path.$_FILES['Signature']['name'].$JobId;
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
				'CustomerId'=>$UserId
				//'Rated'=>$Rated,
				//'ReviewDetails'=>$ReviewDetails
				);

				$tracking_array = array(
									'EndTime'=>date('Y-m-d H:i:s'),		
									'UpdatedAt'=>date('Y-m-d H:i:s')				
									);

				//$completejob_result = $this->general_model->insert_completejob_data($completejob_array);
				$completejob_result = $this->job_model->insert_currentjob_data($completejob_array);

				$tracking_endtime_updated = $this->job_model->update_trackingdetails($JobId,$tracking_array);
				
				if($completejob_result!=0){
					//$get_completejob_data = $this->general_model->Get_completejob_data($completejob_result);
					// $get_completejob_data = $this->job_model->get_jobmaster_data($completejob_result);

					$get_jobmaster_details = $this->job_model->get_job_data($completejob_result);							

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
					if(!empty($get_jobmaster_details)){
						// $jsondecode=json_decode($get_completejob_data[0]->PickupDetail);
						// $get_completejob_data[0]->PickupDetail = $jsondecode;
						// $jsondecode=json_decode($get_completejob_data[0]->DropoffDetail);
						// $get_completejob_data[0]->DropoffDetail = $jsondecode;
						// $jsondecode=json_decode($get_completejob_data[0]->Distance);
						// $get_completejob_data[0]->Distance = $jsondecode;
						// $jsondecode=json_decode($get_completejob_data[0]->Duration);
						// $get_completejob_data[0]->Duration = $jsondecode;
						// $jsondecode=json_decode($get_completejob_data[0]->DistanceStatus);
						// $get_completejob_data[0]->DistanceStatus = $jsondecode;
						// $jsondecode=json_decode($get_completejob_data[0]->AcceptedDriverId);
						// $get_completejob_data[0]->AcceptedDriverId = $jsondecode;
						
						$arr_json['success'] = true;
						$arr_json['message'] = "Job completed successfully.";
						$arr_json['completejob'] = $booking;
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
						$arr_json['review'] = $get_review_data;
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
				
				echo json_encode($arr_json);exit;
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
		// echo json_encode($arr_json);
	}

	/********** get job details by job id api ***************/
    public function Details()
    {		
    		//print_r($_POST);exit;
    		/*
    		Header
    		Content-Type:application/json
			Apikey:SST100000000003		
			Body
			{
				 "jobId": "SSTVJOB10029"        		
			}			
			*/

    		$request_data = json_decode(file_get_contents('php://input'),true);
			$header =getallheaders();
			//print_r($header);exit;
			/****** Check the header and request type and body @v *******/
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
			
			if(!$this->isJson($request_data)){
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
		
			if(empty($request_data['JobId'])){
					$message['success'] = false; 					
					$message['message'] =  'Required JobId, please check header with JobId field.';
					echo json_encode($message); exit;
			}	
			//$Apikey = $this->input->post('Apikey');// driver api token for access job details
			//$jobId = $this->input->post('jobId'); //  job id for get job details		

			$Apikey = $header['Apikey'];// driver api token for access job details
			$jobId = $request_data['JobId']; //  job id for get job details	
			
			
			if(empty($Apikey)){
					$message['success'] = false; 					
					$message['message'] =  'Required Apikey';
					echo json_encode($message); exit;
			}	

					
			$token= $Apikey;
			/*** start verify the apitoken with company token weather exist in db or not ***/
			$token_result = $this->general_model->verify_token($token,"driver");
			// echo $token_result['result'];
			// print_r($token_result);exit;			

			if($token_result['result']==0){
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check Apikey.';
				//$message['data'] = $token_result;
				echo json_encode($message); exit;	
			}
			/*** end verify token ***/
			
			if(isset($jobId) && !empty($jobId)){
					//$JobResult=$this->job_model->get_job_data($jobId);
					$get_jobmaster_details = $this->job_model->get_jobmaster_byjobId($jobId);
					if(!empty($get_jobmaster_details)){
							
							
							// $booking['details'] = $get_jobmaster_details;
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
							$booking['Compnay']['CompanyToken'] = base_url().$get_jobmaster_details[0]->CompanyToken;
							$booking['Compnay']['NotificationMethod'] = $get_jobmaster_details[0]->NotificationMethod;

							// $booking['CustomeName'] = $get_jobmaster_details[0]->UserName;
							
							$booking['Customer']['CustomerId'] = $get_jobmaster_details[0]->CustomerId;
							$booking['Customer']['CustomerName'] = $get_jobmaster_details[0]->CustomerName;
							$booking['Customer']['CustomerEmail'] = $get_jobmaster_details[0]->CustomerEmail;
							
							$booking['lastUpdated'] = $get_jobmaster_details[0]->UpdatedAt; 
							
							$booking['Enable'] = $get_jobmaster_details[0]->Enable; 
							
							$message['success'] = true; 
							$message['message'] =  'Job Data Found.';
							$message['data'] = $booking;
							// echo json_encode($message);exit;

					}
					else{					
						
							$message['success'] = false; 
							$message['message'] =  'Error:Zero Result Found.';
							//echo json_encode($message); exit;
					}
					
			}
			else{
				$message['success'] = false; 
				$message['message'] =  'Invalid JobId or request.';
				//echo "Invalid JobId or request";
			}
		
			echo json_encode($message);exit;
    }

	/*** List Cover Job API ***********/
    public function list_coverjob()
	{
		  /*
		   Content-Type:application/json
		   Apikey:SST100000000011
		  */
  
		  $header =getallheaders();
		  /***** Check the header and request type @kk ******/
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
		  /***** END *****/
  
  		 	$Apikey = $header['Apikey'];

			//$Apikey = $this->input->post('Apikey');// driver api token for access job details

			if(empty($Apikey)){
						$message['success'] = false; 					
						$message['message'] =  'Required Apikey';
						echo json_encode($message); exit;
			}
			
			$token= $Apikey;

		/*** start verify the apitoken with company token weather exist in db or not @v ***/
		$token_result = $this->general_model->verify_token($token,"driver");			

		if($token_result['result']==0){
			$message['success'] = false; 
			$message['message'] =  'Invalid API token, Please Check Apikey.';			
			echo json_encode($message); exit;	
		}
		/*** end verify token @v ***/
		
		$coverjob_listing = $this->job_model->coverjob_List();
		//$pickupDetail = $coverjob_listing[0]->pickupDetail;
		//$pickupDetail = json_decode($pickupDetail);
		/********* remove slaesh from data *********/
		foreach ($coverjob_listing as $cjob) {
			$cjob->PickupDetail = json_decode($cjob->PickupDetail);
			$cjob->DropoffDetail = json_decode($cjob->DropoffDetail);
			$cjob->Distance = json_decode($cjob->Distance);
			$cjob->Duration = json_decode($cjob->Duration);
			$cjob->DistanceStatus = json_decode($cjob->DistanceStatus);	
		}
		//echo "<pre>";print_r($coverjob_listing);exit();
		if(!empty($coverjob_listing))
		{
			$msgch ="CoverJobs list getting successfully.";
			$arr_json['success'] = "1";
			$arr_json['message'] = $msgch;
			$arr_json['CoverJobDetail'] = $coverjob_listing;
		}
		else
		{
			$msgch ="No coverJob Found.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}		

	


	/****** Validate json *********/
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}

	/*************** get customer details ******************/
	public function get_customer_details($Access_Key){
		if(empty($Access_Key)){
			$Access_Key = '';
		}
		$query = $this->db->query("select * from customers where apiToken LIKE'%".$Access_Key."%' ");
		$customer_result = $query->result();
		return $customer_result;
	}
	
	/*************** Create Customer if not exist ******************/
	public function create_customer($email,$password){
		
		$explode = explode('@',$email);
		$name = $explode[0];
		//$password = $this->general_model->random_password(8);
		// $token = $this->general_model->getToken(15);

		/** token generate @v **/
		$token = $this->general->VersatileAccessToken("SST","customer");
		/** end token @v **/
		$roleid =4;
		$create_customer_arr = array(
			'Name'=>$name,
			'Email'=>$email,
			'Password'=> md5($password),
			'RoleId'=>$roleid,
			'Token'=>$token			
		);
		$customer_result = $this->usermaster_model->insert_customer($create_customer_arr);
		$insert_customer_data = array('UserId'=>$customer_result);
  		$insert_customer_data = $this->customermaster_model->insert_customer_data($insert_customer_data);
		return $customer_result;
	}
	
	/************ Get Last Inserted Customer Details from Usermaster Table ***********/
	public function get_customer_data($last_id){
		$customer_result = $this->usermaster_model->get_customer_details($last_id);
		if(!empty($customer_result)){
			return $customer_result;
		}
	}
   
	public function validateDateTime($dateStr, $format="Y-m-d H:i:s")
	{
	    date_default_timezone_set('UTC');
	    $date = DateTime::createFromFormat($format, $dateStr);
	    return $date && ($date->format($format) === $dateStr);
	}

	
}
