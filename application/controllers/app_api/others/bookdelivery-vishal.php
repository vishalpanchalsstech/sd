<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// {
//     "apiKey": "MERCHANT_KEY",
//     "booking":{
//         "pickupDetail": {
//             "name": "Rupert",
//             "phone": "1234567890",
//             "address": "57 luscombe st, brunswick, melbourne"
//         },
//         "dropoffDetail": {
//             "name": "Igor",
//             "phone": "0987654321",
//             "address": "105 collins st, 3000"
//         }
//     }
// }            
class Bookdelivery extends CI_Controller {    
    function __construct() {
        parent::__construct();
        $this->load->model('companymaster_model');
        header("Access-Control-Allow-Origin: *");
    }
    public function index()
    {
		
	    // $headers = apache_request_headers();
		// $hdr = array();
		$data = json_decode(file_get_contents('php://input'),true);
		// print_r($data['booking']['pickupDetail']['address']);
		// print_r($data);exit;
		
		if(!$this->isJson($data)){
				$error = json_last_error_msg();
				echo "Not valid JSON string ($error)";exit;
		}	
				
		if(empty($data['apiKey'])){
				$message['success'] = false; 
				//$message['message'] =  'Please enter access key in header';
				$message['message'] =  'Please enter apiKey as Label in Header';
				echo json_encode($message); exit;
		}
		else{
				$token =$data['apiKey'];	
		}
		
			/*** start verify the apitoken with company token weather exist in db or not ***/
			$company_result = $this->companymaster_model->GetCompanyByTokenPreference($token);
		
			if(empty($company_result)){
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check apikey.';
				echo json_encode($message); exit;	
			}
			/*** end verify token ***/
			
			

			$to = $data['booking']['pickupDetail']['address'];
			$from = $data['booking']['dropoffDetail']['address'];
			// $from = "sr nagar,hyderabad";
			// $to = "kukatpalle,hyderabad";
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
			if(isset($data->status)){
				//echo '<pre>';print_r($data);
				if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0])){
					$time = 0;
					$distance = 0;
					foreach($data->rows[0]->elements as $road) {
					    //$time += $road->duration->value;
					    //$distance += $road->distance->value;
					    if(isset($road->duration)){
						$time      = $road->duration->text;
						}
						if(isset($road->distance)){
						$distance  = $road->distance->text;
						}
						if(isset($road->status)){
							$status = $road->status;
						}
					}		
					echo "To: ".$data->destination_addresses[0];
					echo "<br/>";
					echo "From: ".$data->origin_addresses[0];
					if(isset($road->duration)){
					echo "<br/>";
					echo "Time: ".$time;//." seconds";
					}
					if(isset($road->duration)){
					echo "<br/>";		
					echo "Distance: ".$distance;//." meters";
					}
					if(isset($road->status)){
					echo "<br/>";		
					echo "status: ".$status;//." meters";
					}
					//Note : above you need to km and time h:m format just replace with
					//$time      = $road->duration->text;
					//$distance  = $road->distance->text;
				}
				else{
				//echo $data->rows[0]->elements[0]->status;
					echo "Invalid address or request";
				}
				
			}
			else{
				echo "Invalid address or request";
			}
			
			
			
    }
   
	public function isJson($json){	
		return (json_last_error() == JSON_ERROR_NONE);
	}
	public function get_customer_details($Access_Key){
		if(empty($Access_Key)){
			$Access_Key = '';
		}
		$query = $this->db->query("select * from customers where apiToken LIKE'%".$Access_Key."%' ");
		$customer_result = $query->result();
		return $customer_result;
	}
	
}
