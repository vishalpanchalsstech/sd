<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class editprofile extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->model('editprofile_model');
		$this->load->model('general_model');
		$general = $this->load->library('../controllers/general');
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			$data['email'] = $session_data['email'];
			$data['lastlogin'] = $session_data['lastlogin'];
		}
		else
		{ redirect('/login/logout', 'refresh'); }
    }
	public function index()
	{
		$data = $this->general->check_current_session();
		$session_data = $this->session->userdata('logged_in');
		$data['roleid'] = $session_data['roleid'];
		$data['userid'] = $session_data['userid'];
		$userid = $data['userid'];
		
		$msg=$this->session->flashdata('my_msg');
		$data['msg'] =$msg;
		
		if($data['roleid'] == 3)
		{
			
			$data['Edit_Profile'] = $this->display_driver_detail();
			$Edit_Profile = $data['Edit_Profile'];
			$data['country_details'] = $this->general_model->Get_country_Records();
			$data['section'] = array('editprofile_view');
			$this->general->load_my_view($data);
		}
		else
		{
			$data['EditData'] = $this->editprofile_model->edit_profile_data($userid);
			$EditData = $data['EditData'];
			$data['section'] = array('editprofile_view');
			$this->general->load_my_view($data);
		}
		
	   
	}
	public function display_driver_detail()
	{
		$session_data = $this->session->userdata('logged_in');
		$user_id = $session_data['userid'];
		$driver_profile = $this->editprofile_model->driver_data($user_id);
		return $driver_profile;
	}

	
	public function updateProfileProcess()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$UserId = $this->input->post('UserId');
		$drvr_id = $this->input->post('driverId');
		
		$name=$this->input->post('name');
		$email=$this->input->post('email');
		$Password=$this->input->post('Password');
		$New_pass=$this->input->post('New_pass');
			
		if($New_pass==$Password)
		{
			$ins_pass=$Password;
		}
		else
		{
			$ins_pass=md5($Password);
		}
		$RoleId = 3;
			
		$timestamp = date('Y-m-d H:i:s');
				
		$user_update_array=array( 
									'Name'=>$name,
									'Email'=>$email,
									'Password'=>$ins_pass,
									'RoleId'=>$RoleId,
									'UpdatedAt'=>$timestamp
									//'Enable'=>'0',
								);
									
		$user_update_query = $this->editprofile_model->update_user_data($user_update_array,$UserId);
				
			if($user_update_query)
			{
				$name=$this->input->post('name');
				$Building=$this->input->post('Building');
				$Street=$this->input->post('Street');
				$country=$this->input->post('country');
				$Suburb=$this->input->post('Suburb');
				$State=$this->input->post('State');
				$Postcode=$this->input->post('Postcode');
				$PhoneNo=$this->input->post('PhoneNo');
				$licenceNo=$this->input->post('licenceNo');
				$WorkingStatus=$this->input->post('WorkingStatus');
				$userId = $user_update_query;
				
				$uploadimg = $this->input->post('uploadimg');
				$uploadimg2 = $this->input->post('uploadimg2');
				
				$profile_target_path = 'assets/uploads/images/driver/';
				$document_target_path = 'assets/uploads/images/document/';
				
				if(!empty($_FILES['editfile2']['name']) || ($_FILES['editfile']['name']))
				{ 
					$ext = pathinfo($_FILES['editfile2']['name'], PATHINFO_EXTENSION);
					$add= "." . $ext;
					$profile_name = $this->general_model->imagename($name);   
					$target_path = $profile_target_path . $profile_name . $add;
					
					if(move_uploaded_file($_FILES['editfile2']['tmp_name'], $target_path))
					{
						$uploadimg = $target_path;
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
					
					$document_ext = pathinfo($_FILES['editfile']['name'], PATHINFO_EXTENSION);
					$add= "." . $document_ext;
					$document_name = $this->general_model->imagename($name);   
					$target_path_document = $document_target_path . $document_name . $add;
					
					if(move_uploaded_file($_FILES['editfile']['tmp_name'], $target_path_document))
					{
						$uploadimg2 = $target_path_document;
					}
					else
					{
						$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
					}
				}
				
			$driver_update_array=array(
										'Phoneno'=>$PhoneNo,
										'Building'=>$Building,
										'Street'=>$Street,
										'Country'=>$country,
										'Suburb'=>$Suburb,
										'State'=>$State,
										'Postcode'=>$Postcode,
										'LicenceNo'=>$licenceNo,
										'ProfileImage'=>$uploadimg,
										'DocumentImage'=>$uploadimg2,
										'WorkingStatus'=>$WorkingStatus,
										'UserId'=>$userId
									 );
				//echo "<pre>"; print_r($driver_update_array); exit;	
				$driver_query = $this->editprofile_model->update_driver_data($driver_update_array,$userId,$drvr_id);
				//echo "<pre>"; print_r($driver_query); exit;	
				if($driver_query)
				{
					$VehicleName=$this->input->post('vehiclename');
					$VehicleNo=$this->input->post('vehicleno');
					
					$driver_Id = $driver_query;
					
					$vehicle_update_array=array(
												'DriverId'=>$driver_Id,
												'VehicleType'=>$VehicleName,
												'VehicleNumber'=>$VehicleNo
												);
						$driver_query = $this->editprofile_model->update_vehicle_detail($vehicle_update_array,$driver_Id);
						$msg='<div class="alert alert-success" role="alert"><strong>Profile Updated sucessfully.</strong></div>';
				}
				else
				{
					$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
				}
					
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('editprofile');
	}
	
/******* Update UserProfile Which RoleId is 1 Or 2 Mohammed *******/

	public function updateprofile()
	{
			$session_data = $this->session->userdata('logged_in');
			$userid=$session_data['userid'];
			$Name=$this->input->post('Name');
			$Password=$this->input->post('Password');			
			$last_pass = $this->input->post('lasspass');  
			if($last_pass==$Password)
			{
			   $ins_pass=$Password;
			}
			else
			{
			   $ins_pass=md5($Password);
			}
   			$timestamp = date('Y-m-d H:i:s');
			$update_array=array(
				'Name'=>$Name,
				'Password'=>$ins_pass,
				'UpdatedAt'=>$timestamp
			);
			$query=$this->editprofile_model->update_data($update_array,$userid);
			if($query==1)
			{
				$msg='<div class="alert alert-success" role="alert"><strong>Profile Updated sucessfully.</strong></div>';					
			}
			else
			{
				$msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
			}
			$flash_data = $this->session->set_flashdata('my_msg', $msg);
			redirect('editprofile');
	}
}
?>