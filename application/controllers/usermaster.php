<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class usermaster extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('usermaster_model');
			$this->load->model('general_model');
			$general = $this->load->library('../controllers/general');
			if($this->session->userdata('logged_in'))
			{
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
			//$data['EditData'] = $this->usermaster_model->edit_data($Id);
			$data['GetuserData'] = $this->usermaster_model->GetuserData();
			//print_r($data);exit;
			$data['section'] = array('usermaster_view');
			$this->general->load_my_view($data);
		}
		public function user_insert(){
			$data = $this->general->check_current_session();
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$message = $this->session->flashdata('my_msg');		
			$data['message'] = $message;
			$data['section'] = array('usermaster_insert');
			$this->general->load_my_view($data);
		}
		public function insert(){
			
			$session_data = $this->session->userdata('logged_in');
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$email=$this->input->post('Email');
			$CompanyId=$this->input->post('CompanyId');
			// $validateemail=$this->general_model->emailvalidate($email);
			$validateemail=$this->usermaster_model->same_company_emailvalidate($email,$CompanyId);
			if($validateemail == 1)
			{
				$message='<div class="alert alert-danger" role="alert"><strong>Please Enter Unique Value Of Email</strong></div>';
				$flash_data = $this->session->set_flashdata('my_msg',$message);
				redirect('/usermaster/user_insert');
			}		
			else
			{
				$user_id = $session_data['userid'];
				$Name=$this->input->post('Name');
				$Email=$this->input->post('Email');
				$CompanyId=$this->input->post('CompanyId');
				$Password=$this->input->post('Password');
				$superadminid=1;
				$role=2;
				if($CompanyId==$superadminid){
					$RoleId=$superadminid;
				}else{
					$RoleId=$role;
				}
               // print_r($_POST);//exit;
                $adminemailvalidate=$this->usermaster_model->adminemailvalidate($Email,$RoleId);
				//echo $adminemailvalidate;exit;
				if($adminemailvalidate==0) {
                    //$token = $this->general_model->getToken(15);
                    $user_token = $this->general->VersatileAccessToken("SST", "user");
                    //echo $token;exit;
                    $insert_array = array(
                        'Name' => $Name,
                        'Email' => $Email,
                        'CompanyId' => $CompanyId,
                        'Password' => MD5($Password),
                        'RoleId' => $RoleId,
                        'Token' => $user_token,
                        'CreatedBy' => $user_id,
                    );
                    $query = $this->usermaster_model->insert_data($insert_array);
                    if ($query == 1) {
                        $msg = '<div class="alert alert-success" role="alert"><strong>Data inserted sucessfully.</strong></div>';
                    } else {
                        $msg = '<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
                    }
                    $flash_data = $this->session->set_flashdata('my_msg', $msg);
                    redirect('usermaster');
                }else{
                    $message='<div class="alert alert-danger" role="alert"><strong>Already assign as admin with other company, Please choose unique value of Email.!!</strong></div>';
                    $flash_data = $this->session->set_flashdata('my_msg',$message);
                    redirect('/usermaster/user_insert');
                }

			}
		}
		public function Edit($Id){
			$data = $this->general->check_current_session();
			$data['EditData'] = $this->usermaster_model->edit_data($Id);
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['section'] = array('usermaster_insert');
			$this->general->load_my_view($data);
		}
		public function update(){
			$session_data = $this->session->userdata('logged_in');		
			$UpdateId=$this->input->post('UpdateId');
			$Name=$this->input->post('Name');
			$Email=$this->input->post('Email');
			$CompanyId=$this->input->post('CompanyId');
			$Password=$this->input->post('Password');
			$Token=$this->input->post('Token');
			$sstechadminid=1;
			$role=2;
			if($CompanyId==$sstechadminid){
				$RoleId=$sstechadminid;
			}else{
				$RoleId=$role;
			}			
			$last_pass = $this->input->post('lasspass');  
			if($last_pass==$Password)
			  {
			   $ins_pass=$Password;
			  }
			  else
			  {
			   $ins_pass=md5($Password);
			  }
  			$timestamp = date('Y-m-d H:i:s');
			$update_array=array(
				'Name'=>$Name,
				'Email'=>$Email,
				'CompanyId'=>$CompanyId,
				'Password'=>$ins_pass,
				'RoleId'=>$RoleId,
				'Token'=>$Token,
				'UpdatedAt'=>$timestamp
			);
			$query=$this->usermaster_model->update_data($update_array,$UpdateId);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data Updated sucessfully.</strong></div>';					
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('usermaster');
		}
		public function Delete($Id){
			$session_data = $this->session->userdata('logged_in');
			$query=$this->usermaster_model->Delete_data($Id);
			if($query==1){
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
			}
			else{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('usermaster');
		} 
	}
?>