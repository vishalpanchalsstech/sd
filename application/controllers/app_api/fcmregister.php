<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class fcmregister extends CI_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();		    
        $this->load->model('login_model');
        $this->load->model('general_model');
    }

    /*** register device for push notification @v**********/
    public function fcmadd()
    { 
      
      /* header 
        Content-Type:application/json
      Apikey:SST100000000003  
      */

      /*
        {         
        "fcmregid": "d3eFZ3oYAmw:APA91bHXNDpTm8wF4VpPv5AktemjRj3151Py3HQkeLRbpXQ9HwKUVrZTA-So82cfI1ZTYidUtib-2HEaaNKQSBp0nj_HI7748WBdJM3pIzLngc12mUUnZC9SSHuUn8mhI3opaAKg_zH0",
          "deviceuniquid":"43ijyhe78eyektmf90u4et43wtj9403tj943tj3oit930u3",
          "devicetype":"IOS/ANDROID",
          "usertype":"customer/driver"          
        } 
      */

      
      $request_data = json_decode(file_get_contents('php://input'),true);
      $header =getallheaders();
      
      /****** Check the header and request type and body @v *******/
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

      if(empty($header['Apikey'])){
          $message['success'] = false;          
          $message['message'] =  'Please enter Apikey as Label in Header';
          echo json_encode($message); exit;
      }
      else{
          $token =$header['Apikey'];  
      } 
      $requestby= $token; 
      
      if(!$this->isJson($request_data)){
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
      
      
      $currentdt= date('Y-m-d h:i:s');
      $starttime = new DateTime($currentdt);
      
      if(empty($request_data['fcmregid'])){
          $message['success'] = false; 
          $message['message'] = 'Required field fcmregid in request body.';
          echo json_encode($message); exit;
      }
      else{
          $fcmregid =$request_data['fcmregid']; 
      }

      if(empty($request_data['deviceuniquid'])){
          $message['success'] = false; 
          $message['message'] = 'Required field deviceuniquid in request body.';
          echo json_encode($message); exit;
      }
      else{
          $deviceuniquid =$request_data['deviceuniquid']; 
      }

      if(empty($request_data['devicetype'])){
          $message['success'] = false; 
          $message['message'] = 'Required field devicetype in request body.';
          echo json_encode($message); exit;
      }
      else{
          $devicetype =$request_data['devicetype']; 
      }

      if(empty($request_data['usertype'])){
          $message['success'] = false; 
          $message['message'] = 'Required field usertype in request body.';
          echo json_encode($message); exit;
      }
      else{
          $usertype =$request_data['usertype']; 
      }           

      $data = $this->general_model->verify_token($token,$usertype);
      
      $result = $data['result'];
      if($result == 0){
              $arr_json['success'] = "0";
              $arr_json['message'] = "Invalide Token.";
              echo json_encode($arr_json);exit;
      }
      /*** end verify token @v***/
	   
      // print_r($data);exit;
		  $UserId = $data['data'][0]->UserId;

		  $query = $this->login_model->fmcregister($UserId,$fcmregid,$deviceuniquid,$devicetype);
		  $resultch = $query['result'];
		  $msgch = $query['message'];
		 
			if ($resultch == '1')
      {
	     
	        $arr_json['success'] = "1";
          $arr_json['message'] = $msgch;
         
      }
      else
      {
          $arr_json['success'] = "0";
          $arr_json['message'] = $msgch;
      }
		
		  echo json_encode($arr_json); 
  
	}
	
	    function fcmremove()
    	{        

       $UserId= $this->input->get('userid');
       $FCMRegId = "";
       $DeviceUniquId = "";
       $DeviceType = "";
      
       if(!empty($UserId)){
          
           $query= $this->login_model->fmcregister($UserId,$FCMRegId,$DeviceUniquId,$DeviceType);
           $resultch = $query['result'];
           $msgch = $query['message'];
         
            if ($resultch == '1')
            {
                
                $arr_json['success'] = "1";
                $arr_json['message'] = "Firebase Details Updated Successfully";
               
            }
            else
            {
                $arr_json['success'] = "0";
                $arr_json['message'] = $msgch;
            }
          
           
        }else{
            $msgch ="Something gone wrong. Please Try again.";
            $arr_json['success'] = "0";
            $arr_json['message'] = $msgch;
        }
        echo json_encode($arr_json); 
  
    }

    /****** Validate json *********/
    public function isJson($json){  
      return (json_last_error() == JSON_ERROR_NONE);
    }
}   

?>