<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class drivermaster extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('drivermaster_model');
			$this->load->model('general_model');
			$general = $this->load->library('../controllers/general');
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				$data['email'] = $session_data['email'];
				$data['lastlogin'] = $session_data['lastlogin'];
			}
			else
			{
				redirect('/login/logout', 'refresh');
			}
		}
		public function index()
		{
			$data = $this->general->check_current_session();
			$msg=$this->session->flashdata('my_msg');
			$data['msg'] =$msg;
			$data['driver_data']=$this->drivermaster_model->Get_driver_Data();
			$data['section'] = array('drivermaster_view');
			$this->general->load_my_view($data);
		}
		public function driver_insert()
		{
			$data = $this->general->check_current_session();
			$data['country_details'] = $this->general_model->Get_country_Records();
			//print_r($data['country_details']);exit;
			$data['section'] = array('drivermaster_insert');
			$this->general->load_my_view($data);
		}
		public function validate_email()
		{
			$email=$this->input->post('email');
			$validateemail = $this->general_model->emailvalidate($email,3);
			
			if($validateemail)
			{
				echo '1';
			}
			else
			{
				echo '0';
			} 
		}
		public function insert()
		{
		    $session_data = $this->session->userdata('logged_in');
			$name=$this->input->post('name');
			$email=$this->input->post('email');
			$Password=$this->input->post('Password');
			$RoleId = 3;
			$user_id = $session_data['userid'];			
			// $user_token = $this->general_model->getToken(15);   

			$user_token = $this->general->VersatileAccessToken("SST","driver");   
			$token = $this->general->VersatileAccessToken(); 	
		
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
										'CreatedBy'=>$user_id,
										'Enable'=>'0'
									);					
				$user_query = $this->drivermaster_model->insert_user_data($user_insert_array);
				if($user_query)
				{
					$name=$this->input->post('name');
					$Building=$this->input->post('Building');
					$Street=$this->input->post('Street');
					$country=$this->input->post('country');
					$Suburb=$this->input->post('Suburb');
					$State=$this->input->post('State');
					$Postcode=$this->input->post('Postcode');
					$PhoneNo=$this->input->post('PhoneNo');
					$licenceNo=$this->input->post('licenceNo');
					$userId = $user_query;
					
					
					$profile_target_path = 'assets/uploads/images/driver/';
					$document_target_path = 'assets/uploads/images/document/';
					
					if(isset($_FILES['file2']['name']) && ($_FILES['file']['name']))
					{
						$ext = pathinfo($_FILES['file2']['name'], PATHINFO_EXTENSION);
						$profile_name = $this->general_model->imagename($name);   
						$target_path = $profile_target_path.$profile_name.".".$ext;
						//$profile_target_path = $profile_name.".".$ext;
						move_uploaded_file($_FILES["file2"]["tmp_name"], $target_path);
					   
						$document_ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
						$document_name = $this->general_model->imagename($name);
						$target_path_document = $document_target_path.$document_name.".".$ext;
						//$document_target_path = $document_name.".".$document_ext;
						move_uploaded_file($_FILES["file"]["tmp_name"], $target_path_document);
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
					
					$driver_query = $this->drivermaster_model->insert_driver_data($driver_insert_array);
					
					if($driver_query)
					{
						$VehicleName=$this->input->post('vehiclename');
						$VehicleNo=$this->input->post('vehicleno');
						$driver_Id = $driver_query;
						
						$vehicle_detail_array=array(
													'DriverId'=>$driver_Id,
													'VehicleType'=>$VehicleName,
													'VehicleNumber'=>$VehicleNo
													);
							$driver_query = $this->drivermaster_model->insert_vehicle_detail($vehicle_detail_array);
							$msg='<div class="alert alert-success" role="alert"><strong>Please check your email for verify.</strong></div>';
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
						
				}
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Please Use another email.</strong></div>';
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('drivermaster');
		}

		public function verify()
		{
			$email = $this->input->get('email');
			$token = $this->input->get('token');
			$result = $this->driverregister_model->verifydriver($email,$token); 
			if($result == '1')
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Email is verified. Please Login.</strong></div>';
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Email is not verified.</strong></div>';
			}
			
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('login');
		}
		/**************** Edit Driver Master Data ************/
		
		public function Edit($Id)
		{
			$data = $this->general->check_current_session();
			$editrecord = $this->drivermaster_model->edit_data($Id);
			//echo "<pre>";print_r($editrecord);exit;
			$data['country_details'] = $this->general_model->Get_country_Records();
			$data['EditData'] = $editrecord;
		//	echo '<pre>';print_r($data['EditData']);exit;
			$data['section'] = array('drivermaster_insert');
			$this->general->load_my_view($data);
		}
		
		/**************** Update Driver Master Data ************/
		
		public function update()
		{
			$session_data = $this->session->userdata('logged_in');
			$UserId = $this->input->post('UserId');
			$drvr_id = $this->input->post('driverId');
			$name=$this->input->post('name');
			$email=$this->input->post('email');
			$Password=$this->input->post('Password');
			$New_pass=$this->input->post('New_pass');
			$Token=$this->input->post('Token');
		//	echo $Token;exit;
			if($New_pass==$Password)
			{
				$ins_pass=$Password;
			}
			else
			{
				$ins_pass=md5($Password);
			}
			$RoleId = 3;
			$timestamp = date('Y-m-d H:i:s');
			$user_update_array=array( 
										'Name'=>$name,
										'Email'=>$email,
										'Password'=>$ins_pass,
										'RoleId'=>$RoleId,
										'Token'=>$Token,
										'UpdatedAt'=>$timestamp
									);
			//echo '<pre>';print_r($user_update_array);exit;
			$user_update_query = $this->drivermaster_model->update_user_data($user_update_array,$UserId);
			//echo '<pre>';print_r($user_update_query);exit;
			if($user_update_query)
			{
				$name=$this->input->post('name');
				$Building=$this->input->post('Building');
				$Street=$this->input->post('Street');
				$country=$this->input->post('country');
				$Suburb=$this->input->post('Suburb');
				$State=$this->input->post('State');
				$Postcode=$this->input->post('Postcode');
				$PhoneNo=$this->input->post('PhoneNo');
				$licenceNo=$this->input->post('licenceNo');
				$userId = $user_update_query;
				$uploadimg = $this->input->post('uploadimg');
				$uploadimg2 = $this->input->post('uploadimg2');
				$WorkingStatus = $this->input->post('WorkingStatus');
				
				
				$profile_target_path = 'assets/uploads/images/driver/';
				$document_target_path = 'assets/uploads/images/document/';
				
				if(!empty($_FILES['editfile2']['name']) || ($_FILES['editfile']['name']))
				{ 
					$ext = pathinfo($_FILES['editfile2']['name'], PATHINFO_EXTENSION);
					$add= "." . $ext;
					$profile_name = $this->general_model->imagename($name);   
					$target_path = $profile_target_path . $profile_name . $add;
					
					if(move_uploaded_file($_FILES['editfile2']['tmp_name'], $target_path))
					{
						$uploadimg = $target_path;
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
					
					$document_ext = pathinfo($_FILES['editfile']['name'], PATHINFO_EXTENSION);
					$add= "." . $document_ext;
					$document_name = $this->general_model->imagename($name);   
					$target_path_document = $document_target_path . $document_name . $add;
					
					if(move_uploaded_file($_FILES['editfile']['tmp_name'], $target_path_document))
					{
						$uploadimg2 = $target_path_document;
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
				}
				$driver_update_array=array(
											'Phoneno'=>$PhoneNo,
											'Building'=>$Building,
											'Street'=>$Street,
											'Country'=>$country,
											'Suburb'=>$Suburb,
											'State'=>$State,
											'Postcode'=>$Postcode,
											'LicenceNo'=>$licenceNo,
											'ProfileImage'=>$uploadimg,
											'DocumentImage'=>$uploadimg2,
											'WorkingStatus'=>$WorkingStatus,
											'UserId'=>$userId
										);
			//	echo '<pre>';print_r($driver_update_array);exit;
				$driver_query = $this->drivermaster_model->update_driver_data($driver_update_array,$userId,$drvr_id);
				//print_r ($driver_query);exit;
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
						$driver_query = $this->drivermaster_model->update_vehicle_detail($vehicle_update_array,$driver_Id);
						$msg='<div class="alert alert-success" role="alert"><strong>Driver Registered sucessfully.</strong></div>';
				}
				else
				{
					$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
				}
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('drivermaster');
		}

		/*********** Delete Driver Master Data ************/

		public function Delete($Id)
		{
			$session_data = $this->session->userdata('logged_in');
			
			$query=$this->drivermaster_model->Delete_data($Id);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('drivermaster');
		}
		
	}
?>