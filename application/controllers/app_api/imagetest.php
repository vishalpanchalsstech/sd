<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class imagetest extends CI_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('general_model');
    }

 
    function upload()
    {
        //$r_num = $this->general_api_model->random();
		// Path to move uploaded files
		$target_path = 'assets/uploads/images/';
		
		if (isset($_FILES['image']['name'])) {
			$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$profile_name = 'fname-lname-'.date("Y-m-d").'.'.$ext;
			$target_path = $target_path . $profile_name;
			
			if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path))
			{
				echo json_encode(array('status'=>'success', 'message'=>'File Uploaded'));
			}
			else
			{
				echo json_encode(array('status'=>'fail', 'message'=>'could not move file'));
			}
			
		} else {
			// File parameter is missing
			echo json_encode(array('status'=>'fail', 'message'=>'Not received any file'));
		} 

    }
     
  
}
    

?>