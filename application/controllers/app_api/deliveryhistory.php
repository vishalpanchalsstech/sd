<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class deliveryhistory extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->model('deliveryhistory_model');
        $this->load->model('general_model');
	 }
	
	public function deliveries()
	{
		$apiKey = $this->input->post('Apikey');
		$type = "driver";
		
		/* start verify the apitoken with driver token weather exist in db or not */
			
			$token_result = $this->general_model->verify_token($apiKey,$type);
			//echo "<pre>";print_r($token_result);exit();
			if($token_result['result']==0)
			{
			   $message['success'] = false; 
			   $message['message'] =  'Invalid API token, Please Check apikey.';   
			   echo json_encode($message); exit; 
			}
		
		$DriverUserId = $token_result['data'][0]->UserId;
		
		if(!empty($apiKey))
		{ 
			$driver_delivery_listing = $this->deliveryhistory_model->Get_deliveryhistory($apiKey,$DriverUserId);
			

			$listing = $driver_delivery_listing['result'];
			$deliverys_result = $driver_delivery_listing['data'];
			
			
			if($listing == 1)
			{
				//$status_array = array('Rejected','Canceled','Accepted','Inprogress','Completed');
				$status_array = array();
				$status_array_data = array('Rejected','Created','Canceled','Accepted','Inprogress','Completed');
				foreach($deliverys_result as $data)
				{
					$data->AcceptedDriverId = json_decode($data->AcceptedDriverId);
					$data->CancelDriverId = json_decode($data->CancelDriverId);
					$data->PickupDetail = json_decode($data->PickupDetail);
					$data->DropoffDetail = json_decode($data->DropoffDetail);
					$data->Distance = json_decode($data->Distance);
					$data->Duration = json_decode($data->Duration);
					$data->DistanceStatus = json_decode($data->DistanceStatus);
					//echo "<pre>";print_r($data->AcceptedDriverId);exit;
					$status = $data->JobStatus;
					$data->JobStatus = $status_array_data[$data->JobStatus];
					
					switch($status) 
					{
						 case 1:
						  $status_array['Rejected'][] = $data;
						  break;
						 case 2:
						  $status_array['Canceled'][] = $data;
						  break;
						 case 3:
						  $status_array['Accepted'][] = $data;
						  break;
						 case 4:
						  $status_array['Inprogress'][] = $data;
						  break;
						 case 5:
						  $status_array['Completed'][] = $data;
						  break;
					} 
				}
					//echo "<pre>";print_r($status_array);exit;
					$msgch ="Data Found.";
					$arr_json['success'] = "1";
					$arr_json['message'] = $msgch;
					$arr_json['RESULT'] = $status_array;
			}
			else
			{
				$msgch ="Data not Available.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="Plz Enter A valide API Key.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		//print_r($arr_json);exit;
		echo json_encode($arr_json,JSON_UNESCAPED_SLASHES);
	}
	
	
}
