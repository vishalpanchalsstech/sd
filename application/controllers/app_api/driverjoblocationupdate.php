<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class driverjoblocationupdate extends CI_Controller 
	{
		function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->library('session');
			$this->load->library('upload');
			$this->load->model('drivermaster_model');
			$this->load->model('general_model');
		}
	
		function updatedriverlocation()
		{
				$apiKey = $this->input->post('Apikey');
				$jobid=$this->input->post('JobId');
				$latitude=$this->input->post('latitude');
				$longitude=$this->input->post('longitude');
				
				$type = 'driver';
				if(!empty($apiKey)){
					$data = $this->general_model->verify_token($apiKey,$type);
					
					$result = $data['result'];
					if($result == 0){
							$msgch ="Invalide Token.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
							echo json_encode($arr_json);exit;
					}
					else{
						$DriverUserId = $data['data'][0]->UserId;
					}
				}else{
					$msgch = "Missing apikey token."; 
					$arr_json['success'] = "0"; 
					$arr_json['message'] =  $msgch;	
					echo json_encode($arr_json);exit;
				}
				
				if(!empty($jobid) && !empty($latitude) && !empty($longitude)){
					
					
					/**check jobid and driverid in tracking table*/
					$driverjoblocation_result = $this->drivermaster_model->jobexist_tracking($DriverUserId,$jobid);
					if(!empty($driverjoblocation_result)){
						$location_array=array(
										'latitude'=>$latitude,
										'longitude'=>$longitude
						);
						$jsonencode_location = json_encode($location_array);
						$location = array('CurrentLocation'=>$jsonencode_location);
						$jobid = $driverjoblocation_result[0]->JobId;
						$DriverId = $driverjoblocation_result[0]->DriverId;
						$updatejob_location = $this->drivermaster_model->update_joblocation($location,$jobid,$DriverId);
						if($updatejob_location==1){
							$driverjoblocation_result = $this->drivermaster_model->jobexist_tracking($DriverUserId,$jobid);
							
							$driverjoblocation_result[0]->CurrentLocation = json_decode($driverjoblocation_result[0]->CurrentLocation);
							$driverjoblocation_result[0]->PickupDetail = json_decode($driverjoblocation_result[0]->PickupDetail);
							$driverjoblocation_result[0]->DropoffDetail = json_decode($driverjoblocation_result[0]->DropoffDetail);
							$driverjoblocation_result[0]->StartLocation = json_decode($driverjoblocation_result[0]->StartLocation);
							
							$arr_json['success'] = 'true';
							$arr_json['result'] = $driverjoblocation_result;
							echo json_encode($arr_json);exit;
						}
						
					}
					else{
						$arr_json['success'] = 'false';
						$arr_json['message'] = "Job tracking data not found.";
						echo json_encode($arr_json);exit;
					}
					
				}	
				else{
						$arr_json['success'] = 'false';
						$arr_json['message'] = "All Field Are Required.";
						echo json_encode($arr_json);exit;
				}
			
		}
	}
