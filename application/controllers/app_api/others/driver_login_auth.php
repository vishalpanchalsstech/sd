<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class driver_login_auth extends CI_Controller {

    function __construct()
    {
       parent::__construct();
		$this->load->library('session');
		$this->load->model('login_model');
    }

 
    function driver_login()
    {
       $email = $this->input->post('email');
       $password = $this->input->post('password');
       // $roleid = $this->input->post('roleid');
       $roleid = 3;
		if(!empty($email) && !empty($password) && !empty($roleid) && is_numeric($roleid))
		{
			$result= $this->login_model->login_validate($email,$password,$roleid);
		
			if($result['result']!=1)
			{
				$msgch ="Invalid email and/or password.";
				$arr_json['success'] = "0";
				$arr_json['msg'] = $msgch;
			}
			else
			{
				$data = $result['data'];
				$msgch = "Login successful";
				$success = array('success'=>'1','msg'=>$msgch);
				$arr_json = array_merge($success,$data);
			}
	    }
		else
		{
			$msgch ="Something gone wrong. Please Try again.";
			$arr_json['success'] = "0";
            $arr_json['msg'] = $msgch;
		}	
		
		echo json_encode($arr_json);
    }
     
  
}
    

?>