<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pushnotification extends CI_Controller{
	function __construct(){
        parent::__construct();
        //$this->load->model('general_api_model');
        $this->load->model('login_model');
        //$this->load->model('pushnotification_model');
         // $this->load->library('../controllers/app_api/pushnotification'); 
         $this->load->library('../controllers/app_api/iospushnotification'); 
    }

    /* pushnotifaction send */
    function send($title='',$message='',$image='',$device_id=''){       
  		// $userdetails = $this->login_model->getuser($ReceiverId);
        // if(!empty($message) && !empty($userdetails[0]->FCMRegId)){    
    	if(!empty($message)){
            
        	// echo "yes";       
         	
         	// print_r($userdetails);
			// echo $userdetails[0]->FCMRegId;
         	// exit;
            /*
         	if(empty($image))
         	{
         		$image = "1.png";
         	}

         	$dataArr = array('device_id' => $device_id ,
         					 'message' => $message,
         					 'title' => $title,
         					 'image' => $image                             
         			 		);
           $fcmresponse =$this->SendNotification($dataArr);
           // print_r($dataArr);exit;
           // print_r($fcmresponse);exit;
           $NotificationMessage = array('Request' => $dataArr,
           								 'Respose' => $fcmresponse 	
           								 );
           $add_array = array(
				'SenderId'=>$SenderId,
				'ReceiverId'=>$ReceiverId,
				'NotificationMessage'=>json_encode($NotificationMessage),
				);
           $query = $this->pushnotification_model->SendPushnotification($add_array);
           // print_r($query);exit;
           $resultch = $query['result'];
           $msgch = $query['msg'];
         	
            if ($resultch == '1')
            {
                $arr_json['success'] = "1";
                $arr_json['msg'] = $msgch;
                $arr_json['fcmresponse'] = $fcmresponse;
                $arr_json['token'] = $device_id;
               
            }
            else
            {
                $arr_json['success'] = "0";
                $arr_json['msg'] = $msgch;
            }
            */
                   
        }else{
            $msgch ="Message or fcm register id not found!!";
            $arr_json['success'] = "0";
            $arr_json['msg'] = $msgch;

        }
        return json_encode($arr_json); 

    }
   

    //Define SendNotification function
    function sendNotification($dataArr) {
    	$fcmApiKey = 'AAAADqXYFLo:APA91bF3KylTi9oCQiHGngZLsR7QE1QCxQZITQGupbcxJ2XesSSCsNR-0_C3J9C3jSOD1W3RLkWg4e4r969UxIZm__CkQ98KkXpHGGsqFmI5H-hlhtin5xvmPB4UdApXngS_gIZCeMvo';//App API Key(This is google cloud messaging api key not web api key)
        $url = 'https://fcm.googleapis.com/fcm/send';//Google URL

    	$registrationIds = $dataArr['device_id'];//Fcm Device ids array

    	$title = $dataArr['title'];
    	$message = $dataArr['message'];//Message which you want to send
        $image = $dataArr['image'];
        if($image==""){
        	$image = "1.png";
        }
        // prepare the bundle
        $msg = array('title' => $title,
        			 'message' => $message,
        			 // 'is_background' => false,
        			 'image' =>$image,
        			 // 'payload' => '{"team":"India","score":"5.6"}',
        			 // 'timestamp' => date("Y-m-d h:i:s")
        			 );
        $fields = array('to'  => $registrationIds,'data' => $msg);
        
        $headers = array(
            'Authorization: key=' . $fcmApiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch); 
        // echo json_encode($fields);
        // print_r($result);
        // exit;
        return $result;
 
        // $ch = curl_init();
        // curl_setopt( $ch,CURLOPT_URL, $url );
        // curl_setopt( $ch,CURLOPT_POST, true );
        // curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        // curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        // curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        // $result = curl_exec($ch );
        // // Execute post
        // $result = curl_exec($ch);
        // if ($result === FALSE) {
        //     die('Curl failed: ' . curl_error($ch));
        // }
        // // Close connection
        // curl_close($ch);    
 
        // return $result;
    }

	/* pushnotifaction send */
    function groupsend($SenderId='',$ReceiverId='',$title='',$message='',$image='',$device_id=''){       
        
        // if(!empty($message) && !empty($userdetails[0]->FCMRegId)){    
        if(!empty($message)){    
            // echo "yes";       
            
            // print_r($userdetails);
            // echo $userdetails[0]->FCMRegId;
            // exit;
            if(empty($image))
            {
                $image = "1.png";
            }

            $dataArr = array('device_id' => $device_id ,
                             'message' => $message,
                             'title' => $title,
                             'image' => $image                             
                            );
           $fcmresponse =$this->sendNotificationgroup($dataArr);
           // print_r($dataArr);exit;
           // print_r($fcmresponse);exit;
           $NotificationMessage = array('Request' => $dataArr,
                                         'Respose' => $fcmresponse  
                                         );
           $add_array = array(
                'SenderId'=>$SenderId,
                'ReceiverId'=>$ReceiverId,
                'NotificationMessage'=>json_encode($NotificationMessage),
                );
           $query = $this->pushnotification_model->SendPushnotification($add_array);
           // print_r($query);exit;
           $resultch = $query['result'];
           $msgch = $query['msg'];
            
            if ($resultch == '1')
            {
                $arr_json['success'] = "1";
                $arr_json['msg'] = $msgch;
                $arr_json['fcmresponse'] = $fcmresponse;
                $arr_json['token'] = $device_id;
               
            }
            else
            {
                $arr_json['success'] = "0";
                $arr_json['msg'] = $msgch;
            }
                  
                   
        }else{
            $msgch ="Message or fcm register id not found!!";
            $arr_json['success'] = "0";
            $arr_json['msg'] = $msgch;

        }
        return json_encode($arr_json); 

    }

    //Define SendNotification function
    function sendNotificationgroup($dataArr) {
        $fcmApiKey = 'AAAADqXYFLo:APA91bF3KylTi9oCQiHGngZLsR7QE1QCxQZITQGupbcxJ2XesSSCsNR-0_C3J9C3jSOD1W3RLkWg4e4r969UxIZm__CkQ98KkXpHGGsqFmI5H-hlhtin5xvmPB4UdApXngS_gIZCeMvo';//App API Key(This is google cloud messaging api key not web api key)
        $url = 'https://fcm.googleapis.com/fcm/send';//Google URL

        $registrationIds = $dataArr['device_id'];//Fcm Device ids array

        $title = $dataArr['title'];
        $message = $dataArr['message'];//Message which you want to send
        $image = $dataArr['image'];
        if($image==""){
            $image = "1.png";
        }
        // prepare the bundle
        $msg = array('title' => $title,
                     'message' => $message,                    
                     'image' =>$image,                     
                     );
        $fields = array('registration_ids'  => $registrationIds,'data' => $msg);
        
        $headers = array(
            'Authorization: key=' . $fcmApiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch); 
        // echo json_encode($fields);
        // print_r($result);
        // exit;
        return $result; 
      
    }
    
}
?>