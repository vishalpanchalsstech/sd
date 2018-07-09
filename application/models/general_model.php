<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class general_model extends CI_Model{
		function __construct(){
		parent::__construct();
	}
	
	/***************** get all company record********************/
	public function GetcompanyData(){
		$this->db->select('Id,Name');
		$this->db->from('companymaster');
        $this->db->where('Enable', 1);
		$query = $this->db->get();
		if($query){
			 $result = $query->result();
			 return $result;
		}else{
			return false;
		}
	}
	/***************** Check and email duplicate or validate********************/
	 public function emailvalidate($email,$RoleId)
	 {	  
	  $query = $this->db->query("SELECT um.Id FROM usermaster as um
	   join drivermaster as dm on dm.UserId = um.Id
	   WHERE um.Email = '$email' AND um.RoleId='$RoleId' AND dm.Enable=1");
	 // echo $this->db->last_query();
	  $querys = $query->result();
	  
	   if( $query->num_rows() > 0){
		    $Id = $querys['0']->Id;
			return $Id;
	   }else{
		   return '0';
	   }
	 }
	/***************** new name and add rand no********************/
	public function imagename($name){
		
		$name = str_replace(' ','', $name);
		$randno = mt_rand(1000, 9999);
		$imagename = $name.'-'.$randno;
		return $imagename;
	}
	/***************** get country name********************/
	public function Get_Records_country(){
		
		$this->db->select('*');
		$this->db->from('countriesmaster');
		$query = $this->db->get();
		$result = $query->result();
		return $result;	
	}
	
	 public function Get_country_Records($array='') {
  		if($array){
			$bind = 'where SortName in("'.$array.'")';
			}else{
			$bind = '';
		}
    $countrylist = $this->db->query("SELECT * FROM countriesmaster $bind ");
  	return ($countrylist->result());
	}
	
	/****************** Get State   ***************/
	public function Get_Records_state($country)
    {
		$this->db->distinct();
		$this->db->select('CountryStateId,State');
		$this->db->from('countrystate');
        $this->db->where('Country', $country);
        $query = $this->db->get();
		$result = $query->result();
		//print_r($result); exit;
		return $result;
    }
    public function Get_Records_city($state)
    {
        $this->db->select('CountryStateSuburbId,Suburb');
		$this->db->from('countrystatesuburb');
        $this->db->where('Country_StateId', $state);
		//$this->db->limit(20);
        $query = $this->db->get();
		
		//echo $this->db->last_query(); exit;
		$result = $query->result();
		//print_r($result); exit;
		return $result;
    }
	public function Like_Records_city($state,$keyword)
    {
        $this->db->select('CountryStateSuburbId,Suburb');
		$this->db->from('countrystatesuburb');
        $this->db->where('Country_StateId', $state);
		$this->db->like('Suburb', $keyword,'after');
		$this->db->limit(20);
        $query = $this->db->get();
		
		//echo $this->db->last_query(); exit;
		$result = $query->result();
		//print_r($result); exit;
		return $result;
    }
	/************ Unique token generate  **************/
	public function gettoken($length)
	{
	    $token = "";
	    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	    $codeAlphabet.= "0123456789";
	    $max = strlen($codeAlphabet); // edited

	    for ($i=0; $i < $length; $i++) {
	        $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
	    }

	    return $token;
	}
	public function crypto_rand_secure($min, $max)
	{
	    $range = $max - $min;
	    if ($range < 1) return $min; // not so random...
	    $log = ceil(log($range, 2));
	    $bytes = (int) ($log / 8) + 1; // length in bytes
	    $bits = (int) $log + 1; // length in bits
	    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	    do {
	        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
	        $rnd = $rnd & $filter; // discard irrelevant bits
	    } while ($rnd > $range);
	    return $min + $rnd;
	}
	
	public function mail_setup($to=array(), $cc=array(),$from='',$subject='', $body='', $alt_body='', $attachment=''){
		
			$toEmail = $to;
			//$ccEmail = $cc;
			//$from = $from;
			$subject  = $subject;
			$body = $body;
			$alt_body = $alt_body;
			//$attachment = $attachment;

			$config = array();
			//$config['api_key'] = "key-1551ce3d84c88fb58ee26cdb7b299287";
			$config['api_key'] = "key-2603536f2e69090cd25e46fcfb231971";
			//$config['api_url'] = "https://api.mailgun.net/v3/www.sstechsystem.com/messages";
			$config['api_url'] = "https://api.mailgun.net/v3/sandbox64ea3b959d1b422c85f7b960cc574072.mailgun.org/messages";
			$s_message = array();
			$s_message['from'] = 'SSTech Driver <info@sstechdriver.com>';
			$s_message['to'] = $toEmail;
		//	$s_message['cc'] = $ccEmail;
			$s_message['h:Reply-To'] = "noreply@sstechdriver.com";
			$s_message['subject'] = $subject;
			//$html = 	
			$s_message['html'] = $body;	
			$s_message['text'] = $alt_body;
			//print_r($s_message); exit;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $config['api_url']);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$s_message);
			$result = curl_exec($ch);
		
			curl_close($ch);
			//print_r($result); 
			$result =  json_decode($result);
			if($result){
					$status = '1';
				
			}else{
					$status = '0';
					
			}
			
				return $status;
	}

	public function get_all_location_details($countryCod,$keyword)
	{

		$limit = 20;
		$this->db->select('co.CountryStateId, co.State as stateName, sub.Suburb, sub.PostCode');
		$this->db->from('countrystatesuburb as sub');
		$this->db->join('countrystate as co','co.CountryStateId=sub.Country_StateId','left');
		$this->db->where(array('co.Country'=>$countryCod));
		$this->db->like('sub.Suburb', $keyword,'after');
		//$this->db->limit($limit);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result(); 
	}
	 
	public function check_customer_address()
	{
	   $country = $_POST['selectcountry'];
	   $countries = explode("-", $country);
	   $country_name = $countries[0];
	   $country_shortname = $countries[1];


		$query = $this->db->query('select * from countrystatesuburb where Country_StateId = (select CountryStateId from countrystate where State="'.$_POST['selectstate'].'" AND Country="'.$country_shortname.'")  AND Suburb="'.$_POST['selectcity'].'" AND  PostCode='.$_POST['selectpostcode'].'');
		//echo '<pre>'; print_r($query->result());
		if($query)
		{
			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
	    }
	 }

	 /******** Global token verification function for all master @v ***************/
	 public function verify_token($token,$type)
	 {	
	 	//echo $token;
	 	//echo $type;
		 if(!empty($token) && !empty($type)) {			 
		
			   if($type == "company"){
				   	
				   $cquery = $this->db->query("SELECT * FROM `companymaster` cm JOIN preferencesettings ps ON ps.CompanyId=cm.Id WHERE ps.Token = '$token' AND cm.Enable='1' ");
				   if($cquery){
					   $cresult = $cquery->result();
					   $co_cresult =  count($cresult);
					   if($co_cresult > 0){
						   
						   $myresult= array('result'=>1,'msg'=>"Data available",'data'=>$cresult);
					   }else{
						  $myresult= array('result'=>0,'msg'=>"Invalid Company"); 
					   }
					   
				   }else{
					   $myresult= array('result'=>0,'msg'=>"Something gone wrong. Please Try again"); 
				   }
				}
				elseif($type == "user"){
				   
				  $uquery = $this->db->query("SELECT * FROM `usermaster` WHERE Token = '$token' AND RoleId='2' AND Enable='1'");
				   if($uquery){
					   $uresult = $uquery->result();
					   $co_uresult =  count($uresult);
					   if($co_uresult > 0){
						   
						   $myresult= array('result'=>1,'msg'=>"Data available",'data'=>$uresult);
					   }else{
						  $myresult= array('result'=>0,'msg'=>"Invalid user"); 
					   }
					   
				   }else{
					   $myresult= array('result'=>0,'msg'=>"Something gone wrong. Please Try again"); 
				   }
				   
				   
			   }
			   elseif($type == "customer"){
				  //echo "yes";exit; 	
				  // $uquery = $this->db->query("SELECT * FROM `usermaster` WHERE Token = '$token' AND RoleId='4' AND Enable='1'");
					   	$uquery = $this->db->query("SELECT * FROM `usermaster` um 
		           JOIN customermaster cm ON cm.UserId=um.Id
		           WHERE um.Token = '$token' AND um.RoleId='4' AND um.Enable='1' AND cm.Enable='1'"
		             );
				  //echo $this->db->last_query();exit;

				   if($uquery){
					   $uresult = $uquery->result();

					   $co_uresult =  count($uresult);
					   if($co_uresult > 0){
						   
						   $myresult= array('result'=>1,'msg'=>"Data available",'data'=>$uresult);
					   }else{
						  $myresult= array('result'=>0,'msg'=>"Invalid user"); 
					   }
					   
				   }else{
					   $myresult= array('result'=>0,'msg'=>"Something gone wrong. Please Try again"); 
				   }
				   
				   
			   }
			   elseif($type == "driver"){
				   
				   $dquery = $this->db->query("SELECT * FROM `usermaster` um 
											JOIN drivermaster dm ON um.Id=dm.UserId
											JOIN vehiclemaster as vm on vm.DriverId = dm.Id
											WHERE um.Token = '$token' AND um.RoleId='3' AND um.Enable='1' AND dm.Enable='1'");
				   if($dquery){
					   $dresult = $dquery->result();
					   $co_dresult =  count($dresult);
					   if($co_dresult > 0){

					   	$dresult[0]->ProfileImage = base_url().$dresult[0]->ProfileImage;
					   	$dresult[0]->DocumentImage = base_url().$dresult[0]->DocumentImage;
						//print_r($dresult);exit;

						   $myresult= array('result'=>1,'msg'=>"Data available",'data'=>$dresult);
					   }else{
						  $myresult= array('result'=>0,'msg'=>"Invalid driver"); 
					   }
					   
				   }else{
					   $myresult= array('result'=>0,'msg'=>"Something gone wrong. Please Try again"); 
				   }
				   
				   
			   }
			   else{
				   $myresult= array('result'=>0,'msg'=>"Type mismatch"); 
			   }
		   }
		   else
		   {
			   $myresult= array('result'=>0,'msg'=>"Please add token or type."); 
		   }
	   	   
	   	   return $myresult;
	  
	 }	


	/*Insert Data into CompleteJob Table*/
	public function insert_completejob_data($completejob_array){
		
		$query = $this->db->insert('completejob',$completejob_array);
		$insert_id = $this->db->insert_id();
		if(!empty($insert_id)){
			$result=$insert_id;
		}
		else{
			$result=0;
		}
		return $result;
	}
	
	/*Insert Data into Review Table*/
	public function insert_review_data($review_array){
		
		$query = $this->db->insert('review',$review_array);
		$insert_id = $this->db->insert_id();
		if(!empty($insert_id)){
			$result=$insert_id;
		}
		else{
			$result=0;
		}
		return $result;
	}
	
	
	/*Get Completejob table data*/
	public function Get_completejob_data($last_id){
		
		$this->db->select("*");
		$this->db->from("completejob");
		$this->db->where('Id',$last_id);
		$query = $this->db->get();
		return $query->result(); 
		
	}
	
	/*Get review table data*/
	public function Get_review_data($last_id){
		
		$this->db->select("*");
		$this->db->from("review");
		$this->db->where('Id',$last_id);
		$query = $this->db->get();
		return $query->result(); 
		
	}
	
	/*Generate Random Password*/
	function random_password($length) 
	{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$password = array(); 
		$alpha_length = strlen($alphabet) - 1; 
		for ($i = 0; $i < $length; $i++) 
		{
			$n = rand(0, $alpha_length);
			$password[] = $alphabet[$n];
		}
		return implode($password); 
		
	}
	//echo random_password();

	public function api_log_data($apilog_insert_array)
	{
	  //echo '<pre>';print_r($apilog_insert_array);exit;
		  $query = $this->db->insert('api_logs',$apilog_insert_array);
		  if($query)
		  {
		   $result=1;
		  }
		  else
		  {
		   $result=0;
		  }
		  return $result;
	}
	
	public function apilogdata()
	{
	  $this->db->select('*');
	  $this->db->from('api_logs');
	  $this->db->order_by('api_logs.Id', 'DESC'); 
	  $query = $this->db->get();
	  //echo $this->db->last_query();exit;
	  $result = $query->result();
	  return $result;
	}

  	public function get_api_popup_data($myid)
 	{
	  //echo $myid;exit;
	  $this->db->select('*');
	  $this->db->from('api_logs');
	        $this->db->where('api_logs.Id',$myid);
	  $query = $this->db->get();
	  //echo $this->db->last_query();exit;
	  $result = $query->result();
	  return $result;
  
 	}

 	/****** Dashboard Count functions start @m ***************/
    public function get_customer()
    {
        $this->db->select("count(*) as customer");
        $this->db->where('um.RoleId',4);
        $this->db->where('um.Enable',1);
        $this->db->join('customermaster cm','cm.UserId=um.Id');
        $query = $this->db->get("usermaster um");
        if($query)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
    public function get_user()
    {
        $this->db->select("count(*) as user");
        $this->db->where('RoleId',2);
        $this->db->where('Enable',1);
        $query = $this->db->get("usermaster");
        if($query)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
    public function get_driver()
    {
        $this->db->select("count(*) as driver");
        $this->db->where('RoleId',3);
        $this->db->where('Enable',1);
        $query = $this->db->get("usermaster");
        if($query)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
    public function get_company()
    {
        $this->db->select("count(*) as company");
        $this->db->where('Enable',1);
        $query = $this->db->get("companymaster");
        if($query)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
    /****** Dashboard Count functions end @m ***************/

    /******* changes password for driver and customer common start @m ************/
    public function update_new_password($old_pwd,$new_pwd,$DriverUserId,$roleid)
 	{
		  // $id='';
		  // if(is_array($old))
		  // {
		  //  $id = $old[0]->Id;
		  // }
		  // else
		  // {  
		  //  $id = $old;
		  // }  
		  $this->db->where('Id',$DriverUserId);  
		  if($roleid==3){
		   $this->db->where('RoleId',3);   
		  }
		  elseif($roleid==4){
		   $this->db->where('RoleId',4);
		  }
		  $query = $this->db->update('usermaster',array('Password'=>MD5($new_pwd)));
		  if($query)
		  { 
		   return true;
		  }
		  else
		  {
		   return false;
		  }        
 	}
 
 	/*******This Function Use for the Check Old_Password Process*******/
 	public function old_password($old_pwd,$userid)
 	{
	  $this->db->select('*');
	  $this->db->where('Id', $userid);
	  $this->db->where('Password', md5($old_pwd));  
	  $query = $this->db->get('usermaster');
	  if($query)
	  {
	   return $query->result();
	  }
	  else
	  {
	   return false;
	  }
 	}
	
 /******* changes password for driver and customer common end @m ************/
 
 	/************ Get TimeZone from Compnay by company Id @v ************/
 	public function getTimeZoneCompanyWise($companyId){
 	  $this->db->select('TimeZone');
	  $this->db->where('Id',$companyId);
	  $this->db->where('Enable',1);  
	  $query = $this->db->get('companymaster');
	  if($query)
	  {
	   return $query->result();
	  }
	  else
	  {
	   return false;
	  }
 	}

 	/************ get name though token for apilog grid @k ************/
 	public function token_matching($RequestBy)
	{
	  
	  $this->db->select('Cm.Name,Ps.Token');
	  $this->db->from('preferencesettings as Ps');
	  $this->db->join('companymaster as Cm','Cm.Id=Ps.CompanyId');
	  $this->db->where('Ps.Token',$RequestBy);  
	  $query = $this->db->get();
	  //print_r($query);exit;
		  if($query->num_rows())
		  {
		   return $query->result();
		  }
		  else
		  {
			   $this->db->select('*');
			   $this->db->from('usermaster');
			   $this->db->where('Token',$RequestBy);  
			      $query = $this->db->get();
			   if($query->num_rows())
			   {
			      return $query->result();
			   }
			   else
			   {
			    return false;
			   }
		  }
	  
	}

	/*get customer details and driver details*/
  	public function get_customer_driverdetails($email,$roleId){
		   if($roleId==3){
		     $query = $this->db->query("SELECT * FROM `usermaster` um 
		           JOIN drivermaster dm ON um.Id=dm.UserId
		           WHERE um.Email = '$email' AND um.RoleId='$roleId' AND um.Enable='1' AND dm.Enable='1'");
		   }  
		   if($roleId==4){
		    $query = $this->db->query("SELECT * FROM `usermaster` um 
		           JOIN customermaster cm ON cm.UserId=um.Id
		           WHERE um.Email = '$email' AND um.RoleId='$roleId' AND um.Enable='1' AND cm.Enable='1'"
		             );
		   }
		  $result = $query->result();
		  if($result)  {
		   return $result;
		  }
		  else{
		   return false;
		  }    
    
  	}


}
?>