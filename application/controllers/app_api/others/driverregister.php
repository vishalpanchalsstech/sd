<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class driverregister extends CI_Controller 
{
	function __construct()
	{
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->library('upload');
        $this->load->model('driverregister_model');
	    $this->load->model('general_model');
    }
	
	public function insert_registration()
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
	
		if(isset($_FILES['profile_image']) && isset($_FILES['document_image']))
		{
			
			if(!empty($name) && !empty($email) && !empty($Password) && !empty($PhoneNo) && !empty($licenceNo) && !empty($vehiclename) && !empty($vehicleno) && !empty($Building) && !empty($Street) && !empty($country) && !empty($Suburb) && !empty($State) && !empty($Postcode))
			{	
				$result = $this->general_model->emailvalidate($email);
				if( $result > 0)
				{	
					$arr_json['success'] = "0";
					$arr_json['message'] = "Email already exist. Please use different email.";
				}
				else
				{
					$token = $this->general_model->getToken(15);   
					$user_token = $this->general_model->getToken(15);   
					
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
								$msgch ="Driver Reghistered Successfully.";
								$arr_json['success'] = "1"; 
								$arr_json['message'] =  $msgch;
								
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
			$msgch ="Plz insert Images.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
			echo json_encode($arr_json);exit;
		}
		echo json_encode($arr_json);
	}
	
	
}
