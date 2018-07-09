<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class companydriver extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('companydriver_model');
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
			$session_data = $this->session->userdata('logged_in');
			//print_r($session_data); exit;
			$user_id = $session_data['userid'];
			$companyid = $session_data['companyid'];
			$roleid = $session_data['roleid'];
			$rcompanyid = '';
			if($roleid == '2'){
				$rcompanyid = $companyid;
			}
			if($roleid == '3'){
				$data['GetcdData'] = $this->companydriver_model->GetDrivercdData($user_id);
			}else{
				$data['GetcdData'] = $this->companydriver_model->GetcdData($rcompanyid);
			}
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['roleid'] = $roleid;
			$data['companyid'] = $companyid;
			$data['section'] = array('companydriver_view');
			$this->general->load_my_view($data);
		}
		
		public function insert(){
			$session_data = $this->session->userdata('logged_in');
			$email = $this->input->post('Email');
			$companyid = $this->input->post('CompanyId');
			
			$userid = $this->companydriver_model->email_check($email);
			if($userid > 0){
				$query=$this->companydriver_model->insert_data($userid,$companyid);
				if($query==1)
				{
					$msg='<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Driver added sucessfully.</strong></div>';
					$status = 1;
					$flash_data = $this->session->set_flashdata('my_msg', $msg);
				}
				elseif($query==2)
				{
					$msg='<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Something went wrong.</strong></div>';
					$status = 0;
				}elseif($query==0){
					$msg='<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Already added this driver.</strong></div>';
					$status = 0;
				}elseif($query==3){
					$msg='<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>This Driver is deleted for this company.</strong></div>';
					$status = 0;
				}
			}else{
				$msg='<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>This driver email is not registered.</strong></div>';
				$status = 0;
			}
			
			echo json_encode(array('msg' => $msg,'status' => $status));	
			
		}
		
		public function update(){
			$session_data = $this->session->userdata('logged_in');		
			$UpdateId = $this->input->post('id');
			$priority = $this->input->post('priority');
			
			$update_array=array('Priority'=>$priority);
			$query=$this->companydriver_model->update_data($update_array,$UpdateId);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Priority Updated sucessfully.</strong></div>';
				$status = 1;				
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			echo json_encode(array('status' => $status));	
		}
		public function Delete($Id){
			$session_data = $this->session->userdata('logged_in');
			$query=$this->companydriver_model->Delete_data($Id);
			if($query==1){
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
			}
			else{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('companydriver');
		} 
	}
?>