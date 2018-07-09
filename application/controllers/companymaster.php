<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class companymaster extends CI_Controller
	{
		function __construct(){
			parent::__construct();
			$this->load->model('companymaster_model');
			$this->load->model('general_model');
			$general = $this->load->library('../controllers/general');
			if($this->session->userdata('logged_in')){
				$session_data = $this->session->userdata('logged_in');
				$data['email'] = $session_data['email'];
				$data['lastlogin'] = $session_data['lastlogin'];
			}else{
				redirect('/login/logout', 'refresh');
			}
		}
		public function index(){
			
			$data = $this->general->check_current_session();
			$msg=$this->session->flashdata('my_msg');
			$data['msg'] =$msg;
			$data['company_data']=$this->companymaster_model->GetcompanyData();
			$data['country'] = $this->general_model->Get_Records_country();
			$data['timezone'] = $this->general->tz_list();
			$data['section'] = array('companymaster_view');
			$this->general->load_my_view($data);
		}
		public function company_insert(){
			$data = $this->general->check_current_session();
			$data['country'] = $this->general_model->Get_Records_country();
			$data['timezone'] = $this->general->tz_list();
			$message = $this->session->flashdata('my_msg');		
			$data['message'] = $message;	
			$data['section'] = array('companymaster_insert');
			$this->general->load_my_view($data);
		}

		public function state_list()
		{
			$country = $this->input->post('country');
			$result = $this->general_model->Get_Records_state($country);
			$statename[] = "<option></option>";
			if(empty($result)) 
			{
				$statename[] = "<option>No Records Found.</option>";
			}	
			foreach($result as $value){
				
				$statename[] = '<option id="statevalue" name="'. $value->CountryStateId.'" value="'. $value->State.'">'. $value->State.'</option>';
			}
			
			echo json_encode($statename);
		}
		public function city_list()
		{
			$state = $this->input->post('state');
			$result = $this->general_model->Get_Records_city($state);
			$cityname[] = "<option></option>";
			if(empty($result)) 
			{
				$cityname[] = "<option>No Records Found.</option>";
			}	
			foreach($result as $value){
				
				$cityname[] = '<option value="'. $value->Suburb.'" name="'. $value->CountryStateSuburbId.'">'. $value->Suburb.'</option>';
			}
			
			echo json_encode($cityname);
		}
		public function key_city_list()
		{
			$state = $this->input->post('state');
			$keyword = $this->input->post('query');
			$result = $this->general_model->Like_Records_city($state,$keyword);
			$cityname[] = '';
			//print_r($result); exit;
			if(!empty($result)) 
			{
				foreach($result as $value){
				
				$cityname[] = $value->Suburb; 
				}
				
			}else{
				$cityname[] = 'No Records';
			}	
			echo json_encode($cityname);
			
			
		}

		public function insert(){
			$session_data = $this->session->userdata('logged_in');
			$Name=$this->input->post('Name');
			$Email=$this->input->post('Email');
			$Address=$this->input->post('Address');
			$Country=$this->input->post('country');
			$State=$this->input->post('state');
			$City=$this->input->post('city');
			$companyprefix=$this->input->post('companyprefix');
			$TimeZone=$this->input->post('TimeZone');
			$dupli_email_check =$this->companymaster_model->company_emailvalidate($Email);
				
			if($dupli_email_check == 1)
			{
				$message='<div class="alert alert-danger" role="alert"><strong>Please Enter Unique Value Of Email</strong></div>';
				$flash_data = $this->session->set_flashdata('my_msg',$message);
				redirect('/companymaster/company_insert');
			}
			else
			{	
				$target_path = 'assets/uploads/images/company/';
				if (isset($_FILES['file']['name'])){
					$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
					$add= "." . $ext;
					$profile_name = $this->general_model->imagename($Name);   
					$target_path = $target_path . $profile_name . $add;
					
					if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path))
					{
						$insert_array=array(
									'Name'=>$Name,
									'Email'=>$Email,
									'Address'=>$Address,
									'Logo'=>$target_path,
									'Country'=>$Country,
									'State'=>$State,
									'City'=>$City,
									'Prefix'=>strtoupper($companyprefix),
									'TimeZone'=>$TimeZone
									);
						$query = $this->companymaster_model->insert_data($insert_array);
						// $token = $this->general_model->getToken(15); 
						$companyprefix = strtoupper($companyprefix);
						$token = $this->general->VersatileAccessToken($companyprefix,'company');

						$insert_array_ps=array(
									'CompanyId'=>$query,
									'Token'=>$token,
									'NotificationMethodId'=>'1');
						
						
						$query_ps = $this->companymaster_model->insert_data_pre_setti($insert_array_ps);	
						
						
						
						if($query_ps==1)
						{
							$msg='<div class="alert alert-success" role="alert"><strong>Data inserted sucessfully.</strong></div>';					
						}
						else
						{
							$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
						}
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
				}
				
				
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
				redirect('companymaster');
			}
		}

		/*Check Company Prefix*/
		public function checkcompanyprefix(){
			$company_prefix = $this->input->post('company_prefix');
			$result = $this->companymaster_model->prefixduplicationcheck($company_prefix);
			echo json_encode($result);
		}
		
	
		public function Edit($Id)
		{
			$data = $this->general->check_current_session();
			
			$editrecord = $this->companymaster_model->edit_data($Id);
			if(isset($editrecord)){
				$country = $editrecord[0]->Country;
				$data['state'] = $this->companymaster_model->edit_state_data($country);
				//print_r($data['state']); exit;
			}
			$data['EditData'] = $editrecord;
			$data['psdata'] = $this->companymaster_model->psdata($Id);
			$data['country'] = $this->general_model->Get_Records_country();
			$data['notmethod'] = $this->companymaster_model->NotiMethod();
			$data['timezone'] = $this->general->tz_list();
			$data['section'] = array('companymaster_insert');
			$this->general->load_my_view($data);

		}
		/********************************** update company Data ***********************************/
		public function update(){
			
			$session_data = $this->session->userdata('logged_in');
			$Id = $this->input->post('UpdateId');
			$Name = $this->input->post('Name');
			$Email = $this->input->post('Email');
			$Address = $this->input->post('Address');
			$uploadimg = $this->input->post('uploadimg');
			$Country = $this->input->post('country');
			$State = $this->input->post('state');
			$City = $this->input->post('city');
			$token = $this->input->post('token');
			$notmethod = $this->input->post('notmethod');
			$TimeZone = $this->input->post('TimeZone');
			
			$target_path = 'assets/uploads/images/company/';
			
			if (!empty($_FILES['editfile']['name'])){ 
				$ext = pathinfo($_FILES['editfile']['name'], PATHINFO_EXTENSION);
				$add= "." . $ext;
				$profile_name = $this->general_model->imagename($Name);   
				$target_path = $target_path . $profile_name . $add;
				
				if(move_uploaded_file($_FILES['editfile']['tmp_name'], $target_path))
				{
					$uploadimg = $target_path;
				}else{
					$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
				}
			}
			$timestamp = date('Y-m-d H:i:s');
			$update_array=array(
						'Name'=>$Name,
						'Email'=>$Email,
						'Address'=>$Address,
						'Logo'=>$uploadimg,
						'Country'=>$Country,
						'State'=>$State,
						'City'=>$City,
						'TimeZone'=>$TimeZone,
						'UpdatedAt'=>$timestamp
						);
			$query = $this->companymaster_model->update_data($update_array,$Id);	
			
			$update_ps_array = array('Token'=>$token,'NotificationMethodId'=>$notmethod);
			
			$query_ps = $this->companymaster_model->update_ps_data($update_ps_array,$Id);	
					
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data Updated sucessfully.</strong></div>';					
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('companymaster');
		}
		/********************************** Delete company Data ***********************************/
		public function Delete($Id)
		{
			$session_data = $this->session->userdata('logged_in');
			
			$query=$this->companymaster_model->Delete_data($Id);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
								
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
				$flash_data = $this->session->set_flashdata('my_msg', $msg);
			
			redirect('companymaster');
		}
	}
?>