<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class updateprofile extends CI_Controller 
	{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->library('upload');
        $this->load->model('drivermaster_model');
        $this->load->model('general_model');
       
    }
	
		function update_profile()
		{
			
			$apiKey = $this->input->post('apiKey');
			//$UserId = $this->input->post('UserId');
			//$drvr_id = $this->input->post('driverId');
			//echo $UserId;
			$name=$this->input->post('name');
			//$email=$this->input->post('email');
			$Password=$this->input->post('Password');
			$New_pass=$this->input->post('New_pass');
			if(strlen(trim($Password)) < 6 ){
						  $msgch ="Please enter maximum 6 character in Password.";
						  $arr_json['success'] = "0";
						  $arr_json['message'] = $msgch;
						  echo json_encode($arr_json);exit;
			}
			if(strlen(trim($New_pass)) < 6 ){
						  $msgch ="Please enter maximum 6 character in New Password.";
						  $arr_json['success'] = "0";
						  $arr_json['message'] = $msgch;
						  echo json_encode($arr_json);exit;
			}
			if($New_pass==$Password){
				$ins_pass=$Password;
			}
			else{
				$ins_pass=md5($Password);
			}
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
				'Password'=>$ins_pass,
				'RoleId'=>$RoleId,
				//'Token'=>$Token,
				'UpdatedAt'=>$timestamp
				);

				$type = 'driver';
				if(!empty($apiKey)){
					
					$data = $this->general_model->verify_token($apiKey,$type);
					$UserId = $data['data'][0]->UserId;
					$result = $data['result'];
					if($result == 0){
							$msgch ="Invalide Token.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
							echo json_encode($arr_json);exit;
					}
				}else{
					$msgch = "Missing apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
				}
				
				if(!empty($name) && !empty($Password) && !empty($Building) && !empty($Street) && !empty($country) && !empty($Suburb) && !empty($State) && !empty($Postcode) && !empty($PhoneNo) && !empty($licenceNo)){
					
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
				   
					$user_update_query = $this->drivermaster_model->update_user_data($user_update_array,$UserId);
					$msgch ="Profile Updated Success.";
					$arr_json['success'] = "1"; 
					$arr_json['message'] =  $msgch;	
					}
					else{
						$arr_json['success'] = false;
						$arr_json['message'] = "All Field Are Required.";
						echo json_encode($arr_json);exit;
					}
			
			if($user_update_query){
				$name=$this->input->post('name');
				$userId = $user_update_query;
				// $uploadimg = $this->input->post('uploadimg');
				// $uploadimg2 = $this->input->post('uploadimg2');
				$profile_target_path = 'assets/uploads/images/driver/';
				$document_target_path = 'assets/uploads/images/document/';
				// if(empty($_FILES['editfile2']))
				// {
				// 	$arr_json['success'] = false;
				// 	$arr_json['message'] = "Please insert ProfileImage.";
				// 	echo json_encode($arr_json);exit;
				// }
				// if(empty($_FILES['editfile']))
				// {
				// 	$arr_json['success'] = false;
				// 	$arr_json['message'] = "Please insert DocumentImage.";
				// 	echo json_encode($arr_json);exit;
				// }
				if(!empty($_FILES['editfile2']) && !empty($_FILES['editfile']))
				{
					
					$ext = pathinfo($_FILES['editfile2']['name'], PATHINFO_EXTENSION);
					$add= "." . $ext;
					$profile_name = $this->general_model->imagename($name);   
					$target_path = $profile_target_path . $profile_name . $add;
					
					if(move_uploaded_file($_FILES['editfile2']['tmp_name'], $target_path))
					{
						$uploadimg = $target_path;
					}
					
					$document_ext = pathinfo($_FILES['editfile']['name'], PATHINFO_EXTENSION);
					$add= "." . $document_ext;
					$document_name = $this->general_model->imagename($name);   
					$target_path_document = $document_target_path . $document_name . $add;
					if(move_uploaded_file($_FILES['editfile']['tmp_name'], $target_path_document))
					{
						$uploadimg2 = $target_path_document;
					}
				}
				// else{
				// 	$arr_json['success'] = false;
				// 	$arr_json['message'] = "Please ProfileImage AND DocumentImage Image.";
				// 	echo json_encode($arr_json);exit;
				// }
				
				//	$token = $this->general_model->getToken(15);  
				//echo $token;exit;
				//echo $Building;exit;
				$driver_update_array=array(
											'Building'=>$Building,
											'Street'=>$Street,
											'Country'=>$country,
											'Suburb'=>$Suburb,
											'State'=>$State,
											'Postcode'=>$Postcode,
											'LicenceNo'=>$licenceNo,
											'PhoneNo'=>$PhoneNo,											
										//	'Token'=>$token,
											'UserId'=>$userId
										);
				
				if(isset($uploadimg) && !empty($uploadimg)){
					$driver_update_array['ProfileImage']=$uploadimg;
				}
				if(isset($uploadimg2) && !empty($uploadimg2)){
					$driver_update_array['DocumentImage']= $uploadimg2;
				}					
				//echo '<pre>';print_r($driver_update_array);exit;
				$driver_query = $this->drivermaster_model->update_driver_data($driver_update_array,$userId);
				//print_r($driver_query);exit;
				if($driver_query)
				{
					$VehicleName=$this->input->post('vehiclename');
					$VehicleNo=$this->input->post('vehicleno');
					$driver_Id = $driver_query;
					$vehicle_update_array=array(
												'DriverId'=>$driver_Id,
												'VehicleType'=>$VehicleName,
												'VehicleNumber'=>$VehicleNo
												);
					if(!empty($VehicleName) && !empty($VehicleNo)){
						//echo '<pre>';print_r($vehicle_update_array);exit;
					$driver_query = $this->drivermaster_model->update_vehicle_detail($vehicle_update_array,$driver_Id);
					$msgch ="Profile Updated Success.";
					$arr_json['success'] = "1"; 
					$arr_json['message'] =  $msgch;	
					}
					else{ 
							$arr_json['success'] = false;
						   $arr_json['message'] = "All Field Are Required.";
						   echo json_encode($arr_json);exit;
						
					}
				}
				else
				{
					$arr_json['success'] = "0";
					$msgch ="Profile Updated Success.";
					$arr_json['message'] =  $msgch;
				}
			}
			echo json_encode($arr_json);
		}
	}
