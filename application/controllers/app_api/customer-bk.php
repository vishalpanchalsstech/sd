<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class customer extends CI_Controller {

    function __construct()
    {
       parent::__construct();
		$this->load->library('session');
		$this->load->model('login_model');
		$this->load->model('usermaster_model');
		$this->load->model('customermaster_model');
		$this->load->model('general_model');		
        $this->load->model('job_model');

		$general = $this->load->library('../controllers/general');
    }

    /*********** Customer Registration API ***************/
    public function registration()
	{
		
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');		
		$RoleId = 4;
	
		
		if(!empty($name) && !empty($email) && !empty($password))
		{	///echo "yes";exit;
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  	$emailErr = "Invalid email format"; 
			  	$arr_json['success'] = "0";
				$arr_json['message'] = $emailErr;
				echo json_encode($arr_json);exit;
			}
			$result = $this->general_model->emailvalidate($email,$RoleId);
			if( $result > 0)
			{	
				$arr_json['success'] = "0";
				$arr_json['message'] = "Email already exist. Please use different email.";
			}
			else
			{
				//$token = $this->general_model->getToken(15);   
				//$user_token = $this->general_model->getToken(15);   
				$token = $this->general->VersatileAccessToken();
				$user_token = $this->general->VersatileAccessToken("SST","driver");
				
				
		
				/*if email not exist then create customer*/
				
				$create_customer = $this->create_customer($name,$email,$password);				
				$get_last_customer_details = $this->get_customer_data($create_customer); 
				$email = $get_last_customer_details[0]->Email;				
				if($create_customer!=0){
					$customername = $name;					
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
										<td><label type='text'  style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;'  name='password'>$password</label></td>
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
						$arr_json['EmailStatus'] = 'Email Sent Succesfully.';
					}
					$msgch ="Customer Added Succesfully!";
					$arr_json['customer_details']= $get_last_customer_details;
					$arr_json['message'] = $msgch;
					$arr_json['success'] = "1";
				}	
				else
				{
						$msgch ="Email Already in Use,Please Use another email.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
				}
			}	
			
		}
		else
		{
			$msgch ="All fields are required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
			
		}
		
		echo json_encode($arr_json);		

	}
 	
 	/*********** Customer login API ************/
    function login()
    {
       $email = $this->input->post('email');
       $password = $this->input->post('password');
      // $roleid = $this->input->post('roleid');
       $roleid = 4;
		if(!empty($email) && !empty($password) && !empty($roleid) && is_numeric($roleid))
		{
			$result= $this->login_model->login_validate_customer($email,$password,$roleid);
		
			if($result['result']!=1)
			{
				$msgch ="Invalid email and/or password.";
				$arr_json['success'] = "0";
				$arr_json['msg'] = $msgch;
			}
			else
			{
				$data = $result['data'];
				$msgch = "Login successful";
				$success = array('success'=>'1','msg'=>$msgch);
				$arr_json = array_merge($success,$data);
			}
	    }
		else
		{
			$msgch ="Something gone wrong. Please Try again.";
			$arr_json['success'] = "0";
            $arr_json['msg'] = $msgch;
		}	
		
		echo json_encode($arr_json);
    }

    /********** Customer Chnage password **********/
    public function change_password()	
	{
			// print_r($_POST);exit;
			$currentdt= date('Y-m-d h:i:s');
			$starttime = new DateTime($currentdt);
			// $userid = $this->input->get('userid');
			$Apikey = $this->input->post('Apikey');
			$old_pwd= $this->input->post('old_pwd');
			$new_pwd= $this->input->post('new_pwd');
			$cnf_pwd= $this->input->post('cnf_pwd');
			$type = 'customer';
			// print_r($_POST);exit;
			if(!empty($Apikey) && !empty($old_pwd) && !empty($new_pwd) && !empty($cnf_pwd))
			{
				$data = $this->general_model->verify_token($Apikey,$type);
				// print_r($data);exit;
				$result = $data['result'];
				if($result == 0){
							$msgch ="Invalide Token.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
							echo json_encode($arr_json);exit;
				}else{
					$DriverUserId = $data['data'][0]->UserId;
				}
				$old = $this->general_model->old_password($old_pwd,$DriverUserId);
				//print_r($old);exit;
				$roleid = 4;
				if(!empty($old))
				{
					if ($new_pwd === $cnf_pwd) 
					{
					  	$change = $this->general_model->update_new_password($old_pwd,$new_pwd,$DriverUserId,$roleid);	
						$msgch = $change['msg'];			
						if($change == '0')
						{
							$msgch = 'Your Password  Was Wrong';
							$arr_json['success'] = 0;
							$arr_json['message'] = $msgch; 				
						}
						else
						{	
							$msgch ="Your password change successfully.";
							$arr_json['message'] = $msgch; 
							$arr_json['success'] = 1;
						}
					}
					else
					{
						$msgch ="Your New Password And Confirm Password Not Same. Please Try again.";
						$arr_json['message'] = $msgch;
						$arr_json['success'] = 0;
					}
				}
				else
				{
					$msgch ="Your old Password is wrong. Please Try again.";
					$arr_json['message'] = $msgch;
					$arr_json['success'] = 0;
				}	
			}
			else
			{
				$msgch ="All Fields Are Required.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
			//$this->general->api_logs($_GET,json_encode($arr_json),$arr_json['success'],$starttime);
			echo json_encode($arr_json);
	}

	/************ Customer jobhistory api ***********/
	public function jobhistory()
	{
		$Apikey = $this->input->post('Apikey');
		$type = "customer";
		
		/* start verify the apitoken with driver token weather exist in db or not */
			$token_result = $this->general_model->verify_token($Apikey,$type);
			 //print_r($token_result);exit;
			if($token_result['result']==0)
			{
			   $message['success'] = false; 
			   $message['message'] =  'Invalid API token, Please Check Apikey.';   
			   echo json_encode($message); exit; 
			}
		/***** End verify apitoken Code ****/
		
		$CustomerUserId = $token_result['data'][0]->UserId;
		if(!empty($Apikey))
		{ 
			$Customerlisting = $this->customermaster_model->Get_customer_jobhistory($CustomerUserId);
			$listing = $Customerlisting['result'];
			$Customer_result = $Customerlisting['data'];
			//echo "<pre>";print_r($Customer_result);exit;
			
			if($listing == 1)
			{
				//$status_array = array('Rejected','Canceled','Accepted','Inprogress','Completed');
				foreach($Customer_result as $data)
				{
				
					$data->AcceptedDriverId = json_decode($data->AcceptedDriverId);
					$data->CancelDriverId = json_decode($data->CancelDriverId);
					$data->PickupDetail = json_decode($data->PickupDetail);
					$data->DropoffDetail = json_decode($data->DropoffDetail);
					$data->Distance = json_decode($data->Distance);
					$data->Duration = json_decode($data->Duration);
					$data->DistanceStatus = json_decode($data->DistanceStatus);
					
					$status = $data->JobStatus;
					switch($status) 
					{						
						case 1:
						  $status_array['Created'][] = $data;
						  break;
						case 2:
						  $status_array['Canceled'][] = $data;
						  break;
						case 3:
						  $status_array['Accepted'][] = $data;
						  break;
						case 4:
						  $status_array['Inprogress'][] = $data;
						  break;
						case 5:
						  $status_array['Completed'][] = $data;
						  break;
					} 
				}
				//echo "<pre>";print_r($status_array);exit;
				$msgch ="Data Found.";
				$arr_json['success'] = "1";
				$arr_json['message'] = $msgch;
				$arr_json['RESULT'] = $status_array;
			}
			else
			{
				$msgch ="Data not Available.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="Plz Enter A valide API Key.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}

	/********** Display customer details by custome user token **************/
	public function details()
    {
            //$driver_id = $this->input->post('id');  
	       $token = $this->input->post('Apikey');
	       $type = 'customer';
	       if(!empty($token)){
	       $data = $this->general_model->verify_token($token,$type);
	       
	       $result = $data['result'];
		    if($result == 0){
		          $arr_json['success'] = "0";
		          $arr_json['message'] = "Invalide Token.";
		          echo json_encode($arr_json);exit;
		    }
	       	else
	       	{	
			        if(isset($data)){
			         //$driver_id = $data['data'][0]->Id;
			         //$driver_result= $this->drivermaster_model->get_driver_details($driver_id);
			         $driver_result = $data;
				         if(empty($driver_result))
				         {
				          $arr_json['success'] = "False";
				          $arr_json['message'] = "No Records found.";
				          echo json_encode($arr_json);exit;
				         }
				         else
				         {
				          //$msgch ="Profile Updated Success.";
				          $arr_json['success'] = "1";
				          $arr_json['message'] = "Customer details found.";        
				          $arr_json['result'] = $data['result'];
				          $arr_json['result'] = $data['data'];
				          echo json_encode($arr_json);exit;
				         }
			        }
	       	}
	     }
	     else{
	        $msgch = "Missing Apikey."; 
	        $arr_json['success'] = "0"; 
	        $arr_json['message'] =  $msgch; 
	        echo json_encode($arr_json);exit;
	     } 
   
    }


	/************ Update Customer Profile ******************/
	function Updateprofile()
	{
			
			$token = $this->input->post('Apikey');
			//$UserId=$this->input->post('UserId');
			$country=$this->input->post('Country');
			$building=$this->input->post('Building');
			$street=$this->input->post('Street');
			$suburb=$this->input->post('Suburb');
			$state=$this->input->post('State');
			$postcode=$this->input->post('Postcode');
			$phoneno=$this->input->post('Phoneno');
			//$ForgotEmailToken=$this->input->post('ForgotEmailToken');
			
			$type = 'customer';
			if(!empty($token)){
				$data = $this->general_model->verify_token($token,$type);
				$result = $data['result'];
				if($result == 0){
							$msgch ="Invalide Token.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
							echo json_encode($arr_json);exit;
				}
				else{
					if(isset($data)){
						//$UserId = $data['data'][0]->Id;
						$UserId = $data['data'][0]->UserId;
					}
				}
			}
			else{
					$msgch = "Missing Apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
			}
			/*if(!empty($country) && !empty($building) && !empty($street) && !empty($suburb) && !empty($state)&& !empty($postcode) && !empty($phoneno))
			{*/
				
				
				if(isset($postcode) && !empty($postcode)){	
				    if(strlen(trim($postcode)) > 6){
						 
						 $arr_json['success'] = "0";
						 $arr_json['message'] = "Please Enter 6 digits in postalcode.";
						 echo json_encode($arr_json);exit;
				    }
					if(is_numeric(trim($postcode)) == false) {
						 $arr_json['success'] = "0";
						 $arr_json['message'] = "Please enter only digits in postalcode.";
						 echo json_encode($arr_json);exit;
				    }
				    if(strlen(trim($postcode)) != 6 ){
						  $arr_json['success'] = "0";
						  $arr_json['message'] = "Please enter minimum 6 digits in postalcode.";
						  echo json_encode($arr_json);exit;
				    }
				 }   
					
					
				 if(isset($phoneno) && !empty($phoneno)){		
					    if(is_numeric(trim($phoneno)) == false) {
							 $arr_json['success'] = "0";
							 $arr_json['message'] = "Please enter only digits in phoneno.";
							 echo json_encode($arr_json);exit;
					    }
					   else if(strlen(trim($phoneno)) > 15){
							 $arr_json['success'] = "0";
							 $arr_json['message'] = "Please Enter 15 digits in phoneno.";
							 echo json_encode($arr_json);exit;
					   }
					   else if(strlen(trim($phoneno)) < 10){
							$arr_json['success'] = "0";
							$arr_json['message'] = "Please Enter 10 digits in phoneno.";
							echo json_encode($arr_json);exit;
					   }
				 }  
					
				  $customer_result = $this->customermaster_model->get_customer_details($UserId,$customerid='');
				  if(!empty($customer_result)){
					  $customerid = $customer_result[0]->Id;
					  
					 /* $custome_arr = array(
										'Country'=>$country,
										'Building'=>$building,
										'Street'=>$street,
										'Suburb'=>$suburb,
										'State'=>$state,
										'Postcode'=>$postcode,
										'Phoneno'=>$phoneno
									  );*/
					
						if(isset($building) && !empty($building)){
							$customer_update_array['Building']=$building;
						}
						if(isset($street) && !empty($street)){
							$customer_update_array['Street']=$street;
						}
						if(isset($country) && !empty($country)){
							$customer_update_array['Country']=$country;
						}
						if(isset($suburb) && !empty($suburb)){
							$customer_update_array['Suburb']=$suburb;
						}
						if(isset($state) && !empty($state)){
							$customer_update_array['State']=$state;
						}
						if(isset($postcode) && !empty($postcode)){
							$customer_update_array['Postcode']=$postcode;
						}
						if(isset($phoneno) && !empty($phoneno)){
							$customer_update_array['Phoneno']=$phoneno;
						}				  
					  
				  if(!empty($building) || !empty($street) || !empty($country) || !empty($suburb) || !empty($state) || !empty($postcode) || !empty($phoneno)){
					  
					  $update_customer_data = $this->customermaster_model->update_customer_details($customerid,$customer_update_array);
					  
  				  }	  
					  if(!empty($update_customer_data)){
						   $customer_result = $this->customermaster_model->get_customer_details($UserId='',$update_customer_data);
						    
						  $arr_json['success'] = 'true';
						  $arr_json['message'] = "Customer Profile Updated Successfully.";
						  $arr_json['result'] = $customer_result;
						  echo json_encode($arr_json);exit;
					  }
					  else{
						  $arr_json['success'] = 'false';
						  $arr_json['message'] = "Record Already Updated.";
						  echo json_encode($arr_json);exit;
					  }
					 
					  
					  
				  }
				  else{
						$arr_json['success'] = 'false';
						$arr_json['message'] = "Customer Profile Data Not Found.";
						echo json_encode($arr_json);exit;
				  }
					
			/*}	
			else{
						$arr_json['success'] = 'false';
						$arr_json['message'] = "All Field Are Required.";
						echo json_encode($arr_json);exit;
			}*/	
	}		
		
		
	/*** Start Customer jobTracking API @ Krushna @ 29/05/2018 ****/

	public function jobTracking()
	{
		$Apikey = $this->input->post('Apikey');
		$JobId = $this->input->post('JobId');
		$type = "customer";
		
		/**** start verify the apitoken with driver token weather exist in db or not ****/
			
			$token_result = $this->general_model->verify_token($Apikey,$type);
			if($token_result['result']==0)
			{
			   $message['success'] = false; 
			   $message['message'] =  'Invalid API token, Please Check Apikey.';   
			   echo json_encode($message); exit; 
			}
		
		/**** End verify apitoken Code ****/
		
		$CustomerUserId = $token_result['data'][0]->UserId;
		if(!empty($Apikey) && !empty($JobId))
		{
			$Cust_job_trck_list = $this->customermaster_model->Get_customer_jobTracking($CustomerUserId,$JobId);
			$List = $Cust_job_trck_list['result'];
			$Customer_result = $Cust_job_trck_list['data'];
			
			$Customer_result[0]->PickupDetail = json_decode($Customer_result[0]->PickupDetail);
			$Customer_result[0]->DropoffDetail = json_decode($Customer_result[0]->DropoffDetail);
			$Customer_result[0]->StartLocation = json_decode($Customer_result[0]->StartLocation);
			$Customer_result[0]->CurrentLocation = json_decode($Customer_result[0]->CurrentLocation);
			
			
			if($List ==1)
			{
				$MSG =  'Data Found.'; 
				$arr_json['success'] = "1";
				$arr_json['message'] = $MSG; 
				$arr_json['Result'] = $Customer_result; 
			}
			else
			{
				$MSG =  'Data not Available.'; 
				$arr_json['success'] = "0";
				$arr_json['message'] = $MSG; 
			}
		}
		else
		{
			$MSG ="All fields are required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $MSG;
		}
		echo json_encode($arr_json);
	}

	/*** End Customer jobTracking API @ Krushna @ ****/
	
	/******** Customer Job Review & Rating *********/
	public function jobRating()
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
		  if(empty($header['Apikey']))
		  {
			$message['success'] = false; 
			$message['message'] =  'Missing Apikey in header.';
			echo json_encode($message); exit;
		  }
		  else{
			  $customertoken = $header['Apikey'];
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

					$getjobdata = $this->job_model->jobexistcheck($jobid);	
					if(!empty($getjobdata)){
						$companyid = $getjobdata[0]->CompanyId;
						$getTimeZone = $this->general->getCompanyTimeZonebyId($companyid);
							//print_r($getTimeZone);//exit;
							$currentdt="";
							if($getTimeZone['success']==1){
								$CompanyTimezone = $getTimeZone['TimeZone'][0];
								//echo $CompanyTimezone;
								$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
							}
					}


					$review_arr = array(
								'Rated'=>$rated,
								'ReviewDetails'=>$reviewdetails,
								'UpdatedAt'=>$currentdt		
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
	
    /*Create Customer if not exist*/
	public function create_customer($name,$email,$password){
		// $token = $this->general_model->getToken(15);
		$token = $this->general->VersatileAccessToken("SST","customer");
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

	/*Get Last Inserted Customer Details from Usermaster Table*/
	public function get_customer_data($last_id){
		$customer_result = $this->usermaster_model->get_customer_details($last_id);
		if(!empty($customer_result)){
			return $customer_result;
		}
	}
     
  
}
    

?>