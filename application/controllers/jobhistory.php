<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class jobhistory extends CI_Controller{
		function __construct(){
			parent::__construct();
			
			$this->load->model('jobhistorymaster_model');
			$this->load->model('usermaster_model');
			$this->load->model('customermaster_model');			
			$this->load->model('Job_model');
			$this->load->model('companymaster_model');
			$this->load->model('general_model');
			$this->load->model('priority_model');
			$general = $this->load->library('../controllers/general');
			require_once APPPATH."controllers/app_api/notifications.php";      
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
		public function index()
		{   
		    $data = $this->general->check_current_session();
			$roleid = $data['roleid'];
			$msg=$this->session->flashdata('my_msg');
			$data['msg'] =$msg;		
			//$data['EditData'] = $this->usermaster_model->edit_data($Id);
			$data['GetJobHistoryData'] = $this->jobhistorymaster_model->GetJobHistoryData($roleid);
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$StartJob =  date("Y-m-d");
			$data['StartJob'] = date("d-m-Y",strtotime($StartJob));
			$EndJob =  date("Y-m-d");
			$data['EndJob'] = date("d-m-Y",strtotime($EndJob));
			$data['section'] = array('jobhistoryrmaster_view');
			$this->general->load_my_view($data);
		}
		
		public function jobhistory_table()
		{
			$data = $this->general->check_current_session();
			$roleid = $data['roleid'];
			$email = $data['email'];
			
			$UserCompany = $this->jobhistorymaster_model->Get_loginUser_companyName($roleid,$email);
			//echo "<pre>";print_r($UserCompany);exit;
			$data['UserCompany'] = $UserCompany;
			$CompanyId = $UserCompany[0]->Id;
			
			$jobhistory_data= $this->jobhistorymaster_model->GetJobHistoryData($roleid,$CompanyId);
			//echo '<pre>';print_r($jobhistory_data);exit;
			
			
			
			if(isset($jobhistory_data))
			{
				for($i=0;$i<count($jobhistory_data);$i++)
				{
					//$jbid = "<input type='hidden' id='jid' value='".$jobhistory_data[$i]->JobId."'/>";
					//$jobhistory_data[$i]->mydata = $jbid;
					$jobhistory_data[$i]->popup_icon = '<a class="search-details" href="javascript:void(0)" 	onclick="get_jobhistory_details('.$jobhistory_data[$i]->Id.')"><i class="fa fa-search" aria-hidden="true"></i></a>';
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
					


					$jobhistory_data[$i]->Action ="";
					if($roleid==1 || $roleid==2)
					{
						$jobhistory_data[$i]->Action .= "<a href='".base_url()."jobhistory/Edit/".$jobhistory_data[$i]->Id."'><button class='btn btn-default btn-rounded btn-sm' style='margin-right:5px!important;'><span class='fa fa-pencil'></span></button></a>";
//						if($jobhistory_data[$i]->jobstatus==="Created" || $jobhistory_data[$i]->jobstatus==="Accepted")

						/**** check job has accepted after process or completed ****/
						$query = $this->db->query("SELECT * FROM `jobmaster` WHERE jobstatus=3 AND JobId='".$jobhistory_data[$i]->JobId."' AND JobId NOT IN (SELECT JobId FROM `jobmaster` WHERE  jobstatus IN('4','5'))");
				   		//echo $this->db->last_query();exit;				   		
				   		$result = $query->result();
				   		//print_r($result);exit;
						if($jobhistory_data[$i]->jobstatus==="Accepted" && !empty($result))
						{							
							$jobhistory_data[$i]->Action .= "<button data-target='#mb-cancel-row' style='margin-right:5px!important;' data-toggle='modal' class='btn btn-success fa fa-trash' controller='".base_url()."jobhistory/removedata/'  onclick=cancel_raw_detail('".$jobhistory_data[$i]->Id."')><span class='fa fa-times'></span></button>";
						}
						$jobhistory_data[$i]->Action .= "<button data-target='#mb-remove-row' data-toggle='modal' class='btn btn-danger btn-rounded btn-sm' id='delete-data' controller='".base_url()."jobhistory/Delete' data-id= '".$jobhistory_data[$i]->Id."' onclick=delete_row_new('".$jobhistory_data[$i]->Id."')> <span class='fa fa-times'></span></button>";
						
					}
				}
			}
			echo json_encode(array('data'=>$jobhistory_data),JSON_PRETTY_PRINT);	
		}
		
		
		
		
		
		
		
		/*For Modal Popup in JobHistory*/
		public function get_datamodal_jobhistory(){
			$data = $this->general->check_current_session();
			$id = $this->input->post('myid');
			$Get_Retun_Detail = $this->jobhistorymaster_model->Get_jobhistoryIdWiseDetails($id);
			
			
			if(!empty($Get_Retun_Detail)){
				$order_html='';
			
				$jobstatus_result = $this->jobhistorymaster_model->get_job_status($Get_Retun_Detail[0]->JobStatus);
					foreach($jobstatus_result as $jobstatus){
						if($jobstatus->StatusName=="Created"){
							$jobstatus = '<span class="label label-Created" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Canceled"){
							$jobstatus = '<span class="label label-Canceled" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Accepted"){
							$jobstatus = '<span class="label label-Accepted" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Inprogress"){
							$jobstatus = '<span class="label label-Inprogress" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Completed"){
							$jobstatus = '<span class="label label-Completed" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}else{
							$jobstatus = '<span class="label label-info" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}
						
						//$jobhistory_data[$i]->JobStatus = $jobstatus->StatusName;
					}
			
					/*Start This code for the Get Email for user through CreatedBy Id*/
					$createdby = $this->jobhistorymaster_model->get_customer_details($Get_Retun_Detail[0]->CreatedBy);
					//print_r($createdby);exit;
					if(!empty($createdby)){
						foreach($createdby as $crtedby){
							$CreatedBy = $crtedby->Email;
						}
					}else{
						$CreatedBy ='';
					}
					/*End This code for the Get Email for user through CreatedBy Id*/
					
					/*check scheduled time */
					if($Get_Retun_Detail[0]->ScheduleJobTime != '0000-00-00 00:00:00')
					{
						$schedulejob = $Get_Retun_Detail[0]->ScheduleJobTime;
						
						 $schedulejob = '<tr> 
											<td><b> Schedule Job   </b></td>
											<td> : &nbsp</td>
											<td> ' . $schedulejob.'</td>
										</tr>';
					}
					else{
						$schedulejob='';
					}
					
					
					/*Start This code for the Get Email for user through DeletedBy Id*/
					$deletedby = $this->jobhistorymaster_model->get_customer_details($Get_Retun_Detail[0]->DeletedBy);
					if(!empty($deletedby)){
						foreach($deletedby as $dltdby){
							$DeletedBy = $dltdby->Email;
						}
					}else{$DeletedBy ='';}
					/*End This code for the Get Email for user through DeletedBy Id*/
			
					$order_html .= '<table class="popup_view table-bordered table-condensed" width="100%!important;">  
						<tr> 
							<td><b> Id </b> </td>
							<td> : &nbsp</td>
							<td> '  .$Get_Retun_Detail[0]->Id .'</td>
						</tr>
					   <tr> 
							<td><b>Pickup Details  </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->PickupDetail.'</td>
					   </tr>
					   <tr> 
							<td><b> DropOff Details   </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->DropoffDetail.'</td>

					   </tr>
					   <tr> 
							<td><b> Distance   </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->Distance.'</td>

					   </tr>
					   <tr> 
							<td><b> Duration   </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->Duration.'</td>

					   </tr>
					   <tr> 
							<td><b> Distance Status  </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->DistanceStatus.'</td>
					   </tr>
					   <tr> 
							<td><b> Consignment   </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->Consignment.'</td>
					   </tr>
					   
					   '.$schedulejob.'
					   
					   <tr> 
							<td><b> Accepted Driver Details  </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->AcceptedDriverId.'</td>
					   </tr>
					   <tr> 
							<td><b> Reject DriverId Details </b> </td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->RejectDriverId.'</td>
					   </tr>
						<tr> 
							<td><b> Cancel Driver Details </b> </td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->CancelDriverId.'</td>
					   </tr>
					   <tr> 
							<td><b> Created At  </b></td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->CreatedAt.'</td>
					   </tr>
					   <tr> 
							<td><b> Created Via </b> </td>
							<td> : &nbsp</td>
							<td> ' . $Get_Retun_Detail[0]->CreatedVia.'</td>
					   </tr>
					   <tr> 
							<td><b> Created By  </b></td>
							<td> : &nbsp</td>
							<td> ' .$CreatedBy.'</td>
					   </tr>
					   <tr> 
							<td><b> Deleted By  </b></td>
							<td> : &nbsp</td>
							<td> ' . $DeletedBy.'</td>
					   </tr>
					    <tr> 
							<td><b> Job Status  </b></td>
							<td> : &nbsp</td>
							<td> ' . $jobstatus.'</td>
					   </tr>
				 </table></div> ';	
			}
				 $html = $order_html;
			         $array = array('html'=>$html,'Id'=>$Get_Retun_Detail[0]->Id);		 
				 echo json_encode($array);
		}
		
		
		/*filter functionality for jobhistory */
		public function filter_jobhistory(){
			
			$data = $this->general->check_current_session();
			$data['roleid'];
			$roleid = $data['roleid'];
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			
			$StartJob = $this->input->post('StartJob');
			
			$data['StartJob'] = $StartJob;
			$StartJob = date("Y-m-d",strtotime($StartJob));
			
			
			$EndJob= $this->input->post('EndJob');
			
			$data['CompanyId'] = $this->input->post('CompanyName');
			$companyid = $data['CompanyId'];
			
			$data['JobStatusId'] = $this->input->post('JobStatus');
			$jobstatusid = $data['JobStatusId'];
		
			if(empty($EndJob)){
				$EndJob =  date("Y-m-d");
				$data['EndJob'] = date("d-m-Y",strtotime($EndJob));
				
			}else{
				$EndJob = date("Y-m-d",strtotime($EndJob));
				$data['EndJob'] = date("d-m-Y",strtotime($EndJob));
			}
			
			$checkbox = $this->input->post('checked_box');
			$data['checkbox'] = $checkbox[0];
			
			$search_key = $this->input->post('search');
			$data['search_key'] = $search_key;
				
			$StartJob = $StartJob. ' 00:00:01';
			$EndJob =  $EndJob. ' 23:59:59';
			
		$data['filter_jobhistory_data'] =$this->jobhistorymaster_model->filter_data($jobstatusid,$companyid,$StartJob,$EndJob,$checkbox,$search_key);
			
		  	$filter_jobhistory_data=$data['filter_jobhistory_data'];
		  	// echo '<pre>';print_r($filter_jobhistory_data);exit;
			for($i=0;$i<count($filter_jobhistory_data);$i++){
			  $jobstatus_result = $this->jobhistorymaster_model->get_job_status($filter_jobhistory_data[$i]->JobStatus);
		// 	   foreach($jobstatus_result as $jobstatus){
		// if($jobstatus->StatusName=="Created"){
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Created" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// }elseif($jobstatus->StatusName=="Canceled"){
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Canceled" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// }elseif($jobstatus->StatusName=="Accepted"){
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Accepted" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// }elseif($jobstatus->StatusName=="Inprogress"){
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Inprogress" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// }elseif($jobstatus->StatusName=="Completed"){
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Completed" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// }else{
		// $filter_jobhistory_data[$i]->JobStatus = '<span class="label label-info" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
		// 				$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
		// 			}
		// 		}
		// 	}

				foreach($jobstatus_result as $jobstatus){
						if($jobstatus->StatusName=="Created"){
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Created" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Canceled"){
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Canceled" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Accepted"){
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Accepted" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Inprogress"){
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Inprogress" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}elseif($jobstatus->StatusName=="Completed"){
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-Completed" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}else{
							$filter_jobhistory_data[$i]->JobStatus = '<span class="label label-info" style="font-size:11px;top:10px;">'.$jobstatus->StatusName.'</span>';
						}
						//$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
						//$filter_jobhistory_data[$i]->JobStatus = $jobstatus->StatusName;
						//$data['JobStatus'] = $filter_jobhistory_data[$i]->JobStatus;
					}
					/*End This code for the Get JobStatus through Jobstatus Id*/
					
					/*Start This code for the Get Email for user through CreatedBy Id*/
					$createdby = $this->jobhistorymaster_model->get_customer_details($filter_jobhistory_data[$i]->CreatedBy);
					foreach($createdby as $crtedby){
						$filter_jobhistory_data[$i]->CreatedBy = $crtedby->Email;
					}
					/*End This code for the Get Email for user through CreatedBy Id*/
					
					/*Start This code for the Get Email for user through DeletedBy Id*/
					$deletedby = $this->jobhistorymaster_model->get_customer_details($filter_jobhistory_data[$i]->DeletedBy);
					foreach($deletedby as $dltdby){
						$filter_jobhistory_data[$i]->DeletedBy = $dltdby->Email;
					}
					/*End This code for the Get Email for user through DeletedBy Id*/
				
					$filter_jobhistory_data[$i]->Action ="";
					if($roleid==1 || $roleid==2)
					{
						$filter_jobhistory_data[$i]->Action .= "<a href='".base_url()."jobhistory/Edit/".$filter_jobhistory_data[$i]->Id."'><button class='btn btn-default btn-rounded btn-sm' style='margin-right:5px!important;'><span class='fa fa-pencil'></span></button></a>";
//						if($filter_jobhistory_data[$i]->jobstatus==="Created" || $filter_jobhistory_data[$i]->jobstatus==="Accepted")
						if($filter_jobhistory_data[$i]->jobstatus==="Accepted")
						{							
							$filter_jobhistory_data[$i]->Action .= "<button data-target='#mb-cancel-row' style='margin-right:5px!important;' data-toggle='modal' class='btn btn-success fa fa-trash' id='delete-data' controller='".base_url()."jobhistory/removedata/'  onclick=cancel_raw_detail('".$filter_jobhistory_data[$i]->Id."')><span class='fa fa-times'></span></button>";
						}
						$filter_jobhistory_data[$i]->Action .= "<button data-target='#mb-remove-row' data-toggle='modal' class='btn btn-danger btn-rounded btn-sm' id='delete-data' controller='".base_url()."jobhistory/Delete'  onclick=delete_row_new('".$filter_jobhistory_data[$i]->Id."')> <span class='fa fa-times'></span></button>";
					}
				}
				
				$data['section'] = array('jobhistoryrmaster_view');
				//echo '<pre>';print_r($data);exit;
				$this->general->load_my_view($data);
			
		}
		
		public function jobhistorysimpleform()
		{
			$data = $this->general->check_current_session();
			//print_r($data);exit;
			
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$data['roleid'];
			$data['section'] = array('jobhistorymaster_insert');
			$this->general->load_my_view($data);
		}
		public function jobhistoryadvanceform()
		{
			$data = $this->general->check_current_session();
			$roleid = $data['roleid'];
			$email = $data['email'];
			
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$UserCompany = $this->jobhistorymaster_model->Get_loginUser_companyName($roleid,$email);
			//echo "<pre>";print_r($UserCompany);exit;
			$data['UserCompany'] = $UserCompany;
			$CompanyId = $UserCompany[0]->Id;
			
			$CompanyDriver = $this->jobhistorymaster_model->Get_companiwise_DriverList($CompanyId);
			$data['CompanyDriver'] = $CompanyDriver;
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$data['roleid'];
			$data['section'] = array('jobhistorymaster_advance_insert');
			$this->general->load_my_view($data);
		}
		
		/*************** Create Customer if not exist ******************/
	
	public function create_customer($email,$password)
	{
		$explode = explode('@',$email);
		$name = $explode[0];
		 //$password = $this->general_model->random_password(8);
		// $token = $this->general_model->getToken(15);

		/** token generate @v **/
		$token = $this->general->VersatileAccessToken("SST","customer");
		/** end token @v **/
		$roleid =4;
		$create_customer_arr = array(
										'Name'=>$name,
										'Email'=>$email,
										'Password'=> md5($password),
										'RoleId'=>$roleid,
										'Token'=>$token			
									);
									
		$customer_result = $this->usermaster_model->insert_customer($create_customer_arr);
		$insert_customer_data = array('UserId'=>$customer_result);
  		$insert_customer_data = $this->customermaster_model->insert_customer_data($insert_customer_data);
		return $customer_result;
	}
	
	/******* Job Insert using admin dashboard ***********/
	public function insert()
	{		
			//echo "<pre>";print_r($_POST);exit;
			$session_data = $this->session->userdata('logged_in');
			$user_id = $session_data['userid'];
			$jobstatus = 1;
			$CompanyId=$this->input->post('CompanyId');
			//echo $CompanyId;exit;
			$Company=$this->input->post('Company');
			$email = $this->input->post('Email');
			$ApiKey = $this->input->post('ApiKey');
			//echo $ApiKey;exit;
			$pickuptime = $this->input->post('pickuptime');
			$pickupdate = $this->input->post('pickupdate');
			if(!empty($pickuptime) && !empty($pickupdate)){
			$ScheduleJobTime = $pickupdate.' '.$pickuptime;
			}else{
				$ScheduleJobTime = "";
			}

			//echo "time".print_r($ScheduleJobTime);exit;
			
			$company_result = $this->companymaster_model->GetCompanyByTokenPreference($ApiKey);
			//echo "<pre>";print_r($company_result);exit;
			if(empty($company_result))
			{
				$message['success'] = false; 
				$message['message'] =  'Invalid API token, Please Check apikey.';
				echo json_encode($message); exit;	
			}
			
			/*** start verify the email with user table email weather exist in db or not ***/
			// print_r($email);exit;
			$customer_emailverify_result = $this->usermaster_model->customer_email_verify($email);
			if(empty($customer_emailverify_result))
			{
				/*if email not exist then create customer*/
				$original_password = $this->general_model->random_password(8);
				$create_customer = $this->create_customer($email,$original_password);
				$get_last_customer_details = $this->get_customer_data($create_customer); 
				$email = $get_last_customer_details[0]->Email;
				$password = $get_last_customer_details[0]->Password;
				if($create_customer!=0)
				{
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
					if($mail_sent==1)
					{
						$booking['EmailStatus'] = 'Email Sent Succesfully.';
					}
				}
			}
			//print_r($customer_emailverify_result);exit;			
			
			$to = $this->input->post('pickupaddress');
			$from = $this->input->post('dropoffaddress');
			$add = $this->address_validation($to,$from);
			
			$myarray['pickupDetail'] = array(
										'name'=>$_POST['pickupname'],
										'phone'=>$_POST['pickupphone'],
										'address'=>$to
										);
			$myarray['dropoffDetail'] = array(
										'name'=>$_POST['dropoffname'],
										'phone'=>$_POST['dropoffphone'],
										'address'=>$from
										);
										
			//echo "<pre>";print_r($_POST);exit;	
			
			// $from = "sr nagar,hyderabad";
			// $to = "kukatpalle,hyderabad";
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
			//echo $data->rows[0]->elements[0]->status;//exit;
			//echo "<pre>";print_r($data);exit;

			
			if(isset($data->status) && (isset($data->rows[0]->elements[0]->status) && $data->rows[0]->elements[0]->status!= "ZERO_RESULTS")){
				//echo '<pre>';print_r($data);exit;
				if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0]))
				{
					$time = 0;
					$distance = 0;
					foreach($data->rows[0]->elements as $road) 
					{
					     //$time += $road->duration->value;
					    //$distance += $road->distance->value;
					    if(isset($road->duration))
						{
							$time = $road->duration->text;
						}
						if(isset($road->distance))
						{
							$distance  = $road->distance->text;
						}
						if(isset($road->status))
						{
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
		
					foreach($data->rows[0]->elements as $road) 
					{
						//$key['row']=$val;
						if(isset($road->distance)){
						$distance = $road->distance;
						}else{$distance=null;}
						if(isset($road->duration)){
						$duration = $road->duration;
						}else{$duration=null;}
						$status = $road->status;
					}					
					
					$JobQuery="SELECT sequencenumber FROM sequencemaster ORDER BY Id DESC LIMIT 0,1";
					$execute=$this->db->query($JobQuery);
					$JobResult=$execute->result(); 
					$JobId='';
					foreach($JobResult as $JobResultValue){
						$JobId=$JobResultValue->sequencenumber;
					}
					//$exp = explode("SSTVJOB",$JobId);
					//$exp = $JobId;
					$exp_job='';
					if(isset($JobId)){
					 //$exp[1] = null;	
					 $exp_job = $JobId+1;
					}
					$JobId1 = $exp_job;
					if($JobId1 < 100000000000){
						$JobNumberValues=100000000001;
						$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
					else{
					$exp = explode("SSTVJOB",$JobId);
					$exp_job = $JobId+1;
					$JobNumberValues=$exp_job;
					$GenerateJobId= "SSTVJOB".$JobNumberValues;
					}
					
					$sequenceNumberRow=array(
					   "sequencenumber"=>$JobNumberValues
					); 

					$this->db->insert('sequencemaster',$sequenceNumberRow);
					
					$pickupDetail['pickupDetail'][] = $data->destination_addresses;
					$dropoffDetail['dropoffDetail'][] = $data->origin_addresses;
					
					/*Get UserId through the Email*/
					$userdetails = $this->usermaster_model->customer_email_verify($email);
					if(isset($userdetails[0]->Id)){
						$userid = $userdetails[0]->Id;
					}else{ $userid='';}

					/******* Start:  Get the company time zone and then compare time diffrence @v****/
							
							//$ScheduleJobTime ="";
							$currentdt="";
							$CompanyTimezone="";
							$Timezone="";
							if(isset($ScheduleJobTime) && $ScheduleJobTime!=0)
							{	 
						        //echo '111';exit;
								if($this->validateDatetime($ScheduleJobTime))
								{					
									//echo "valide time";exit;
									$getTimeZone = $this->general->getCompanyTimeZonebyId($CompanyId);
									//print_r($getTimeZone);exit;
									$currentdt="";
									if($getTimeZone['success']==1){
										$CompanyTimezone = $getTimeZone['TimeZone'][0];
										//echo $CompanyTimezone;
										$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
									}else{
										$message['success'] = false; 
										$message['message'] =  'error while timezone : '.$getTimeZone['error'];
										$message['error'] =  $getTimeZone['error'];
										echo json_encode($message); exit;
									}		
									
									/****** 60 second buffer *******/
									$add_time=strtotime($currentdt)-60;
									$currentdtn= date('Y-m-d H:i:s',$add_time);			

									$dtCurrent = new DateTime($currentdtn);
									// $dtCurrent = new DateTime($currentdt);
									//$dtCurrent = $currentdt;
									$dtSchdule = new DateTime($ScheduleJobTime);

									if ( $dtCurrent > $dtSchdule ) {
										$message['success'] = false; 
										$message['message'] =  'Company timezone '.$CompanyTimezone.' & Time:'.$currentdt.' and given Schedule JobTime '.$ScheduleJobTime.' is past datetime, Please add future time to create Schedule job, Past time not allowed.';
										echo json_encode($message); exit;	
									}							
								}
								else
								{
									$message['success'] = false; 
									$message['message'] =  'Invalid Time format, Please Check pickupTime.';
									echo json_encode($message); exit;	
								}						
							}	
							
					/******* End : Get the company time zone and then compare time diffrence @v****/
	 				foreach($company_result as $cresult){
						if($cresult->CompanyId==$CompanyId){
							$timezonearra[] = $cresult->CompanyId;
						}
					}
					
					
	 				$getTimeZone = $this->general->getCompanyTimeZonebyId($company_id);
					$currentdt="";
				    if($getTimeZone['success']==1)
					{
				        $Timezone = $getTimeZone['TimeZone'][0].'-'.$getTimeZone['TimeZone'][1];
				        
				        $currentdt = $this->general->getDatetimeNow($Timezone);
				    }
				
					//echo 'krushna';print_r($CompanyTimezone);exit;
					
					
					//echo "<pre>";print_r($data);exit;
					$insert_array = array(					
											'UserId'=>$userid,
											'JobId'=>$GenerateJobId,
											'CompanyId'=>$company_id,
											'PickupDetail' =>json_encode($myarray['pickupDetail']),
											'DropoffDetail' =>json_encode($myarray['dropoffDetail']),
											'Distance' =>json_encode($distance),
											'Duration' =>json_encode($duration),
											'DistanceStatus' =>json_encode($status),
											//'ScheduleJobTime' =>$ScheduleJobTime,
											'JobStatus'=>$job_status,
											'CreatedVia'=>'Data Entry',
											'CreatedBy'=>$company_id,
											'TimeZone'=>$Timezone,
											'CreatedAt'=>$currentdt
					);
					
					//echo $ScheduleJobTime;//exit;
					if(isset($ScheduleJobTime) && !empty($ScheduleJobTime))
				    {
				    	//	echo "yes";
				       $insert_array['ScheduleJobTime'] = $ScheduleJobTime;      
				    }
				    // echo '<pre>'; print_r($insert_array);exit;

					$currenjob_insert_data = $this->Job_model->insert_currentjob_data($insert_array);
					if($currenjob_insert_data==0)
					{
						if(empty($time) && empty($distance)){
							$message['success'] = false; 
							$message['message'] =  'Error:Zero Result Found.';
							echo json_encode($message);exit;
						}
					}	
					else
					{
					$get_jobmaster_details = $this->Job_model->get_job_data($currenjob_insert_data);

					$booking['id'] = $get_jobmaster_details[0]->Id; 

							$booking['JobId'] = $get_jobmaster_details[0]->JobId;
							
							if(isset($get_jobmaster_details[0]->ScheduleJobTime)){
							$booking['ScheduleJobTime'] = $get_jobmaster_details[0]->ScheduleJobTime;
							}
							$booking['Consignment'] = $get_jobmaster_details[0]->Consignment;

							$booking['JobStatus'] = $get_jobmaster_details[0]->StatusName;	
							$booking['CreatedVia'] = $get_jobmaster_details[0]->CreatedVia;			
							$booking['created'] = $get_jobmaster_details[0]->CreatedAt;	

							$get_jobmaster_details[0]->PickupDetail = json_decode($get_jobmaster_details[0]->PickupDetail,true);
							$get_jobmaster_details[0]->DropoffDetail = json_decode($get_jobmaster_details[0]->DropoffDetail,true);
							$booking['PickupDetail']['name'] = $get_jobmaster_details[0]->PickupDetail['name']; 
							$booking['PickupDetail']['address'] = $get_jobmaster_details[0]->PickupDetail['address']; 
							$booking['PickupDetail']['phone'] = $get_jobmaster_details[0]->PickupDetail['phone']; 
							
							$booking['DropoffDetail']['name'] = $get_jobmaster_details[0]->DropoffDetail['name']; 
							$booking['DropoffDetail']['address'] = $get_jobmaster_details[0]->DropoffDetail['address']; 
							$booking['DropoffDetail']['phone'] = $get_jobmaster_details[0]->DropoffDetail['phone']; 


							$get_jobmaster_details[0]->Distance=json_decode($get_jobmaster_details[0]->Distance,true);
							$get_jobmaster_details[0]->Duration=json_decode($get_jobmaster_details[0]->Duration,true);
							$get_jobmaster_details[0]->DistanceStatus=json_decode($get_jobmaster_details[0]->DistanceStatus,true);

							$booking['distanceKm'] = $get_jobmaster_details[0]->Distance['text'];
							$booking['Duration'] = $get_jobmaster_details[0]->Duration['text'];
							$booking['DistanceStatus'] = $get_jobmaster_details[0]->DistanceStatus;
														
							

							$booking['Compnay']['CompanyId'] = $get_jobmaster_details[0]->CompanyId;
							$booking['Compnay']['CompnayName'] = $get_jobmaster_details[0]->CompnayName;
							$booking['Compnay']['CompanyEmail'] = $get_jobmaster_details[0]->CompanyEmail;
							$booking['Compnay']['CompanyLogo'] = base_url().$get_jobmaster_details[0]->CompanyLogo;
							$booking['Compnay']['CompanyToken'] = $get_jobmaster_details[0]->CompanyToken;
							$booking['Compnay']['NotificationMethod'] = $get_jobmaster_details[0]->NotificationMethod;

							// $booking['CustomeName'] = $get_jobmaster_details[0]->UserName;
							
							$booking['Customer']['CustomerId'] = $get_jobmaster_details[0]->CustomerId;
							$booking['Customer']['CustomerName'] = $get_jobmaster_details[0]->CustomerName;
							$booking['Customer']['CustomerEmail'] = $get_jobmaster_details[0]->CustomerEmail;
							
							$booking['lastUpdated'] = $get_jobmaster_details[0]->UpdatedAt; 
							
							$booking['Enable'] = $get_jobmaster_details[0]->Enable;

							$message['booking'] =  $booking;

					/** To get Notification method for particular company related to job ***/
					$Notification_method = $this->priority_model->Get_Notification_method($get_jobmaster_details[0]->CompanyId);
					if(isset($Notification_method[0]->NotificationMethodId))
					{
						$Notification_method = $Notification_method[0]->NotificationMethodId;
						//echo $Notification_method;exit;
						
						/******* Brodcast Job *******/
						if($Notification_method==1)
						{
							$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($get_jobmaster_details[0]->CompanyId);
							//print_r($getcmpnydriverdetails);exit;
							foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
								{
									$data = array('mtitle' => "New Job(B)!!",
												   'mdesc'=>"Job Notification",
												   'text'=>$message
									);
									/**** iOS Device Push Notification *****/
									if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
									{
										$devicetoken = $driverdetails->DeviceUniquId;		
										$message['iOS'][$driverdetails->DeviceUniquId] = notifications::iOS($data, $devicetoken);

									}
									/**** Android Device Push Notification *****/
									if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
										$FCMRegId = $driverdetails->FCMRegId;
										$message['android'][$driverdetails->FCMRegId] = notifications::android($data,$FCMRegId);
									}
								}
							}
						}
						/******* Priority Job *******/
						if($Notification_method==2)
						{
							//echo "Yes";exit;
							$getcmpnydriverdetails = $this->priority_model->get_companydriver_details($get_jobmaster_details[0]->CompanyId);

							//print_r($getcmpnydriverdetails);//exit;
							foreach ($getcmpnydriverdetails as $driverdetails) 
							{
								if(isset($driverdetails->WorkingStatus) && $driverdetails->WorkingStatus == 1)
								{
									if($driverdetails->Priority == 1)
									{
										$data = array('mtitle' => "New Job(P)!!",
												   'mdesc'=>"Job Notification",
												   'text'=>$message
										);
										/**** iOS Device Push Notification *****/
										if(isset($driverdetails->DeviceUniquId) && $driverdetails->DeviceType == "IOS")
										{
											$devicetoken = $driverdetails->DeviceUniquId;		
											notifications::iOS($data, $devicetoken);
										}
										/**** Android Device Push Notification *****/
										if(isset($driverdetails->FCMRegId) && $driverdetails->DeviceType == "ANDROID"){
												$FCMRegId = $driverdetails->FCMRegId;
												notifications::android($data,$FCMRegId);
										}
									}
								
								}
							
							}
							
						}
					}
					/******** END Notification ***********/
						$message['success'] = true; 
						$message['message'] =  'Job created Successfully.';
					}
				}
				else
				{
					$message['success'] = false; 
					$message['message'] =  'Invalid address or request,address not found by google address.';
				}
			}
			else
			{
				$message['success'] = false; 
				$message['message'] =  'Invalid address or request. No result found by google place.';
			}
			echo json_encode($message);
		}
	/************ Get Last Inserted Customer Details from Usermaster Table ***********/
	public function get_customer_data($last_id)
	{
		$customer_result = $this->usermaster_model->get_customer_details($last_id);
		if(!empty($customer_result)){
			return $customer_result;
		}
	}
	public function address_validation($to,$from)
	{
			
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
	/*public function test($to,$from)
	{
		
		
		$from = urlencode($from);
		$to = urlencode($to);
		$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?destinations=$to&language=en-EN&sensor=false");
		echo '<pre>';print_r($data);exit;
		
		
	}*/
	public function Edit($Id)
	{
			$data = $this->general->check_current_session();
			$data['roleid'];
			$roleid = $data['roleid'];
			$email = $data['email'];
			$data['EditData'] = $this->jobhistorymaster_model->edit_data($Id);
			// echo "<pre>";print_r($data['EditData']);exit();
			$data['Getcompany'] = $this->general_model->GetcompanyData();
			$UserCompanyId = $data['EditData'][0]->CompanyId;
			$data['UserCompanyId']=$UserCompanyId;
			$JobStatus = $data['EditData'][0]->JobStatus;
			$data['JobStatus']=$JobStatus;
			$data['UserCompanyName'] = $this->jobhistorymaster_model->edit_CompanyName($UserCompanyId);
			//echo "<pre>";print_r($data['UserCompanyName']);exit();
			$data['jobstatus'] = $this->jobhistorymaster_model->get_job_status($job='');
			$data['section'] = array('jobhistorymaster_advance_insert');
			$this->general->load_my_view($data);
		}
	public function update()
	{
			
			$session_data = $this->session->userdata('logged_in');		
			$user_id = $session_data['userid'];
			$CompanyId=$this->input->post('CompanyId');
			$JobStatus=$this->input->post('JobStatus');
			//echo $CompanyId;exit;
			$UpdateId=$this->input->post('UpdateId');
			$timestamp = date('Y-m-d H:i:s');
			//echo "<pre>";print_r($_POST);exit();

			   $pickuptime = $this->input->post('pickuptime');
			   $pickupdate = $this->input->post('pickupdate');
			   if(!empty($pickuptime) && !empty($pickupdate)){
			   $ScheduleJobTime = $pickupdate.' '.$pickuptime;
			   }else{
			    $ScheduleJobTime = "";
			   }
			
			/******** Start Calculate Distance and Duration **********/
			
			//echo $UpdateId;exit;
			$to = $this->input->post('pickupaddress');
			$from = $this->input->post('dropoffaddress');
			$from = urlencode($from);
			$to = urlencode($to);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			//echo "<pre>";print_r($data);exit;
			$data = json_decode($data);
			if(isset($data->status))
			{
				if(!empty($data->destination_addresses[0]) && !empty($data->origin_addresses[0]))
				{
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
			
			
			$update_array=array(
				'CompanyId'=>$CompanyId,
				'UserId'=>$user_id,
				'pickupDetail'=>$pickupDetail,
				'dropoffDetail'=>$dropoffDetail,
				'JobStatus'=>$JobStatus,
				'Distance'=>$distance,
				'Duration'=>$duration,
				'UpdatedAt'=>$timestamp
			);
			if(isset($ScheduleJobTime) && !empty($ScheduleJobTime))
	        {
	         // echo "yes";
	           $update_array['ScheduleJobTime'] = $ScheduleJobTime;      
	        }
			//echo '<pre>';print_r($update_array);exit;
			$query=$this->jobhistorymaster_model->update_data($update_array,$UpdateId);
			if($query==1)
			{
				
				$message['success'] = true; 
				$message['message'] =  'Data Updated sucessfully.';
			}
			else
			{
				
				$message['success'] = false; 
				$message['message'] =  'Something went wrong.';
			}
			echo json_encode($message);
			//redirect('jobhistory');
	}
	
	public function removedata()
	{
	
		$data = $this->general->check_current_session();
		$email = $data['email'];
		$roleid = $data['roleid'];
		$userid_result = $this->jobhistorymaster_model->get_loginuser_id($email,$roleid);
		
		$UserId = $userid_result[0]->Id;

		$Id = $this->input->post('Id');
		$Reason = $this->input->post('Reason');
		
		$check_data_cancel = $this->jobhistorymaster_model->Cancel_record_search($Id,$Reason);

			
		   if(!empty($check_data_cancel)){	
			$JobId = $check_data_cancel[0]->JobId;
			$UserId = $userid_result[0]->Id;
		   }else{
		   	$JobId='';
		   	$UserId ='';
		   }			
				
				$JobQuery="SELECT * FROM `jobmaster` WHERE JobId='".$JobId."' AND Id=$Id AND JobStatus IN (1,3) ORDER BY Id DESC LIMIT 0,1";
				//$JobQuery="SELECT * FROM `jobmaster` WHERE JobId='".$JobId."' AND Id=$Id AND DriverId=$UserId AND JobStatus IN (1,3) ORDER BY Id DESC LIMIT 0,1";
				//$JobQuery="SELECT * FROM `jobmaster` WHERE JobStatus=3 OR JobStatus=1 AND JobId='".$JobId."' AND DriverId=$UserId  ORDER BY Id DESC LIMIT 0,1";
				$execute=$this->db->query($JobQuery);
				$JobResult=$execute->result(); 
				
				 /***** check job accepted by driver or not ***********/
			if(!empty($JobResult))
			{
			
				$canceldriverid = json_encode(array("JobId"=>$JobId,"UserId"=>$UserId,"cancellationNotes"=>$Reason));
				
				$update_array= array(									
										'CancelDriverId' => $canceldriverid,
										'DriverId' => $UserId,
										'JobStatus'=>2,
										//'Enable'=>0,
										'UpdatedAt'=>date('Y-m-d h:i:s')
									);
									
					
					$updated_result= $this->Job_model->Canceldelivery_Admin($JobId,$update_array,$Id);

					if($updated_result==1)
					{
						
						

						/******** Completed the Cancel job process and start the new job create with same details with new jobId and status to created @v*************/

									
								$GenerateJobId="";				
								$JobQuery="SELECT sequencenumber FROM sequencemaster ORDER BY Id DESC LIMIT 0,1";
								$execute=$this->db->query($JobQuery);
								$JobResult=$execute->result(); 
								$JobId='';
								foreach($JobResult as $JobResultValue){
									$JobId=$JobResultValue->sequencenumber;
								}
								//$exp = explode("SSTVJOB",$JobId);
								//$exp = $JobId;
								$exp_job='';
								if(isset($JobId)){
								 //$exp[1] = null;	
								 $exp_job = $JobId+1;
								}
								$JobId1 = $exp_job;
								if($JobId1 < 100000000000){
									$JobNumberValues=100000000001;
									$GenerateJobId= "SSTVJOB".$JobNumberValues;
								}
								else{
								$exp = explode("SSTVJOB",$JobId);
								$exp_job = $JobId+1;
								$JobNumberValues=$exp_job;
								$GenerateJobId= "SSTVJOB".$JobNumberValues;
								}
								
								$sequenceNumberRow=array(
								   "sequencenumber"=>$JobNumberValues
								); 
								$this->db->insert('sequencemaster',$sequenceNumberRow);
						
						$JobId = $check_data_cancel[0]->JobId;
						$get_all_jobdata = $this->Job_model->get_all_jobdata_new($JobId,$Id);

						$getTimeZone = $this->general->getCompanyTimeZonebyId($get_all_jobdata[0]->CompanyId);
								//print_r($getTimeZone);//exit;
								$currentdt="";
								if($getTimeZone['success']==1){
									$CompanyTimezone = $getTimeZone['TimeZone'][0];
									//echo $CompanyTimezone;
									$currentdt = $this->general->getDatetimeNow($CompanyTimezone);
								}else{
									$message['success'] = false; 
									$message['message'] =  '<div class="alert alert-danger" role="alert"><strong> Error : Timezone not set in company.</strong></div>';
									$message['error'] =  $getTimeZone['error'];
									echo json_encode($message); exit;
								}		
						
						$insert_array = array(					
									'UserId'=>$get_all_jobdata[0]->UserId,
									'JobId'=>$GenerateJobId,
									'CompanyId'=>$get_all_jobdata[0]->CompanyId,
									'PickupDetail' =>$get_all_jobdata[0]->PickupDetail,
									'DropoffDetail' =>$get_all_jobdata[0]->DropoffDetail,
									'Distance' =>$get_all_jobdata[0]->Distance,
									'Duration' =>$get_all_jobdata[0]->Duration,
									'DistanceStatus' =>$get_all_jobdata[0]->DistanceStatus,
									'Consignment' =>$get_all_jobdata[0]->Consignment,				
									'CreatedAt' => $currentdt,
									'JobStatus'=>1,
									'CreatedVia'=>'RESTAPI',
									'CreatedBy'=>$get_all_jobdata[0]->CompanyId,
									'TimeZone'=>$get_all_jobdata[0]->TimeZone
								);
				
						
						$currenjob_insert_data = $this->Job_model->insert_currentjob_data($insert_array);

						$message['success'] = true; 
						$message['message'] =  '<div class="alert alert-success" role="alert"><strong>Job Cancel successfully.</strong></div>';
					}
					else
					{
						$message['success'] = false; 
						$message['message'] =  '<div class="alert alert-danger" role="alert"><strong>Something went wrong</strong></div>';
					}
			}
			else
			{					
				$message['success'] = false; 
				$message['message'] =  '<div class="alert alert-danger" role="alert"><strong>Sorry, No accepted job result found.</strong></div>';
			}
		
		echo json_encode($message);exit;
	} 
			
	public function Tick_ChkBox()
	{
		$Id = $this->input->post('Id');
		$Reason = $this->input->post('Reason');
		//echo $Reason;exit;
		$check_cancel_detail = $this->jobhistorymaster_model->Get_Tick_Data($Id,$Reason);
		if($check_cancel_detail==1)
		{
			$message['success'] = true; 
			$message['message'] =  '<div class="alert alert-success" role="alert"><strong>Job Cancelled Parmanently.</strong></div>';
		}
		else
		{
			$message['success'] = false; 
			$message['message'] =  '<div class="alert alert-danger" role="alert"><strong>Something Wrong.</strong></div>';
		}
		
		echo json_encode($message);
	}
	
	public function Delete($Id)
	{
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
	
	public function validateDateTime($dateStr, $format="Y-m-d H:i:s")
	{
	    date_default_timezone_set('UTC');
	    $date = DateTime::createFromFormat($format, $dateStr);
	    return $date && ($date->format($format) === $dateStr);
	}
		
}
?>