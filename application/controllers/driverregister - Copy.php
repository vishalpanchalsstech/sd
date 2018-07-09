<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class driverregister extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('driverregister_model');
        $this->load->model('general_model');
        $general = $this->load->library('../controllers/general');

    }
    public function index()
    {
        $msg=$this->session->flashdata('my_msg');
        $data['msg'] =$msg;
        $data['country_details'] = $this->general_model->Get_country_Records();
        $this->load->view('driverregister_view',$data);

    }

    public function validate_email()
    {

        $email=$this->input->post('email');
        $validateemail = $this->general_model->emailvalidate($email,'3');

        if($validateemail)
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
    }
    public function test(){
        $name = $this->input->post('name');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if($this->form_validation->run() == false ){
            $this->load->view('driverregister_view');
        }

    }

    public function insert_registration()
    {
        // echo '<pre>';print_r($_POST);//exit;
        

        
        $session_data = $this->session->userdata('logged_in');
        $data['post']= $_POST;
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $Password = $this->input->post('Password');
        $Building=$this->input->post('Building');
        $Street=$this->input->post('Street');
        $country=$this->input->post('country');
        $Suburb=$this->input->post('Suburb');
        $State=$this->input->post('State');
        $Postcode=$this->input->post('Postcode');
        $PhoneNo=$this->input->post('PhoneNo');
        $licenceNo=$this->input->post('licenceNo');
        $VehicleName=$this->input->post('vehiclename');
        $VehicleNo=$this->input->post('vehicleno');
        $RoleId = 3;

        if (empty($_FILES['ProfileImg']['name']))
        {
            $this->form_validation->set_rules('ProfileImg', 'Profile image', 'required');
        }
        if (empty($_FILES['DocumentImg']['name']))
        {
            $this->form_validation->set_rules('DocumentImg', 'Document image', 'required');
        }

        $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[3]|max_length[10]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[usermaster.Email]');
        $this->form_validation->set_rules('Password', 'Password', 'trim|required|numeric|min_length[6]|max_length[10]');
        $this->form_validation->set_rules('Conf_Password', 'Confirm Password', 'trim|required|matches[Password]');
        $this->form_validation->set_rules('Building', 'Building', 'trim|required');
        $this->form_validation->set_rules('Street', 'Street', 'trim|required');
        $this->form_validation->set_rules('country', 'country', 'trim|required');
        $this->form_validation->set_rules('Suburb', 'Suburb', 'trim|required');
        $this->form_validation->set_rules('State', 'State', 'trim|required');
        $this->form_validation->set_rules('Postcode', 'Postcode', 'trim|required|numeric|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('PhoneNo', 'PhoneNo', 'trim|required|numeric|min_length[10]|max_length[12]');
        $this->form_validation->set_rules('licenceNo', 'licenceNo', 'trim|required');
        $this->form_validation->set_rules('vehiclename', 'vehiclename', 'trim|required');
        $this->form_validation->set_rules('vehicleno', 'vehicleno', 'trim|required');

        $this->form_validation->set_error_delimiters('<div class="col-md-12  no-padding"><label class="error";>', '</label></div>');

        if($this->form_validation->run() == false )
        {
            //echo validation_errors();
            //echo "yes";exit;
            $this->load->view('driverregister_view',$data);
        }
        else
        {
            // echo "No";exit;
            $token = $this->general->VersatileAccessToken();
            $user_token = $this->general->VersatileAccessToken("SST","driver");

            $to = $email;
            $from = '';
            $cc = '';
            $subject = 'SSTech Driver Email Verify';
            $body = "<p>Hi $name ,</p><p>It's time to confirm your email address.</p><p>Have we got the right email address to reach you on? To confirm that you can get our emails, just click the button below.</p>";
            $url = base_url().'driverregister/verify?email='.$email.'&token='.$token;

            $body .= '<a style="background-color:#0070e0;border:1px solid #0070e0;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:220px" href="'.$url.'" target="_blank" >Confirm my email address</a>';

            $mail_sent = $this->general_model->mail_setup($to,$cc,$from,$subject,$body);

            if($mail_sent == '1'){



                $user_insert_array=array(
                    'Name'=>$name,
                    'Email'=>$email,
                    'Password'=>MD5($Password),
                    'RoleId'=>$RoleId,
                    'Token'=>$user_token,
                    'Enable'=>'0',
                );

                $user_query = $this->driverregister_model->insert_user_data($user_insert_array);


                if($user_query)
                {
                    /*$name=$this->input->post('name');
                    $Building=$this->input->post('Building');
                    $Street=$this->input->post('Street');
                    $country=$this->input->post('country');
                    $Suburb=$this->input->post('Suburb');
                    $State=$this->input->post('State');
                    $Postcode=$this->input->post('Postcode');
                    $PhoneNo=$this->input->post('PhoneNo');
                    $licenceNo=$this->input->post('licenceNo');*/
                    $userId = $user_query;

                    $profile_target_path = 'assets/uploads/images/driver/';
                    $document_target_path = 'assets/uploads/images/document/';

                    if(isset($_FILES['ProfileImg']['name']) && ($_FILES['DocumentImg']['name']))
                    {
                        $ext = pathinfo($_FILES['ProfileImg']['name'], PATHINFO_EXTENSION);
                        $profile_name = $this->general_model->imagename($name);

                        $target_path = $profile_target_path.$profile_name.".".$ext;
                        $profile_target_path = $profile_name.".".$ext;
                        move_uploaded_file($_FILES["ProfileImg"]["tmp_name"], $target_path);

                        $document_ext = pathinfo($_FILES['DocumentImg']['name'], PATHINFO_EXTENSION);
                        $document_name = $this->general_model->imagename($name);
                        $target_path_document = $document_target_path.$document_name.".".$ext;
                        $document_target_path = $document_name.".".$document_ext;
                        move_uploaded_file($_FILES["DocumentImg"]["tmp_name"], $target_path_document);
                    }
                    $driver_insert_array=array(
                        'Phoneno'=>$PhoneNo,
                        'Building'=>$Building,
                        'Street'=>$Street,
                        'Country'=>$country,
                        'Suburb'=>$Suburb,
                        'State'=>$State,
                        'Postcode'=>$Postcode,
                        'LicenceNo'=>$licenceNo,
                        'ProfileImage'=>$target_path,
                        'DocumentImage'=>$target_path_document,
                        'EmailVerifyToken'=>$token,
                        'UserId'=>$userId
                    );

                    $driver_query = $this->driverregister_model->insert_driver_data($driver_insert_array);

                    if($driver_query)
                    {


                        /*$VehicleName=$this->input->post('vehiclename');
                        $VehicleNo=$this->input->post('vehicleno');*/

                        $driver_Id = $driver_query;
                        $vehicle_detail_array=array(
                            'DriverId'=>$driver_Id,
                            'VehicleType'=>$VehicleName,
                            'VehicleNumber'=>$VehicleNo
                        );
                        $driver_query = $this->driverregister_model->insert_vehicle_detail($vehicle_detail_array);
                        $msg='<div class="alert alert-success" role="alert"><strong>Please check your email for verify.</strong></div>';
                    }
                    else
                    {
                        $msg='<div class="alert alert-danger" role="alert"><strong>Something went wrong.</strong></div>';
                    }
                }
            }
            else
            {
                $msg='<div class="alert alert-danger" role="alert"><strong>Please Use another email.</strong></div>';
            }
            $flash_data = $this->session->set_flashdata('my_msg', $msg);
            redirect('login');
        }
    }
    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $result = $this->driverregister_model->verifydriver($email,$token);
        if($result == '1'){
            $msg='<div class="alert alert-success" role="alert"><strong>Email is verified. Please Login.</strong></div>';
        }else{
            $msg='<div class="alert alert-danger" role="alert"><strong>Email is not verified.</strong></div>';
        }

        $flash_data = $this->session->set_flashdata('my_msg', $msg);
        redirect('login');

    }

}
