<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Getdriverdetails extends CI_Controller {

    function __construct()
    {
       parent::__construct();
		$this->load->library('session');
		$this->load->model('drivermaster_model');
		$this->load->model('general_model');
    }

 
    function Getdriver_details()
    {
       //$driver_id = $this->input->post('id');  
	   $token = $this->input->post('token');
	   $type = 'driver';
	   if(!empty($token)){
				$data = $this->general_model->verify_token($token,$type);
				
				$result = $data['result'];
				if($result == 0){
							$arr_json['success'] = "0";
							$arr_json['message'] = "Invalide Token.";
							echo json_encode($arr_json);exit;
				}
				else{
					if(isset($data)){
						$driver_id = $data['data'][0]->Id;
						$driver_result= $this->drivermaster_model->get_driver_details($driver_id);
						if(empty($driver_result))
						{
							$arr_json['success'] = "False";
							$arr_json['message'] = "No Records found.";
							echo json_encode($arr_json);exit;
						}
						else
						{
							$arr_json['success'] = "Success";
							$arr_json['result'] = $driver_result;
							echo json_encode($arr_json);exit;
						}
					}
				}
		}
		else{
					$msgch = "Missing apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
		}	
		
    } 
}
?>