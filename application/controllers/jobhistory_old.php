<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class jobhistory extends CI_Controller{
		function __construct(){
			parent::__construct();
			
			$this->load->model('jobhistorymaster_model');
			$this->load->model('general_model');
			$general = $this->load->library('../controllers/general');
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');				
				$this->general->AccessPermission();
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
			//$data['EditData'] = $this->usermaster_model->edit_data($Id);
			$data['GetJobHistoryData'] = $this->jobhistorymaster_model->GetJobHistoryData();
			$data['section'] = array('jobhistoryrmaster_view');
			$this->general->load_my_view($data);
		}
		public function jobhistory_table(){
			$data = $this->general->check_current_session();
			$roleid = $data['roleid'];
			$jobhistory_data= $this->jobhistorymaster_model->GetJobHistoryData();
			//echo '<pre>';print_r($jobhistory_data);exit;
			if(isset($jobhistory_data))
			{
				for($i=0;$i<count($jobhistory_data);$i++)
				{
					
					$jobhistory_data[$i]->Id;
					$jobhistory_data[$i]->JobId;
					$jobhistory_data[$i]->Company;
					$jobhistory_data[$i]->UserName;
					$jobhistory_data[$i]->PickupDetail;
					$jobhistory_data[$i]->DropoffDetail;
					$jobhistory_data[$i]->Distance;
					$jobhistory_data[$i]->Duration;
					$jobhistory_data[$i]->DistanceStatus;
					$jobhistory_data[$i]->Consignment;
					$jobhistory_data[$i]->AcceptedDriverId;
					$jobhistory_data[$i]->RejectDriverId;
					$jobhistory_data[$i]->CancelDriverId;
					$jobhistory_data[$i]->JobStatus;
					$jobhistory_data[$i]->CreatedAt;
					
					/*Start This code for the Get JobStatus through Jobstatus Id*/
					$jobstatus_result = $this->jobhistorymaster_model->get_job_status($jobhistory_data[$i]->JobStatus);
					foreach($jobstatus_result as $jobstatus){
						if($jobstatus->StatusName=="Created"){
							$jobhistory_data[$i]->JobStatus = '<span class="label label-Created" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Canceled"){
							$jobhistory_data[$i]->JobStatus = '<span class="label label-Canceled" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Accepted"){
							$jobhistory_data[$i]->JobStatus = '<span class="label label-Accepted" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Inprogress"){
							$jobhistory_data[$i]->JobStatus = '<span class="label label-Inprogress" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Completed"){
							$jobhistory_data[$i]->JobStatus = '<span class="label label-Completed" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}else{
							$jobhistory_data[$i]->JobStatus = '<span class="label label-info" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}
						
						//$jobhistory_data[$i]->JobStatus = $jobstatus->StatusName;
					}
					/*End This code for the Get JobStatus through Jobstatus Id*/
					
					/*Start This code for the Get Email for user through CreatedBy Id*/
					$createdby = $this->jobhistorymaster_model->get_customer_details($jobhistory_data[$i]->CreatedBy);
					foreach($createdby as $crtedby){
						$jobhistory_data[$i]->CreatedBy = $crtedby->Email;
					}
					/*End This code for the Get Email for user through CreatedBy Id*/
					
					/*Start This code for the Get Email for user through DeletedBy Id*/
					$deletedby = $this->jobhistorymaster_model->get_customer_details($jobhistory_data[$i]->DeletedBy);
					foreach($deletedby as $dltdby){
						$jobhistory_data[$i]->DeletedBy = $dltdby->Email;
					}
					/*End This code for the Get Email for user through DeletedBy Id*/
					
					if($roleid==1){
						$jobhistory_data[$i]->Action = "<a href='".base_url()."jobhistory/Edit/".$jobhistory_data[$i]->Id."'><button class='btn btn-default btn-rounded btn-sm'><span class='fa fa-pencil'></span></button></a>"."<button data-target='#mb-remove-row' data-toggle='modal' class='btn btn-danger btn-rounded btn-sm' id='delete-data' controller='".base_url()."jobhistory/Delete'  onclick=delete_row_new('".$jobhistory_data[$i]->Id."')> <span class='fa fa-times'></span> </button>";
					}
					//$jobhistory_data[$i]->Action .= 
					//$jobhistory_data[$i]->Edit = "<i class='fa fa-fw fa-edit'></i><a href='".base_url()."retailer/edit_retailer/".$jobhistory_data[$i]->Id."'>Edit</a>";
					//$jobhistory_data[$i]->Delete = "<i class='fa fa-fw fa-edit'></i><a href='".base_url()."retailer/edit_retailer/".$jobhistory_data[$i]->Id."'>Delete</a>";
					
					
				}
			}
			echo json_encode(array('data'=>$jobhistory_data),JSON_PRETTY_PRINT);	
		}
		
		public function jobhistorysimpleform(){
			$data = $this->general->check_current_session();
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$data['roleid'];
			$data['section'] = array('jobhistorymaster_insert');
			$this->general->load_my_view($data);
		}
		public function jobhistoryadvanceform(){
			$data = $this->general->check_current_session();
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$data['roleid'];
			$data['section'] = array('jobhistorymaster_advance_insert');
			$this->general->load_my_view($data);
		}

		public function insert(){
			
			$session_data = $this->session->userdata('logged_in');
			$user_id = $session_data['userid'];
			$jobstatus = 1;
			$CompanyId=$this->input->post('CompanyId');
			
			/*Start Calculate Distance and Duration */
			$to = $this->input->post('pickupaddress');
			$from = $this->input->post('dropoffaddress');
			$add = $this->address_validation($to,$from);
			if($add==false){
				$status=false;
				echo json_encode($status,JSON_PRETTY_PRINT);exit;
				
			}
			
			else{
				$from = urlencode($from);
				$to = urlencode($to);
				$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
				$data = json_decode($data);
			
			/*$status='';
			foreach($data->rows[0]->elements as $road) {
						$status = $road->status;
						
			}
			if($status!='OK'){
				$job_status=false;
				//echo json_encode(array('status',$job_status),JSON_PRETTY_PRINT);exit;
				echo 'false';exit;
			}*/
			
			//else{			
					if(isset($data->status)){
						if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0])){
							$time = 0;
							$distance = 0;
							foreach($data->rows[0]->elements as $road) {
								if(isset($road->distance)){
								$distance = $road->distance;
								}else{$distance=null;}
								if(isset($road->duration)){
								$duration = $road->duration;
								}else{$duration=null;}
								$status = $road->status;
							}
							$distance = json_encode($distance);
							$duration = json_encode($duration);
						}
					}
					/*End Calculate Distance and Duration */
					
					$pickupDetail = array(
					  'name'=>$this->input->post('pickupname'),
					  'phone'=>$this->input->post('pickupphone'),
					  'address'=>$this->input->post('pickupaddress')
					);
					$pickupDetail = json_encode($pickupDetail);
					
					$dropoffDetail = array(
					  'name'=>$this->input->post('dropoffname'),
					  'phone'=>$this->input->post('dropoffphone'),
					  'address'=>$this->input->post('dropoffaddress')
					);
					$dropoffDetail = json_encode($dropoffDetail);
					$jobstatus = $this->input->post('jobstatus');
					
					$JobQuery="SELECT JobId FROM jobmaster ORDER BY Id DESC LIMIT 0,1";
					$execute=$this->db->query($JobQuery);
					$JobResult=$execute->result(); 
					$JobId='';
					foreach($JobResult as $JobResultValue){
						$JobId=$JobResultValue->JobId;
					}
					$exp = explode("SSTVJOB",$JobId);
					$exp_job='';
					if(isset($exp[1])){
					 //$exp[1] = null;	
					 $exp_job = $exp[1]+1;
					}
					$JobId1 = $exp_job;
					if($JobId1 < 10000){
						$JobNumberValues=10001;
						$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
					else{
					$exp = explode("SSTVJOB",$JobId);
					$exp_job = $exp[1]+1;
					$JobNumberValues=$exp_job;
					$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
							
					$insert_array=array(
						'JobId'=>$GenerateJobId,
						'CompanyId'=>$CompanyId,
						'UserId'=>$user_id,
						'pickupDetail'=>$pickupDetail,
						'dropoffDetail'=>$dropoffDetail,
						'JobStatus'=>$jobstatus,
						'Distance'=>$distance,
						'Duration'=>$duration,
						'CreatedVia'=>'DataEntry',
						'CreatedBy'=>$user_id,
						'DeletedBy'=>$user_id
					);
					$query=$this->jobhistorymaster_model->insert_data($insert_array);
					if($query==1)
					{
						$msg='<div class="alert alert-success" role="alert"><strong>Data inserted sucessfully.</strong></div>';					
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
					$flash_data = $this->session->set_flashdata('my_msg', $msg);
					redirect('jobhistory');
				
				}
		}
		
		
		public function address_validation($to,$from){
			
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
			
			$status='';
			foreach($data->rows[0]->elements as $road) {
						$status = $road->status;
						
			}
			if($status!='OK'){
				$job_status=false;
				
			}
			else{
				$job_status=true;
			}
			return $job_status;
		}
		
		/*public function test($to,$from){
			
			
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?destinations=$to&language=en-EN&sensor=false");
			echo '<pre>';print_r($data);exit;
			
			
		}*/
		
		
		public function Edit($Id){
			$data = $this->general->check_current_session();
			$data['roleid'];
			$data['EditData'] = $this->jobhistorymaster_model->edit_data($Id);
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			//echo '<pre>';print_r($data['EditData']);exit;
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['section'] = array('jobhistorymaster_insert');
			$this->general->load_my_view($data);
		}
	
		public function update(){
			$session_data = $this->session->userdata('logged_in');		
			$user_id = $session_data['userid'];
			$CompanyId=$this->input->post('CompanyId');
			$UpdateId=$this->input->post('UpdateId');
			$timestamp = date('Y-m-d H:i:s');
			
			/*Start Calculate Distance and Duration */
			$to = $this->input->post('pickupaddress');
			$from = $this->input->post('dropoffaddress');
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
			if(isset($data->status)){
				if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0])){
					$time = 0;
					$distance = 0;
					foreach($data->rows[0]->elements as $road) {
						if(isset($road->distance)){
						$distance = $road->distance;
						}else{$distance=null;}
						if(isset($road->duration)){
						$duration = $road->duration;
						}else{$duration=null;}
						$status = $road->status;
					}
					$distance = json_encode($distance);
					$duration = json_encode($duration);
				}
			}
			/*End Calculate Distance and Duration */
			
			
			$pickupDetail = array(
			  'name'=>$this->input->post('pickupname'),
			  'phone'=>$this->input->post('pickupphone'),
			  'address'=>$this->input->post('pickupaddress')
			);
			$pickupDetail = json_encode($pickupDetail);
			
			$dropoffDetail = array(
			  'name'=>$this->input->post('dropoffname'),
			  'phone'=>$this->input->post('dropoffphone'),
			  'address'=>$this->input->post('dropoffaddress')
			);
			$dropoffDetail = json_encode($dropoffDetail);
			$jobstatus = $this->input->post('jobstatus');
			
			$update_array=array(
				'CompanyId'=>$CompanyId,
				'UserId'=>$user_id,
				'pickupDetail'=>$pickupDetail,
				'dropoffDetail'=>$dropoffDetail,
				'JobStatus'=>$jobstatus,
				'Distance'=>$distance,
				'Duration'=>$duration,
				'UpdatedAt'=>$timestamp
			);
			//echo '<pre>';print_r($update_array);exit;
			$query=$this->jobhistorymaster_model->update_data($update_array,$UpdateId);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Data Updated sucessfully.</strong></div>';					
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('jobhistory');
		}
		public function Delete($Id){
			$session_data = $this->session->userdata('logged_in');
			$query=$this->jobhistorymaster_model->Delete_data($Id);
			if($query==1){
				$msg='<div class="alert alert-success" role="alert"><strong>Data deleted sucessfully.</strong></div>';
			}
			else{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('jobhistory');
		}
		
	}
?>