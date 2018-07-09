<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Controller{
	function __construct(){
        parent::__construct();
		$this->load->model('dashboard_model');
		$this->load->model('general_model');

        $general = $this->load->library('../controllers/general');
        // $session_data = $this->session->userdata('logged_in');
        // echo '<pre>';print_r($session_data);exit;
        if($this->session->userdata('logged_in'))
        {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
            $data['lastlogin'] = $session_data['lastlogin'];
   			
        }else{
			   redirect('/login/logout', 'refresh');
		}
       
    
    }
   
	public function index()
	{
		$data = $this->general->check_current_session();
		/*$email = 'sstech.dipesh@gmail.com';
		$validateemail = $this->general_model->emailvalidate($email);
		if($validateemail > 0) {
			//validateemail further code
		}else{
			//Error Email already used
			echo "Email already used.";
		}
		$name = 'dipesh r   hajari ';
		$imagename = $this->general_model->imagename($name);*/
		
		$roleid=$data['roleid'];
        $user_detail =  $this->general_model->get_user();
        $data['totalusers'] = $user_detail[0]->user;

        $driver_detail =  $this->general_model->get_driver();
        $data['totaldriver'] = $driver_detail[0]->driver;
        $company_detail =  $this->general_model->get_company();
        $data['totalcompany'] = $company_detail[0]->company;
        $customer_detail =  $this->general_model->get_customer();
        $data['totalcustomer'] = $customer_detail[0]->customer;
        //print_r($data);exit;
		$data['section'] = array('dashboard_view');
		$this->general->load_my_view($data);
	}
	
}?>