<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class apilog extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('general_model');
			$general = $this->load->library('../controllers/general');
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
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
			$data['GetApiData'] = $this->general_model->apilogdata();
			//echo '<pre>';print_r($data['GetApiData']);exit;
			$data['section'] = array('apilog_view');
			$this->general->load_my_view($data);
		}
		public function api_view_Getdata($myid)
		{
			//$GetApiData = $this->general_model->apilogdata();
			$All_ApiTrace = $this->general_model->get_api_popup_data($myid);
			$All_ApiTrace=$All_ApiTrace[0];

			$ResponseCode=$All_ApiTrace->ResponseCode;
			if($ResponseCode ==1)
			{
				$ResponseCode = "Success";
			}
			else
			{
				$ResponseCode = "Failure";
			}
			
			$RequestBy = $All_ApiTrace->RequestBy;
			$requestby_name = $this->general_model->token_matching($RequestBy);
		    $requestby_name = $requestby_name[0]->Name;
			//$apiurl=$All_ApiTrace->ApiUrl;
			//print_r($All_ApiTrace);exit;
		   /***** order detail html *******/
			$order_html = '';
				//$order_html .= '<div class="col-md-12">';				   
				$order_html .= '
				<style>
				@media (min-width: 768px){
				.modal-dialog {
				    width: 768px;				    
				}
				}
				</style>
				<div class="col-md-12"><table class="popup_view" >  
				<tr> 
				<td><b> API URL  </b></td>
				<td> : &nbsp</td>
				<td> '  .$All_ApiTrace->ApiUrl .'</td>
				</tr>
				<tr> 
					<tr> 
				<td><b> RequestType  </b></td>
				<td> : &nbsp</td>
				<td> '  .$All_ApiTrace->RequestType .'</td>
				</tr>
				</table><br></div>';
				$order_html .= '<div class="col-md-12"><div class="api_log_center"><h4><span style="font-weight:bold;">Request Headers :</span></h4>';
				$order_html .= '<table class="popup_view"  style="background:rgb(245,245,245);border:2px solid #cccccc;">  
				<tr> 
				   <td style="padding:15px;line-height:2;    word-wrap: break-word;
    word-break: break-all;">'  .$All_ApiTrace->Request .'<br>
					</tr>
				  </table></div><br><br></div>';
				  $order_html .= '<div class="col-md-12"><div class="api_log_center"><h4><span style="font-weight:bold;">Response :</span></h4>';
				$order_html .= '<table class="popup_view"  style="background:rgb(245,245,245);border:2px solid #cccccc;">  
				<tr> 
				   <td style="padding:15px;line-height:2;    word-wrap: break-word;
    word-break: break-all;">'  .$All_ApiTrace->Response .'<br>
					</tr>
				  </table></div><br></div>';
				  $order_html .= '<div class="col-md-12">
				<table class="popup_view" >  
				<tr> 
				<tr> 
				<td><b> ResponseCode  </b></td>
				<td> : &nbsp</td>
				<td> '  .$ResponseCode .'</td>
				</tr>
				
				<tr> 
				<td><b> OriginIP  </b></td>
				<td> : &nbsp</td>
				<td> '  .$All_ApiTrace->OriginIP .'</td>
				</tr>
				<tr> 
				<td><b> RequestBy  </b></td>
				<td> : &nbsp</td>
				<td> '  .$requestby_name .'</td>
				</tr>
				<tr> 
				<td><b> ResponseTime  </b></td>
				<td> : &nbsp</td>
				<td> '  .$All_ApiTrace->ResponseTime .'</td>
				</tr>
				<tr> 
				<td><b> CreatedAt  </b></td>
				<td> : &nbsp</td>
				<td> '  .$All_ApiTrace->CreatedAt .'</td>
				</tr>
				</table><br></div>';
				$html = $order_html;
		 
		   echo json_encode($html);
		   //return $order_html;		
		}

		
 
	}
	?>
    
    
    