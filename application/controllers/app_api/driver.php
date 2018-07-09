<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('driverregister_model');
        $this->load->model('drivermaster_model');
        $this->load->model('editprofile_model');
        $this->load->library('upload');
	    $this->load->model('general_model');
	    $this->load->model('login_model');
	    $general = $this->load->library('../controllers/general');
    }

    /******** Driver Login API *************/
    function login()
    {
       $email = $this->input->post('email');
       $password = $this->input->post('password');
       // $roleid = $this->input->post('roleid');
       $roleid = 3;
	   
			$currentdt= date('Y-m-d h:i:s');
		    $starttime = new DateTime($currentdt);
			
		if(!empty($email) && !empty($password) && !empty($roleid) && is_numeric($roleid))
		{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			    $msgch ="Please enter valid email address.";
			    $arr_json['success'] = "0";
			    $arr_json['message'] = $msgch;
			    echo json_encode($arr_json);exit;
			}

			$result= $this->login_model->login_validate($email,$password,$roleid);
		
			if($result['result']!=1)
			{
				$msgch ="Invalid email and/or password or Unverified.";
				$arr_json['success'] = "0";
				$arr_json['msg'] = $msgch;
			}
			else
			{
				// $data = $result['data'];
				// $msgch = "Login successful";
				// $success = array('success'=>'1','msg'=>$msgch);
				// $arr_json = array_merge($success,$data);
				$arr_json['success'] = "1";
				$arr_json['message'] = "Driver Login Successfully"; 							
				$arr_json['result'] = $result['data'];
				
				$Token = $result['data']['Token'];
				$requestby = $Token;
				$request_parameter = $_POST;
				$apirequest = json_encode($request_parameter);
				$Request_Type = "Driver Login";
				$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
				//echo json_encode($arr_json); exit;
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

    /*********** Driver Registration API *********/
    public function registration()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$Password = $this->input->post('Password');
		$vehiclename = $this->input->post('vehiclename');
		$vehicleno = $this->input->post('vehicleno');
		$PhoneNo = $this->input->post('PhoneNo');
		$licenceNo = $this->input->post('licenceNo');
		$Building=$this->input->post('Building');
		$Street=$this->input->post('Street');
		$country=$this->input->post('country');
		$Suburb=$this->input->post('Suburb');
		$State=$this->input->post('State');
		$Postcode=$this->input->post('Postcode');
		$RoleId = 3;
		
		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);

		if(isset($_FILES['profile_image']) && isset($_FILES['document_image']))
		{
			
			if(!empty($name) && !empty($email) && !empty($Password) && !empty($PhoneNo) && !empty($licenceNo) && !empty($vehiclename) && !empty($vehicleno) && !empty($Building) && !empty($Street) && !empty($country) && !empty($Suburb) && !empty($State) && !empty($Postcode))
				{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			    $msgch ="Please enter valid email address.";
			    $arr_json['success'] = "0";
			    $arr_json['message'] = $msgch;
			    echo json_encode($arr_json);exit;
			      }

			    if(is_numeric(trim($PhoneNo)) == false) {
			     $msgch ="Please enter only digits in phoneno.";
			     $arr_json['success'] = "0";
			     $arr_json['message'] = $msgch;
			     echo json_encode($arr_json);exit;
			    }
			    else if(strlen(trim($PhoneNo)) > 15){
			     $msgch ="Please Enter 15 digits in phoneno.";
			     $arr_json['success'] = "0";
			     $arr_json['message'] = $msgch;
			     echo json_encode($arr_json);exit;
			    }
			    else if(strlen(trim($PhoneNo)) < 10){
			        $msgch ="Please Enter 10 digits in phoneno.";
				    $arr_json['success'] = "0";
				    $arr_json['message'] = $msgch;
				    echo json_encode($arr_json);exit;
			    }

			    if(strlen(trim($Postcode)) > 6){
				     $msgch ="Please enter maximum 6 digits in postalcode";
				     $arr_json['success'] = "0";
				     $arr_json['message'] = $msgch;
				     echo json_encode($arr_json);exit;
			    }
			    if(strlen(trim($Postcode)) != 6 ){
			      $msgch ="Please enter minimum 6 digits in postalcode";
			      $arr_json['success'] = "0";
			      $arr_json['message'] = $msgch;
			      echo json_encode($arr_json);exit;
			    }

			    if(is_numeric(trim($Postcode)) == false) {
			     $msgch ="Please enter only digits in postalcode.";
			     $arr_json['success'] = "0";
			     $arr_json['message'] = $msgch;
			     echo json_encode($arr_json);exit;
			     
			    }		      
			     
			    if(strlen(trim($Password)) < 6 ){
			      $msgch ="Please enter maximum 6 character in Password.";
			      $arr_json['success'] = "0";
			      $arr_json['message'] = $msgch;
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
					$token = $this->general->VersatileAccessToken();
					$user_token = $this->general->VersatileAccessToken("SST","driver");
					
					$to = $email;
					$from = '';		
					$cc = '';
					$subject = 'SSTech Driver Email Verify';
					$body = "<p>Hi $name ,</p><p>It's time to confirm your email address.</p><p>Have we got the right email address to reach you on? To confirm that you can get our emails, just click the button below.</p>";
					$url = base_url().'driverregister/verify?email='.$email.'&token='.$token;
					
					$body .= '<a style="background-color:#0070e0;border:1px solid #0070e0;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:220px" href="'.$url.'" target="_blank" >Confirm my email address</a>';
					
					$mail_sent = $this->general_model->mail_setup($to,$cc,$from,$subject,$body);
					
					if($mail_sent == '1')
					{
						$user_insert_array=array( 
													'Name'=>$name,
													'Email'=>$email,
													'Password'=>MD5($Password),
													'RoleId'=>$RoleId,
													'Token'=>$user_token,
													'Enable'=>'0',
												);
						//echo '<pre>';print_r($user_insert_array);
						$user_query = $this->driverregister_model->insert_user_data($user_insert_array);
						
						if($user_query)
						{
							$userId = $user_query;
							$profile_target_path = 'assets/uploads/images/driver/';
							$document_target_path = 'assets/uploads/images/document/';
							
							if(isset($_FILES['profile_image']['name']) && ($_FILES['document_image']['name']))
							{
								$ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
								$profile_name = $this->general_model->imagename($name);   
								   
								$target_path = $profile_target_path.$profile_name.".".$ext;
								$profile_target_path = $profile_name.".".$ext;
								move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_path);
							   
								$document_ext = pathinfo($_FILES['document_image']['name'], PATHINFO_EXTENSION);
								$document_name = $this->general_model->imagename($name);
								$target_path_document = $document_target_path.$document_name.".".$ext;
								$document_target_path = $document_name.".".$document_ext;
								move_uploaded_file($_FILES["document_image"]["tmp_name"], $target_path_document);
							}
							$driver_insert_array=array(
														'Phoneno'=>$PhoneNo,
														'Building'=>$Building,
														'Street'=>$Street,
														'Country'=>$country,
														'Suburb'=>$Suburb,
														'State'=>$State,
														'Postcode'=>$Postcode,
														'LicenceNo'=>$licenceNo,
														'ProfileImage'=>$target_path,
														'DocumentImage'=>$target_path_document,
														'EmailVerifyToken'=>$token,
														'UserId'=>$userId
													);
							
							$driver_query = $this->driverregister_model->insert_driver_data($driver_insert_array);
							if($driver_query)
							{
								$driver_Id = $driver_query;
								$vehicle_detail_array=array(
															'DriverId'=>$driver_Id,
															'VehicleType'=>$vehiclename,
															'VehicleNumber'=>$vehicleno
															);
								$driver_query = $this->driverregister_model->insert_vehicle_detail($vehicle_detail_array);
								$msgch ="Driver Registration Successfully, Verification Email Sent to Driver Email Id.";
								$arr_json['success'] = "1"; 
								$arr_json['message'] =  $msgch;
								
								$Token = $user_token;
								$requestby = $Token;
								$request_parameter = $_POST;
								$apirequest = json_encode($request_parameter);
								$Request_Type = "Driver Registration";
								$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
								//echo json_encode($arr_json);exit;
							}
							else
							{
								$msgch ="Something went wrong.";
								$arr_json['success'] = "0";
								$arr_json['message'] = $msgch;
							}
						}
					}
					else
					{
						$msgch ="Please Use another email.";
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
		}
		else
		{	
			if(empty($_FILES['profile_image']))
			{
			 $arr_json['success'] = false;
			 $arr_json['message'] = "Please insert ProfileImage.";
			 echo json_encode($arr_json);exit;
			}
			if(empty($_FILES['document_image']))
			{
			 $arr_json['success'] = false;
			 $arr_json['message'] = "Please insert DocumentImage.";
			 echo json_encode($arr_json);exit;
			}
		}
		echo json_encode($arr_json);
	}

	/************ Get Driver details by driverusertoken *************/
	function Getdriver_details()
    {
		//$driver_id = $this->input->post('id');  
		$token = $this->input->post('Apikey');
		$type = 'driver';
	   
		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);
	

	   if(!empty($token)){
				$data = $this->general_model->verify_token($token,$type);
				
				$result = $data['result'];
				if($result == 0){
							$arr_json['success'] = "0";
							$arr_json['message'] = "Invalide Token.";
							echo json_encode($arr_json);exit;
				}
				else{
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
							$arr_json['message'] = "Driver details found."; 							
							$arr_json['result'] = $data['result'];
							$arr_json['result'] = $data['data'];
							
							$requestby = $token;
							$request_parameter = $_POST;
							$apirequest = json_encode($request_parameter);
							$Request_Type = "Get Driver details";
							$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
							echo json_encode($arr_json);exit;
						}
					}
				}
		}
		else{
					$msgch = "Missing Apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
		}	
		
    }

    /********* Update Driver Profile API ***********/
	public function update_profile()
	{
			
		$Apikey = $this->input->post('Apikey');			
		$name=$this->input->post('name');
		$Building=$this->input->post('Building');
		$Street=$this->input->post('Street');
		$country=$this->input->post('country');
		$Suburb=$this->input->post('Suburb');
		$State=$this->input->post('State');
		$Postcode=$this->input->post('Postcode');
		$PhoneNo=$this->input->post('PhoneNo');
		$licenceNo=$this->input->post('licenceNo');
		
		$RoleId = 3;
		$timestamp = date('Y-m-d H:i:s');
		$user_update_array=array( 
		'Name'=>$name,
		//'Email'=>$email,
		//'Password'=>$ins_pass,
		//'RoleId'=>$RoleId,
		//'Token'=>$Token,
		'UpdatedAt'=>$timestamp
		);
		
		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);
		
			//echo "<pre>";print_r($_POST);exit();

			
				$Building=$this->input->post('Building');
				$Street=$this->input->post('Street');
				$country=$this->input->post('country');
				$Suburb=$this->input->post('Suburb');
				$State=$this->input->post('State');
				$Postcode=$this->input->post('Postcode');
				$PhoneNo=$this->input->post('PhoneNo');
				$licenceNo=$this->input->post('licenceNo');
				$RoleId = 3;
				$timestamp = date('Y-m-d H:i:s');
				$user_update_array=array( 
				'Name'=>$name,
				//'Email'=>$email,
				//'Password'=>$ins_pass,
				//'RoleId'=>$RoleId,
				//'Token'=>$Token,
				'UpdatedAt'=>$timestamp
				);

				$type = 'driver';
				if(!empty($Apikey)){
					
					$data = $this->general_model->verify_token($Apikey,$type);
					//print_r($data);exit;					
					$result = $data['result'];
					if($result == 0){
							$msgch ="Invalide Token.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
							echo json_encode($arr_json);exit;
					}
					$UserId = $data['data'][0]->UserId;
					$driver_Id = $data['data'][0]->Id;
				}
				else
				{
					$msgch = "Missing Apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
				}
				
				// if(!empty($name) && !empty($Password) && !empty($Building) && !empty($Street) && !empty($country) && !empty($Suburb) && !empty($State) && !empty($Postcode) && !empty($PhoneNo) && !empty($licenceNo))
				// {
			
				if(isset($Postcode) && !empty($Postcode)){		
					if(strlen(trim($Postcode)) > 6){
						 $msgch ="Please Enter 6 digits in postalcode.";
						 $arr_json['success'] = "0";
						 $arr_json['message'] = $msgch;
						 echo json_encode($arr_json);exit;
				   }			
				
				   if(is_numeric(trim($Postcode)) == false) {
						 $msgch ="Please enter only digits in postalcode.";
						 $arr_json['success'] = "0";
						 $arr_json['message'] = $msgch;
						 echo json_encode($arr_json);exit;
				   }			
				
				   if(strlen(trim($Postcode)) != 6 ){
						  $msgch ="Please enter minimum 6 digits in postalcode";
						  $arr_json['success'] = "0";
						  $arr_json['message'] = $msgch;
						  echo json_encode($arr_json);exit;
				   }
			    }

			    if(isset($PhoneNo) && !empty($PhoneNo)){		
				   if(is_numeric(trim($PhoneNo)) == false) {
						 $msgch ="Please enter only digits in phoneno.";
						 $arr_json['success'] = "0";
						 $arr_json['message'] = $msgch;
						 echo json_encode($arr_json);exit;
				   }
				   else if(strlen(trim($PhoneNo)) > 15){
						 $msgch ="Please Enter 15 digits in phoneno.";
						 $arr_json['success'] = "0";
						 $arr_json['message'] = $msgch;
						 echo json_encode($arr_json);exit;
				   }
				   else if(strlen(trim($PhoneNo)) < 10){
						$msgch ="Please Enter 10 digits in phoneno.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
						echo json_encode($arr_json);exit;
				   }
				}
				//print_r($_POST);exit;
				if(!empty($name)){
				$user_update_query = $this->drivermaster_model->update_user_data($user_update_array,$UserId);
				}
						
				$profile_target_path = 'assets/uploads/images/driver/';
				$document_target_path = 'assets/uploads/images/document/';
				
				$ProfileImage="";
				$DocumentImage="";
				if(!empty($_FILES['ProfileImage']))
				{
					
					$ext = pathinfo($_FILES['ProfileImage']['name'], PATHINFO_EXTENSION);
					$add= "." . $ext;
					$profile_name = $this->general_model->imagename($name);   
					$target_path = $profile_target_path . $profile_name . $add;
					
					if(move_uploaded_file($_FILES['ProfileImage']['tmp_name'], $target_path))
					{
						$ProfileImage = $target_path;
					}
				}
				if(!empty($_FILES['DocumentImage'])){
					
					$document_ext = pathinfo($_FILES['DocumentImage']['name'], PATHINFO_EXTENSION);
					$add= "." . $document_ext;
					$document_name = $this->general_model->imagename($name);   
					$target_path_document = $document_target_path . $document_name . $add;
					if(move_uploaded_file($_FILES['DocumentImage']['tmp_name'], $target_path_document))
					{
						$DocumentImage = $target_path_document;
					}
				}
				
				if(isset($Building) && !empty($Building)){
					$driver_update_array['Building']=$Building;
				}
				if(isset($Street) && !empty($Street)){
					$driver_update_array['Street']=$Street;
				}
				if(isset($country) && !empty($country)){
					$driver_update_array['Country']=$country;
				}
				if(isset($Suburb) && !empty($Suburb)){
					$driver_update_array['Suburb']=$Suburb;
				}
				if(isset($State) && !empty($State)){
					$driver_update_array['State']=$State;
				}
				if(isset($Postcode) && !empty($Postcode)){
					$driver_update_array['Postcode']=$Postcode;
				}
				if(isset($licenceNo) && !empty($licenceNo)){
					$driver_update_array['LicenceNo']=$licenceNo;
				}
				if(isset($PhoneNo) && !empty($PhoneNo)){
					$driver_update_array['PhoneNo']=$PhoneNo;
				}
				
						
				if(isset($ProfileImage) && !empty($ProfileImage)){
					$driver_update_array['ProfileImage']=$ProfileImage;
				}
				if(isset($DocumentImage) && !empty($DocumentImage)){
					$driver_update_array['DocumentImage']= $DocumentImage;
				}					
				//echo '<pre>';print_r($driver_update_array);exit;

				if(!empty($Building) || !empty($Street) || !empty($country) || !empty($Suburb) || !empty($State) || !empty($Postcode) || !empty($licenceNo) || !empty($PhoneNo) || !empty($ProfileImage) || !empty($DocumentImage)){

					$driver_query = $this->drivermaster_model->update_driver_data($driver_update_array,$UserId);
				}					
					
				$VehicleName=$this->input->post('vehiclename');
				$VehicleNo=$this->input->post('vehicleno');
		
				$vehicle_update_array=array(
											'DriverId'=>$driver_Id,
											//'VehicleType'=>$VehicleName,
											//'VehicleNumber'=>$VehicleNo
											);
				if(isset($VehicleName) && !empty($VehicleName)){
					$vehicle_update_array['VehicleType']= $VehicleName;
				}	
				if(isset($VehicleNo) && !empty($VehicleNo)){
					$vehicle_update_array['VehicleNumber']= $VehicleNo;
				}		

				if(!empty($VehicleName) || !empty($VehicleNo)){					
					$driver_query = $this->drivermaster_model->update_vehicle_detail($vehicle_update_array,$driver_Id);					
				}
					
				$arr_json['success'] = "1";
				$msgch ="Profile Updated Success.";
				$arr_json['message'] =  $msgch;
				
				$requestby = $Apikey;
				$request_parameter = $_POST;
				$apirequest = json_encode($request_parameter);
				$Request_Type = "Driver Profile Updated";
				$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
				echo json_encode($arr_json);exit;
	}
	
	/********* Company Driver List ***************/
	public function lists()
	{
		$Apikey = $this->input->post('Apikey');
		$filter = $this->input->post('filter');
		$type = 'company';
		
		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);
		
		if(!empty($Apikey) && !empty($filter))
		{ 
			$data = $this->general_model->verify_token($Apikey,$type);
			$result = $data['result'];
		
			if($result == 1)
			{
				$drivers_listing = $this->driverregister_model->get_driver_list($Apikey,$filter);
				$drivers_listing['data'][0]->ProfileImage = base_url().$drivers_listing['data'][0]->ProfileImage;
				$drivers_listing['data'][0]->DocumentImage = base_url().$drivers_listing['data'][0]->DocumentImage;
				$listing = $drivers_listing['result'];
				
				if($listing == 1)
				{
					$msgch ="Driver list geting successfully.";
					$arr_json['success'] = "1";
					$arr_json['message'] = $msgch;
					$arr_json['DriverDetail'] = $drivers_listing;
					
					$requestby = $Apikey;
					$request_parameter = $_POST;
					$apirequest = json_encode($request_parameter);
					$Request_Type = "CompanyDriver List";
					$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
					echo json_encode($arr_json);exit;
				}
				else
				{
					$msgch ="Data not Available as per your filter criteria.";
					$arr_json['success'] = "0";
					$arr_json['message'] = $msgch;
				}
			}
			else
			{
				$msgch ="Invalide Token.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="All Fields Are Required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}

	/********** Change online/offline status of driver *********/
	public function WorkingStatus()
	{
		$Apikey = $this->input->post('Apikey');
		//$DriverUserId = $this->input->get('DriverUserId');
		$Status = $this->input->post('Status');
		$type = 'driver';
		//print_r($_GET);exit;
		//echo isset($Status);exit;
		// if(!empty($Apikey) && !empty($Status))
		$currentdt= date('Y-m-d h:i:s');
		$starttime = new DateTime($currentdt);
		if(!empty($Apikey) && ($Status==0 || $Status==1))
		{ 
			if(is_numeric($Status) && $Status=="1" || $Status=="0"){
				$data = $this->general_model->verify_token($Apikey,$type);
				//print_r($data);exit;
				$result = $data['result'];
				
				if($result == 1)
				{
				//print_r($data);
				//$DriverUserId = 
				$DriverUserId = $data['data'][0]->UserId;//exit;
				$update_array= array('WorkingStatus'=>$Status);
					
				$updatestatus_result = $this->drivermaster_model->UpdateWorkingStatus($DriverUserId,$update_array);
									
					if($updatestatus_result)
					{
						$msgch ="Driver Status updated successfully.";
						$arr_json['success'] = "1";
						$arr_json['message'] = $msgch;
						$Token = $Apikey;
						$requestby = $Token;
						$request_parameter = $_POST;
						$apirequest = json_encode($request_parameter);
						$Request_Type = "Driver's WorkingStatus Changed";
						$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
						echo json_encode($arr_json); exit;

					}
					else
					{
						$msgch ="Something goes wrong.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
					}
				}
				else{
					$msgch ="Invalide Token.";
					$arr_json['success'] = "0";
					$arr_json['message'] = $msgch;
				}
			}
			else{
				$msgch ="Only single digit allowd as status field value.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="Invalid Input, All Fields Are Required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}

	/*********** Change Password of driver ************/
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
			$type = 'driver';
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
				$roleid = 3;
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
							$arr_json['success'] = 1;
							$arr_json['message'] = $msgch; 
							
							$Token = $Apikey;
							$requestby = $Token;
							$request_parameter = $_POST;
							$apirequest = json_encode($request_parameter);
							$Request_Type = "Driver's password changed";
							$this->general->api_logs($requestby,$apirequest,json_encode($arr_json),$arr_json['success'],$starttime,$Request_Type);
							echo json_encode($arr_json); exit;
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

	
	
}
