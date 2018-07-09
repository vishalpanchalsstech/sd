<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

    function __construct()
    {
       
        parent::__construct();
		$this->load->library('session');
		$this->load->model('general_model');
		$this->load->model('general_api_model');
	}
 
	function validate_email()
    {
		$email=$this->input->post('email');
		$roleid=$this->input->post('roleid');
		if(!empty($email) && !empty($roleid) && is_numeric($roleid))
		{
		    $result = $this->general_model->emailvalidate($email,$roleid);
			if( $result > 0)
			{
				$arr_json['success'] = "0";
				$arr_json['msg'] = "Email already exist. Please use different email.";
			}
			else
			{
				$msgch ="You can use this Email.";
				$arr_json['success'] = "1";
				$arr_json['msg'] = $msgch;
			}
		}
		else
		{
			$msgch ="Missing Email or role type.";
			$arr_json['success'] = "0";
			$arr_json['msg'] = $msgch;
		}	
		
		echo json_encode($arr_json); 
	}
	function country_list()
    {
	   $country_details = $this->general_api_model->Get_country_api();
		
		if(!empty($country_details))
		{
			$arr_json['success'] = "1";
			$arr_json['msg'] = "Success";
			$arr_json['Result'] = $country_details;
		}
		else
		{
			$msgch ="Something gone wrong. Please Try again.";
			$arr_json['success'] = "0";
            $arr_json['msg'] = $msgch;
		}
		
		echo json_encode($arr_json); 
	}
	function suburb_list()
    {
		$country = $this->input->get('country');
		$countries = explode("-", $country);
		$country = $countries[1];
		$keyword = $this->input->get('keyword');
		$subrub_array = $this->general_model->get_all_location_details($country,$keyword);
		
		if(!empty($subrub_array))
		{
			$arr_json['success'] = "1";
			$arr_json['msg'] = "success";
			$arr_json['Result'] = $subrub_array;
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