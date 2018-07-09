<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class findmap extends CI_Controller {
	function __construct(){
			parent::__construct();
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
		$data['section'] = array('findmap_view');
		$this->general->load_my_view($data);
	}
}