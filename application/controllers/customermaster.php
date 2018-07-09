<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class customermaster extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('customermaster_model');
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
			$data['customer_data']=$this->customermaster_model->Get_customer_list();
			//echo "<pre>";print_r($data['customer_data']);exit;
			$data['section'] = array('customermaster_view');
			$this->general->load_my_view($data);
		}
		public function customer_insert()
		{
			$data = $this->general->check_current_session();
			$data['country_details'] = $this->general_model->Get_country_Records();
			//print_r($data['country_details']);exit;
			$data['section'] = array('customermaster_insert');
			$this->general->load_my_view($data);
		}
		public function validate_customer_email()
		{
			$email=$this->input->post('email');
			$validateemail = $this->customermaster_model->customer_email_validate($email);
			
			if(!empty($validateemail))
			{
				echo '1';
			}
			else
			{
				echo '0';
			} 
		}
		public function insert_customer_details()
		{
		    $session_data = $this->session->userdata('logged_in');
			$name=$this->input->post('name');
			$email=$this->input->post('email');
			$Password=$this->input->post('Password');
			$RoleId = 4;
			
			$user_id = $session_data['userid'];
			//$token = $this->general_model->getToken(15);   
			//$user_token = $this->general_model->getToken(15);   
			$user_token = $this->general->VersatileAccessToken("SST","customer");
				
		
			$to = $email;
			$from = '';		
			$cc = '';
			$subject = 'SSTech Driver Email Verify';
			//$body = "<p>Hi $name ,</p><p>It's time to confirm your email address.</p><p>Have we got the right email address to reach you on? To confirm that you can get our emails, just click the button below.</p>";
			//$url = base_url().'customermaster/verify?email='.$email.'&token='.$user_token;
			
			//$body .= '<a style="background-color:#0070e0;border:1px solid #0070e0;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:220px" href="" target="_blank" >Confirm my email address</a>';
			$body = "<div style='padding:10px;font-family: Arial,Helvetica, sans-serif;'>
						 <h2>Customer Created Succesfully.</h2>
						 <p>Hi $name</p>
						 <p>Below Provided the Created Customer Credential: </p>
						 <label style='margin-right:23px;'>Email</label>
						 <label type='text' style='width:30%;padding:12px;margin:8px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;' name='email'>$email</label>
						 <br>
						 Password <label type='text'  style='width:30%;padding: 12px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;'  name='password'>$Password</label>
					  </div>";
			
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
										'Enable'=>'1'
									);					
				$user_query = $this->customermaster_model->insert_user_data($user_insert_array);
				//echo '<pre>';print_r($user_query);exit;
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
					$userId = $user_query;
					
					/*$profile_target_path = 'assets/uploads/images/customer/';
					
					if(isset($_FILES['file']['name']))
					{
						$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
						$profile_name = $this->general_model->imagename($name);   
						$target_path = $profile_target_path.$profile_name.".".$ext;
						//$profile_target_path = $profile_name.".".$ext;
						move_uploaded_file($_FILES["file"]["tmp_name"], $target_path);
					   
						
					}
					*/
					$customer_insert_array=array(
												'Phoneno'=>$PhoneNo,
												'Building'=>$Building,
												'Street'=>$Street,
												'Country'=>$country,
												'Suburb'=>$Suburb,
												'State'=>$State,
												'Postcode'=>$Postcode,
												//'ProfileImage'=>$target_path,
												'UserId'=>$userId
											  );
					
					$cust_insrt_query = $this->customermaster_model->insert_customer_data($customer_insert_array);
					//echo $cust_insrt_query;exit;
					if($cust_insrt_query == 1)
					{
						$msg='<div class="alert alert-success" role="alert"><strong>Customer detail inserted successfully.</strong></div>';
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
				redirect('customermaster');
		}

		
		/**************** Edit Driver Master Data ************/
		
		public function Edit($Id)
		{	
			//echo $Id;exit;
			$data = $this->general->check_current_session();
			//print_r($data);exit;
			$editrecord = $this->customermaster_model->edit_data($Id);
			//echo "<pre>";print_r($editrecord);exit;
			$data['country_details'] = $this->general_model->Get_country_Records();
			$data['EditData'] = $editrecord;
		//	echo '<pre>';print_r($data['EditData']);exit;
			$data['section'] = array('customermaster_insert');
			$this->general->load_my_view($data);
		}
		
		/**************** Update Driver Master Data ************/
		
		public function update()
		{
			$session_data = $this->session->userdata('logged_in');
			$UserId = $this->input->post('UserId');
			$cstmr_id = $this->input->post('customerId');
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
			$RoleId = 4;
			$timestamp = date('Y-m-d H:i:s');
			
			$user_update_array=array( 
										'Name'=>$name,
										'Email'=>$email,
										'Password'=>$ins_pass,
										'RoleId'=>$RoleId,
										'Token'=>$Token,
										'UpdatedAt'=>$timestamp
									);
			
			$user_update_query = $this->customermaster_model->update_user_data($user_update_array,$UserId);
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
				//$licenceNo=$this->input->post('licenceNo');
				$userId = $user_update_query;
				$uploadimg = $this->input->post('uploadimg');
				$profile_target_path = 'assets/uploads/images/driver/';
				$customer_update_array=array(
											'Phoneno'=>$PhoneNo,
											'Building'=>$Building,
											'Street'=>$Street,
											'Country'=>$country,
											'Suburb'=>$Suburb,
											'State'=>$State,
											'Postcode'=>$Postcode,
											'UserId'=>$userId
										);
				$cust_update_query = $this->customermaster_model->update_customer_data($customer_update_array,$userId,$cstmr_id);
				if($cust_update_query)
				{
					$msg='<div class="alert alert-success" role="alert"><strong>Customer detail Updated successfully.</strong></div>';
				}
				else
				{
					$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
				}
				
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('customermaster');
		}

		/*********** Delete Driver Master Data ************/

		public function Delete($Id)
		{
			$session_data = $this->session->userdata('logged_in');
			
			$query=$this->customermaster_model->Delete_data($Id);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('customermaster');
		}
		
	}
?>