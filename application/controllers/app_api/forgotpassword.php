<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class forgotpassword extends CI_Controller 
{
	function __construct() 
	{
        parent::__construct();
        $this->load->database();
        $this->load->model('forgotpassword_model');
        $this->load->model('general_model');
	    $general = $this->load->library('../controllers/general');
    }
	
	public function forgotPassword()
	{
		$Email = $this->input->post('Email');
		$RoleId = $this->input->post('RoleId');
		
		if(!empty($Email) && !empty($RoleId))
		{
			if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) 
			{
			    $msgch ="Please enter valid email address.";
			    $arr_json['success'] = "0";
			    $arr_json['message'] = $msgch;
			    echo json_encode($arr_json);exit;
			}
			if($RoleId==3 || $RoleId==4)
			{
				$token = $this->general->VersatileAccessToken();
				$chk_mail_roleId = $this->forgotpassword_model->chk_mail_roleId($Email,$RoleId);
				//echo "<pre>";print_r($chk_mail_roleId);exit;
				$listing = $chk_mail_roleId['result'];
				
				if($listing == 1)
				{
					$deliverys_result = $chk_mail_roleId['data'][0];
					$userId = $deliverys_result->Id;
					$role_Id = $deliverys_result->RoleId;
					$Name = $deliverys_result->Name;
					$to = $Email;
					$from = '';		
					$cc = '';
					$subject = 'SSTech Driver Forgot Password Email Verify';
					// $body = "<p>Hi,</p><p>It's time to confirm your email address.</p><p>Have we got the right email address to reach you on? To confirm that you can get our emails, just click the button below.</p>";
					
					// $body .= '<label>Token : </label> <input type="text" value="'.$token.'" style="background-color:#0070e0;border:1px solid #0070e0;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:220px;" readonly>';

					$body = "<div>
					        <p style='margin:0px !important;padding:0px !important;'><center><img src='http://lsdemoserver.com/sstechdriver/assets/images/logo-1.png' width='315' height='130'><center></p>
					        <p>Hello $Name,</p>
					        <p>We've received a request to reset your password. If you didn't make the request, just ignore this email. Otherwise, you can reset your password using the following Token.</p>
					        <table>
					         <tr>
					          <td><label>Token :</label></td>
					          <td><label type='text' style='width:100%;padding:8px;margin:4px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;' name='token' readonly>$token</label></td>
					         </tr>
					        </table>
					        <p>Thanks</p>
					        <p>The SSTECH Driver App Team</p>
					       </div>";
					
					$mail_sent = $this->general_model->mail_setup($to,$cc,$from,$subject,$body);
					
					if($mail_sent == '1')
					{
						if($role_Id==3)
						{
							$driver_ForgotEmailToken=array( 'ForgotEmailToken'=>$token);
							$driver_query = $this->forgotpassword_model->insert_driver_ForgotEmailToken($driver_ForgotEmailToken,$userId);
							if($driver_query ==1)
							{
								// $msgch ="Driver ForgotEmailToken inserted Successfully.";
								$msgch ="Verification token sent to your registed email.";
								$arr_json['success'] = "1";
								$arr_json['message'] = $msgch;
							}
							else
							{
								$msgch ="Something went Wrong.";
								$arr_json['success'] = "0";
								$arr_json['message'] = $msgch;
							}
						}
						if($RoleId==4)
						{
							$customer_ForgotEmailToken=array( 'ForgotEmailToken'=>$token);
							$customer_query = $this->forgotpassword_model->insert_customer_ForgotEmailToken($customer_ForgotEmailToken,$userId);
							
							if($customer_query ==1)
							{
								// $msgch ="customer ForgotEmailToken inserted Successfully.";
								$msgch ="Verification token sent to your registed email.";
								$arr_json['success'] = "1";
								$arr_json['message'] = $msgch;
							}
							else
							{
								$msgch ="Something went Wrong.";
								$arr_json['success'] = "0";
								$arr_json['message'] = $msgch;
							}
						}
					}
					else
					{
						$msgch ="Something went Wrong,Please Use another email.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
					}
				}
				else
				{
					$msgch ="No such email address found in DB.";
					$arr_json['success'] = "0";
					$arr_json['message'] = $msgch;
				}
			}
			else
			{
				$msgch ="Invalide User Type.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="All Fields Are Required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}
	
	public function forgotPassword_tokenmatch()
	{
		$NewPassword = $this->input->post('NewPassword');
		$ConfirmPassword = $this->input->post('ConfirmPassword');
		$RoleId = $this->input->post('RoleId');
		$ForgotEmailToken = $this->input->post('ForgotEmailToken');
		
		if(!empty($NewPassword) && !empty($ConfirmPassword) && !empty($RoleId) && !empty($ForgotEmailToken))
		{
			if(strlen(trim($NewPassword)) < 6 )
			{
				$msgch ="Please enter minimum 6 character in NewPassword.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
				echo json_encode($arr_json);exit;
			}
			if(strlen(trim($ConfirmPassword)) < 6 )
			{
				$msgch ="Please enter minimum 6 character in Confirm Password.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
				echo json_encode($arr_json);exit;
			}
			if($ConfirmPassword==$NewPassword)
			{
				$ins_pass=$NewPassword;
			}
			else
			{
				$msgch ="ConfirmPassword Mismatch.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
				echo json_encode($arr_json);exit;
				//$ins_pass=md5($NewPassword);
			}
			
			if($RoleId==3 || $RoleId==4)
			{
				if($RoleId==3)
				{
					$driver_ForgotEmailToken_match = $this->forgotpassword_model->chk_driver_frgtemailtoken($ForgotEmailToken,$RoleId);
					$DriverResult = $driver_ForgotEmailToken_match['result'];
					if($DriverResult ==1)
					{
						$final_result = $driver_ForgotEmailToken_match['data'][0];
						$UserId = $final_result->UserId;
						
						$update_driver_array=array('Password'=>md5($ins_pass));
						$update_Driver_pass = $this->forgotpassword_model->update_driver_pass($UserId,$update_driver_array);
						if($update_Driver_pass ==1)
						{
							$msgch ="Driver Password Update Successfully.";
							$arr_json['success'] = "1";
							$arr_json['message'] = $msgch;
						}
						else
						{
							$msgch ="Something went Wrong.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
						}
					}
					else
					{
						$msgch ="No Driver available with this Token.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
					}
				}
				if($RoleId==4)
				{
					$customer_ForgotEmailToken_match = $this->forgotpassword_model->chk_customer_frgtemailtoken($ForgotEmailToken,$RoleId);
					$CustomerResult = $customer_ForgotEmailToken_match['result'];
					if($CustomerResult ==1)
					{
						$final_result = $customer_ForgotEmailToken_match['data'][0];
						$UserId = $final_result->UserId;
						
						$update_cust_array=array('Password'=>md5($ins_pass),'UpdatedAt'=>date('Y-m-d H:i:s'));
						$update_cust_pass = $this->forgotpassword_model->update_pass($UserId,$update_cust_array);
						if($update_cust_pass ==1)
						{
							$msgch ="Customer Password Update Successfully.";
							$arr_json['success'] = "1";
							$arr_json['message'] = $msgch;
						}
						else
						{
							$msgch ="Something went Wrong.";
							$arr_json['success'] = "0";
							$arr_json['message'] = $msgch;
						}
					}
					else
					{
						$msgch ="No Customer available with this Token.";
						$arr_json['success'] = "0";
						$arr_json['message'] = $msgch;
					}
				}
			}
			else
			{
				$msgch ="Invalide User Type.";
				$arr_json['success'] = "0";
				$arr_json['message'] = $msgch;
			}
		}
		else
		{
			$msgch ="All Fields Are Required.";
			$arr_json['success'] = "0";
			$arr_json['message'] = $msgch;
		}
		echo json_encode($arr_json);
	}

}
