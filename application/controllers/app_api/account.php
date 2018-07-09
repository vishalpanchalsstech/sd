<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class account extends CI_Controller {

    function __construct()
    {
       parent::__construct();
		$this->load->library('session');
		$this->load->model('general_model');
    }
	
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}
 
    public function switchapi()
    {
		
		 /*
		   Header
		   Content-Type:application/json
		   Apikey:SST100000000018
		   Body
		   {
		    "email":"sstech.vishal@gmail.com",
		    "roleId":"3"
		   }    
		  */
		
       //$driver_id = $this->input->get('id');  
	    $request_data = json_decode(file_get_contents('php://input'),true);
		$header =getallheaders();
		//print_r($header);exit;
		if($header['Content-Type'] != "application/json")
		{
			$message['success'] = false; 
			$message['message'] =  'Invalid content-type in header only json allowed.';
			echo json_encode($message); exit;
 	    }
		if(empty($header['Apikey']))
		{
				$message['success'] = false; 
				$message['message'] =  'Missing Apikey in header.';
				echo json_encode($message); exit;
		}
		if(!$this->isJson($request_data))
		{
				$error = json_last_error_msg();
				$message['success'] = false; 
				$message['message'] =  'Not valid JSON string ($error)';
				echo json_encode($message); exit;
				//echo "Not valid JSON string ($error)";exit;
		} 

		if(!isset($request_data) && empty($request_data))
		{
		    $message['success'] = false; 
		    $message['message'] =  'Missing request data.';
		    echo json_encode($message); exit;
		}
			
		if(!array_key_exists('email',$request_data))
		{
			$message['success'] = false; 
			$message['message'] =  'Please declare parameter of email in Request Body';
			echo json_encode($message); exit;
		}
		if(!array_key_exists('roleId',$request_data))
		{
			$message['success'] = false; 
			$message['message'] =  'Please declare parameter of roleId in Request Body';
			echo json_encode($message); exit;
		}
		
		if(empty($request_data['email'])){
					$message['success'] = false; 
					$message['message'] =  'email value empty not allowed in Request Body';
					echo json_encode($message); exit;
		}	
		if(empty($request_data['roleId'])){
					$message['success'] = false; 
					$message['message'] =  'roleId value empty not allowed in Request Body';
					echo json_encode($message); exit;
		}
		
	    $token = $header['Apikey'];
		$email = $request_data['email'];
		$roleId = $request_data['roleId'];
	
		if($roleId==4){
			$type = 'driver';
		}
		else if($roleId==3){
			$type = 'customer';
		}
		else{
			$message['success'] = false; 
		    $message['message'] =  'Sorry,Only Driver and Customer Allow to Login.';
		    echo json_encode($message); exit;
		}
		//echo $token;
		//echo $type;
		$token_result = $this->general_model->verify_token($token,$type);   
		if($token_result['result']==0){
			$message['success'] = false; 
			$message['message'] =  'Invalid API token, Please Check Apikey.';			
			echo json_encode($message); exit;	
		}
		else{
	
			$result = $this->general_model->get_customer_driverdetails($email,$roleId);
			//print_r($result);exit;
			if(!empty($result)){
				if($result[0]->RoleId==3){
					$result[0]->ProfileImage = base_url().$result[0]->ProfileImage;
					$result[0]->DocumentImage = base_url().$result[0]->DocumentImage;
				}
				$message['success'] = 'true'; 
				$message['message'] = 'Account Switch Successfully.';	
				$message['result'] =  $result;			
				echo json_encode($message); exit;
			}
			else{
				if($roleId==4){					
					$message['message'] = 'No customer data available.';
				}
				else if($roleId==3){
					$message['message'] = 'No driver data available.';
				}
				else{
					$message['message'] = 'Something goes wrong.';
				}
				$message['success'] = 'false';
				echo json_encode($message); exit;
			}	
			
		}
				
		
    }
     
}
    

?>