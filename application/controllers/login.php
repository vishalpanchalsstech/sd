<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login extends CI_Controller{
	function __construct(){
		parent::__construct();
		 $this->load->model('login_model');	
		$general = $this->load->library('../controllers/general');
	}
	
	public function index($msg = NULL){		
		
		
	    if(!$this->session->userdata('logged_in'))
	    {
			$data['msg'] = $msg;
			$data['msgf']=$this->session->flashdata('my_msg');
			$this->load->view('login_view', $data);
	    }
	    else{
    	    $session_data = $this->session->userdata('logged_in');
			$this->data['email'] = $session_data['email'];
			$data['name'] = $session_data['fname'];
			redirect('dashboard');
			
	    }
	    
	}

	
	/**************** Main Login process to validate login ********************/
	public function process(){
	    // Load the model
	    $data=$this->session->userdata('url');
	     $this->load->model('login_model');	
	    
	    // Validate the user can login
	    $email = $this->security->xss_clean($this->input->post('email'));
	    $password = $this->security->xss_clean($this->input->post('password'));
		
		$result = $this->login_model->validate($email,$password);
		$resultch = $result['result'];
		$msgch = $result['msg'];
	    // Now we verify the result
	    if($resultch == '0'){
			
	        // If user did not validate, then show them login page again
	        $msg= '<div class="alert alert-danger" role="alert" id="success-alert"><strong>Invalid username and/or password</strong></div>';
			$this->index($msg);
	    }else{
			
	       	$session_data = $this->session->userdata('logged_in');
			$this->data['email'] = $session_data['email'];
			$data['name'] = $session_data['name'];
			if(isset($session_data)){
				redirect('dashboard');
			}
			}		  
	}
	
	
	/******************* Logout function ***************/
	 public function logout($logout=''){
	  	$sess_array = array("userid"=>"","roleid"=>"","name"=>"","companyid"=>"","email"=>"");
		$this->session->unset_userdata('logged_in',$sess_array);
	
	    $msg= '<div class="alert alert-success" role="alert" id="success-alert"><strong>You have sucessfully logged out.</strong></div>';
		$flash_data = $this->session->set_flashdata('my_msg', $msg);
		
		redirect('login');
	 }
	

} ?>