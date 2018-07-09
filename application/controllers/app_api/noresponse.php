<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class noresponse extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->model('job_model');
	    $this->load->model('general_model');
	    $general = $this->load->library('../controllers/general');
    }

    /******** No Response api *********/
    public function nrpapi()
	{
			/*Get job list when job status=1*/
			$final_newarray = $this->job_model->findJob();
			// print_r($final_newarray);

			$flag=0;
			$i=0;
			$myarray=array();
			$driveridupdate=array();
			$myarray=array();
			if(!empty($final_newarray)){

				foreach($final_newarray as $value){
					$created = $value->CreatedAt;
					$created_time = new DateTime($created);
					$CompanyId = $value->CompanyId;
					// get current time by conpmay timezone ********/
					$getTimeZone = $this->general->getCompanyTimeZonebyId($CompanyId);
					$currentdt="";
				    if($getTimeZone['success']==1){
				     $CompanyTimezone = $getTimeZone['TimeZone'][0];
				     $currentdt = $this->general->getDatetimeNow($CompanyTimezone);
				    }

					$curr_time = new DateTime($currentdt);
					// $dteDiff  = $created_time->diff($curr_time); 
					$dteDiff  = $created_time->diff($curr_time); 

					$minute = $dteDiff->i;

					$PRejected_driverid= array();
					//echo "Rejected".$value->RejectDriverId;exit;
					if(isset($value->RejectDriverId) && !empty($value->RejectDriverId)){

					$PRejected_driverid = $value->DriverId;					
					$PRejected_driverid = explode(',', $PRejected_driverid);
					}

					// print_r($PRejected_driverid);

					if($minute>=2){
						$companyid = $value->CompanyId;
						 
						$jobresult = $this->job_model->get_companydriver_details($companyid);
						// print_r($jobresult);
						$driveridupdate = array();
						foreach($jobresult as $val){
							$CompanyDriverUserId = $val->DriverUserId;
							
							if(isset($PRejected_driverid) && !empty($PRejected_driverid)){
								//echo "yes";exit;
								if(!in_array($CompanyDriverUserId,$PRejected_driverid)){
									$driveridupdate[] = $CompanyDriverUserId;					
								}
							}
							else{
								$driveridupdate[] = $CompanyDriverUserId;	
							}
						}
					}

					$rejectedid = implode(",",$driveridupdate);
					$rejectedid_result = ltrim($rejectedid, ",");
					$update_array= array('RejectDriverId'=>$rejectedid_result,
										 'UpdatedAt'=>$currentdt
					);
					$jobmaster_id= $value->Id;
					// $update_rejected_status = $this->job_model->update_rejectedid($rejectedid_result,$jobmaster_id);					
					$update_rejected_status = $this->job_model->update_rejectedid($update_array,$jobmaster_id);					


					$i++;
				}				
				
				if($update_rejected_status==1){
							 $message['success'] = 'true'; 
							 $message['message'] =  'Job Rejected Status Updated.';   
							 echo json_encode($message); exit; 
				}
				else{
							 $message['success'] = 'false'; 
							 $message['message'] =  'Error Occured.';   
							 echo json_encode($message); exit; 
				}
			}
			
	}
	
	// public function NoResponseApi()
	// {
	// 		/*Get job list when job status=1*/
	// 		$jobstatus_first = $this->job_model->findJob();
	// 		$jobstatus_second = $this->job_model->findjob_status();
	// 		//$flag=0;
	// 		$final_newarray = array();
	// 		$i=0;
	// 		foreach($jobstatus_first as $fvalue){
	// 			$fjobid = $fvalue->JobId;
	// 			$flag=0;
	// 			foreach($jobstatus_second as $svalue){
	// 				$sjobid = $svalue->JobId;
	// 				$sjobstatus = $svalue->JobStatus;
	// 				if($fjobid==$sjobid){
	// 					$flag=1;
	// 				}
	// 			}		
	// 			if($flag==0){
	// 				$final_newarray[] = $jobstatus_first[$i];
	// 			}
	// 			$i++;
				
	// 		}
	// 		$flag=0;
	// 		$i=0;
	// 		$myarray=array();
	// 		$driveridupdate=array();
	// 		$myarray=array();
	// 		if(!empty($final_newarray)){
	// 			foreach($final_newarray as $value){
	// 				$created = $value->CreatedAt;
	// 				$created_time = new DateTime($created);
	// 				$curr_time = new DateTime();
	// 				$dteDiff  = $created_time->diff($curr_time); 
	// 				$minute = $dteDiff->i;
	// 				$finalarray_driverid = $value->DriverId;
				
	// 				if($minute>=2){
	// 					$companyid = $value->CompanyId;
						 
	// 					$jobresult = $this->job_model->get_companydriver_details($companyid);
						
	// 					foreach($jobresult as $val){
	// 						$CompanyDriverUserId = $val->DriverUserId;
							
	// 						if($CompanyDriverUserId==$finalarray_driverid){
	// 							$flag=1;
								
	// 						}
	// 					}
	// 					if($flag==0){
	// 							$myarray[] = $final_newarray[$i];
	// 							$driveridupdate[] = $CompanyDriverUserId;
	// 							$f[] =$finalarray_driverid;
	// 					}
	// 				}
	// 				$i++;
	// 			}
	// 			$rejectedid = implode(",",$driveridupdate);
	// 			$rejectedid_result = ltrim($rejectedid, ",");
	// 			foreach($final_newarray as $value){
	// 				$jobmaster_id= $value->Id;
	// 				$update_rejected_status = $this->job_model->update_rejectedid($rejectedid_result,$jobmaster_id);
	// 			}
				
	// 			if($update_rejected_status==1){
	// 						 $message['success'] = 'true'; 
	// 						 $message['message'] =  'Job Rejected Status Updated.';   
	// 						 echo json_encode($message); exit; 
	// 			}
	// 			else{
	// 						 $message['success'] = 'false'; 
	// 						 $message['message'] =  'Error Occured.';   
	// 						 echo json_encode($message); exit; 
	// 			}
	// 		}
			
	// }

	
	
	
}
