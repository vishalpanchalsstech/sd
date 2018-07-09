<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// {
//     "apiKey": "MERCHANT_KEY",
//	   "Email": "sstech.umesh@gmail.com",
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
        $this->load->model('general_model');
        $this->load->model('usermaster_model');
        header("Access-Control-Allow-Origin: *");
    }
	
    public function index()
    {
		
			// $headers = apache_request_headers();
			// $hdr = array();
			$request_data = json_decode(file_get_contents('php://input'),true);
			
			if(!$this->isJson($request_data)){
					$error = json_last_error_msg();
					$message['success'] = false; 
					$message['message'] =  'Not valid JSON string ($error)';
					echo json_encode($message); exit;
					//echo "Not valid JSON string ($error)";exit;
			}	
			
			if(empty($request_data['email'])){
					$message['success'] = false; 
					$message['message'] =  'Please enter parameter email';
					echo json_encode($message); exit;
			}
			else{
					$email =$request_data['email'];	
			}
			
			if(empty($request_data['apiKey'])){
					$message['success'] = false; 
					//$message['message'] =  'Please enter access key in header';
					$message['message'] =  'Please enter apiKey as Label in Header';
					echo json_encode($message); exit;
			}
			else{
					$token =$request_data['apiKey'];	
			}
	
			/*Validation for Key the PickupDetail and DropoffDetail in Request Body*/
			$required = array('name', 'phone', 'address');
			if(count(array_intersect_key(array_flip($required),$request_data['booking']['pickupDetail'])) !== count($required)) {
				$message['success'] = false; 
				$message['message'] =  'Please Required Parameter of PickupDetail in Request Body';
				echo json_encode($message); exit;
			}
			if (count(array_intersect_key(array_flip($required),$request_data['booking']['dropoffDetail'])) !== count($required)) {
				$message['success'] = false; 
				$message['message'] =  'Please Required Parameter of DropoffDetail in Request Body';
				echo json_encode($message); exit;
			}
		
			/*PickupDetail Request Body Some Value Empty Check*/
			foreach($request_data['booking']['pickupDetail'] as $key=>$value){
				 $value = trim($value);
				 if (empty($value)){
					 $message['success'] = false; 
					 $message['message'] =  'Please Declare Value of PickupDetail in Request Body';
					 echo json_encode($message); exit;
				 }
			}
			
			/*DropoffDetail Request Body Some Value Empty Check*/
			foreach($request_data['booking']['dropoffDetail'] as $key=>$value){
				 $value = trim($value);
				 if (empty($value)){
					 $message['success'] = false; 
					 $message['message'] =  'Please Declare Value of DropoffDetail in Request Body';
					 echo json_encode($message); exit;
				 }
			}
			
			/*** start verify the email with user table email weather exist in db or not ***/
			$customer_emailverify_result = $this->usermaster_model->customer_email_verify($email);
			if(empty($customer_emailverify_result)){
				/*if email not exist then create customer*/
				$original_password = $this->general_model->random_password(8);
				$create_customer = $this->create_customer($email,$original_password);
				$get_last_customer_details = $this->get_customer_data($create_customer); 
				$email = $get_last_customer_details[0]->Email;
				$password = $get_last_customer_details[0]->Password;
				if($create_customer!=0){
					$customername = explode('@',$email);
					$customername = $customername[0];
					$to = $email;
					$from = '';		
					$cc = '';
					$subject = 'SSTech Customer Created.';
					$body="
					 <div >
									<p>Hello $customername</p>
									<p>Thank you so much for allowing us to help you with your logistic needs. We are committed to provide our customers with the highest level of service and live tracking of your parcel using our iphone APP.</p>
									<p>Please download our app using [link]. Use the following credential to log in.</p>
									<table>
									<tr>
									<td><label>Email/Username :</label></td>
									<td><label type='text' style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;' name='email'>$email</label></td>
									</tr>
									<tr>
										<td><label>Password :</label></td>
										<td><label type='text'  style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;'  name='password'>$original_password</label></td>
									</tr>
									</table>
									<p>For more detailed information about any of our products or services, please refer to our website, www.sstechdriver.com, or visit any of our convenient locations.  </p>
									<p>Please do not hesitate to contact us, should you have any questions. We will contact you in the very near future to ensure you are completely satisfied with the services you have received thus far. </p>
									<p>Regards,</p>
									<p>SSTECH DRIVER APP</p>
					 </div>
					";
					$mail_sent = $this->general_model->mail_setup($to,$cc,$from,$subject,$body);
					if($mail_sent==1){
						$booking['EmailStatus'] = 'Email Sent Succesfully.';
					}
				}
			}
			//print_r($customer_emailverify_result);exit;
			
			/*** start verify the apitoken with company token weather exist in db or not ***/
			$company_result = $this->companymaster_model->GetCompanyByTokenPreference($token);
		
			if(empty($company_result)){
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check apikey.';
				echo json_encode($message); exit;	
			}
			/*** end verify token ***/
			$to = $request_data['booking']['pickupDetail']['address'];
			$from = $request_data['booking']['dropoffDetail']['address'];
			
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
						$time = $road->duration->text;
						}
						if(isset($road->distance)){
						$distance  = $road->distance->text;
						}
						if(isset($road->status)){
							$status = $road->status;
						}
					}		
					
					$job_status='';
					if(!empty($time) && !empty($distance)){
						$job_status=1;
					}else{
						$job_status=0;
					}
					
					$company_id = $company_result[0]->CompanyId;
		
					foreach($data->rows[0]->elements as $road) {
						//$key['row']=$val;
						if(isset($road->distance)){
						$distance = $road->distance;
						}else{$distance=null;}
						if(isset($road->duration)){
						$duration = $road->duration;
						}else{$duration=null;}
						$status = $road->status;
					}
					
					$JobQuery="SELECT JobId FROM currjob ORDER BY Id DESC LIMIT 0,1";
					$execute=$this->db->query($JobQuery);
					$JobResult=$execute->result(); 
					$JobId='';
					foreach($JobResult as $JobResultValue){
						$JobId=$JobResultValue->JobId;
					}
					$exp = explode("CJB",$JobId);
					$exp_job='';
					if(isset($exp[1])){
					 //$exp[1] = null;	
					 $exp_job = $exp[1]+1;
					}
					$JobId1 = $exp_job;
					if($JobId1 < 10000){
						$JobNumberValues=10001;
						$GenerateJobId= "CJB".$JobNumberValues;
					}
					else{
					$exp = explode("CJB",$JobId);
					$exp_job = $exp[1]+1;
					$JobNumberValues=$exp_job;
					$GenerateJobId= "CJB".$JobNumberValues;
					}
					
					$pickupDetail['pickupDetail'][] = $data->destination_addresses;
					$dropoffDetail['dropoffDetail'][] = $data->origin_addresses;
					
					/*Get UserId through the Email*/
					$userdetails = $this->usermaster_model->customer_email_verify($email);
					if(isset($userdetails[0]->Id)){
						$userid = $userdetails[0]->Id;
					}else{ $userid='';}
					
					$insert_array = array(
					
						'UserId'=>$userid,
						'JobId'=>$GenerateJobId,
						'CompanyId'=>$company_id,
						'pickupDetail' =>json_encode($request_data['booking']['pickupDetail']),
						'dropoffDetail' =>json_encode($request_data['booking']['dropoffDetail']),
						'Distance' =>json_encode($distance),
						'Duration' =>json_encode($duration),
						'DistanceStatus' =>json_encode($status),
						'JobStatus'=>$job_status
					);
					
					
					$currenjob_insert_data = $this->general_model->insert_currentjob_data($insert_array);
					if($currenjob_insert_data==0){
						
						if(empty($time) && empty($distance)){
							$message['success'] = false; 
							$message['message'] =  'Error:Zero Result Found.';
							echo json_encode($message); 
						}
					}	
					else{
							$get_currjob_details = $this->general_model->get_currjob_data($currenjob_insert_data);
							$get_currjob_details[0]->pickupDetail = json_decode($get_currjob_details[0]->pickupDetail,true);
							$get_currjob_details[0]->dropoffDetail = json_decode($get_currjob_details[0]->dropoffDetail,true);
							$get_currjob_details[0]->Distance=json_decode($get_currjob_details[0]->Distance,true);
							$get_currjob_details[0]->Duration=json_decode($get_currjob_details[0]->Duration,true);
							$get_currjob_details[0]->DistanceStatus=json_decode($get_currjob_details[0]->DistanceStatus,true);
							
							
							
							$booking['quote']['created'] = $get_currjob_details[0]->CreatedAt;
							$booking['quote']['distanceKm'] = $get_currjob_details[0]->Distance['text'];
							$booking['quote']['pickup']['time']['latest'] = $get_currjob_details[0]->Duration['text'];
							$booking['quote']['pickup']['address']= $get_currjob_details[0]->pickupDetail['address'];
							
							$booking['quote']['dropoff']['time']['latest'] = $get_currjob_details[0]->Duration['text'];
							$booking['quote']['dropoff']['address']= $get_currjob_details[0]->dropoffDetail['address'];
							
							$booking['delivery']['created'] = $get_currjob_details[0]->CreatedAt; 
							$booking['delivery']['id'] = $get_currjob_details[0]->Id; 
							$booking['delivery']['pickupLocation']['name'] = $get_currjob_details[0]->pickupDetail['name']; 
							$booking['delivery']['pickupLocation']['address'] = $get_currjob_details[0]->pickupDetail['address']; 
							$booking['delivery']['pickupLocation']['phone'] = $get_currjob_details[0]->pickupDetail['phone']; 
							
							$booking['delivery']['dropoffLocation']['name'] = $get_currjob_details[0]->dropoffDetail['name']; 
							$booking['delivery']['dropoffLocation']['address'] = $get_currjob_details[0]->dropoffDetail['address']; 
							$booking['delivery']['dropoffLocation']['phone'] = $get_currjob_details[0]->dropoffDetail['phone']; 
							
							$booking['delivery']['lastUpdated'] = $get_currjob_details[0]->UpdatedAt; 
							if($get_currjob_details[0]->JobStatus==1){
								$booking['delivery']['currentStatus'] = 'Success'; 	
							}
							else{
								$booking['delivery']['currentStatus'] = 'Failed'; 	
							}
							$booking['delivery']['pickupTime'] = $get_currjob_details[0]->Duration['text'];
							$booking['delivery']['dropoffTime']['latest'] = $get_currjob_details[0]->Duration['text'];
							
							$booking['request']['pickupTime'] =$get_currjob_details[0]->Duration['text'];
							$booking['request']['pickupDetail']['name'] = $get_currjob_details[0]->pickupDetail['name']; 
							$booking['request']['pickupDetail']['phone'] = $get_currjob_details[0]->pickupDetail['phone']; 
						    $booking['request']['pickupDetail']['address'] = $get_currjob_details[0]->pickupDetail['address'];
							
							$booking['request']['dropoffDetail']['name'] = $get_currjob_details[0]->dropoffDetail['name']; 
							$booking['request']['dropoffDetail']['phone'] = $get_currjob_details[0]->dropoffDetail['phone']; 
						    $booking['request']['dropoffDetail']['address'] = $get_currjob_details[0]->dropoffDetail['address'];
							
							echo json_encode($booking); 
					}
				}
				else{
					//echo "Invalid address or request";
					$message['success'] = false; 
					$message['message'] =  'Invalid address or request';
				}
			}
			else{
				// echo "Invalid address or request";
				$message['success'] = false; 
				$message['message'] =  'Invalid address or request';
			}
		
			echo json_encode($message);	
			
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
	
	/*Create Customer if not exist*/
	public function create_customer($email,$password){
		
		$explode = explode('@',$email);
		$name = $explode[0];
		//$password = $this->general_model->random_password(8);
		$token = $this->general_model->getToken(15);
		$roleid =4;
		$create_customer_arr = array(
			'Name'=>$name,
			'Email'=>$email,
			'Password'=> md5($password),
			'RoleId'=>$roleid,
			'Token'=>$token
		);
		$customer_result = $this->usermaster_model->insert_customer($create_customer_arr);
		return $customer_result;
	}
	
	/*Get Last Inserted Customer Details from Usermaster Table*/
	public function get_customer_data($last_id){
		$customer_result = $this->usermaster_model->get_customer_details($last_id);
		if(!empty($customer_result)){
			return $customer_result;
		}
	}
	
}
