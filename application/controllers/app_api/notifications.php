<?php 
// Server file
defined('BASEPATH') OR exit('No direct script access allowed');

class notifications extends CI_Controller{
	// (Android)API access key from Google API's Console.
	private static $API_ACCESS_KEY = 'AIzaSyDG3fYAj1uW7VB-wejaMJyJXiO5JagAsYI';
	// (iOS) Private key's passphrase.
	private static $passphrase = '123456789';
	// (Windows Phone 8) The name of our push channel.
    private static $channelName = "joashp";

	function __construct(){
        parent::__construct();
        //$this->load->model('general_api_model');
        $this->load->model('login_model');
        //$this->load->model('pushnotification_model');
         // $this->load->library('../controllers/app_api/pushnotification'); 
         
    }

    public function index()
    {
    	echo "Yes <br>".$_SERVER['DOCUMENT_ROOT'].'/application/controllers/app_api/'.'ck.pem';
    }

    // Sends Push notification for Android users
	public static function android($data, $reg_id) 
	{
		$API_ACCESS_KEY = 'AAAAnj4waSg:APA91bFei6tXbirD7bpDOFjj55L2gkP9qnDgypJ5A1ym70GXDo0J8k1wtE7w1nEnwKmRy2YNmz07oh87ElZGG_xGXvPy7mZFW3xkQMSM74uXyvp4Hm1-Wvxs_JkCqaQqx_cUAnQEKgCnK4CxcS0G3w6QleVikbH1lA';
	        $url = 'https://android.googleapis.com/gcm/send';
	        $message = array(
	            'title' => $data['mtitle'],
	            'message' => $data['mdesc'],
	            'subtitle' => '',
	            'tickerText' => '',
	            'msgcnt' => 1,
	            'vibrate' => 1,
	            'text' => $data['text']
	        );
	        
		$headers = array('Authorization: key='.$API_ACCESS_KEY,'Content-Type: application/json','charset:utf-8');
			//print_r($headers);exit;
	        $fields = array(
	            'registration_ids' => array($reg_id),
	            'data' => $message,
	        );
	
	    	return notifications::useCurl($url, $headers, json_encode($fields));
    }
	
	// Sends Push's toast notification for Windows Phone 8 users
	public function WP($data, $uri) {
		$delay = 2;
		$msg =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		        "<wp:Notification xmlns:wp=\"WPNotification\">" .
		            "<wp:Toast>" .
		                "<wp:Text1>".htmlspecialchars($data['mtitle'])."</wp:Text1>" .
		                "<wp:Text2>".htmlspecialchars($data['mdesc'])."</wp:Text2>" .
		            "</wp:Toast>" .
		        "</wp:Notification>";
		
		$sendedheaders =  array(
		    'Content-Type: text/xml',
		    'Accept: application/*',
		    'X-WindowsPhone-Target: toast',
		    "X-NotificationClass: $delay"
		);
		
		$response = $this->useCurl($uri, $sendedheaders, $msg);
		
		$result = array();
		foreach(explode("\n", $response) as $line) {
		    $tab = explode(":", $line, 2);
		    if (count($tab) == 2)
		        $result[$tab[0]] = trim($tab[1]);
		}
		
		return $result;
	}
	
        // Sends Push notification for iOS users
	public static function iOS($data, $devicetoken) {
	//public function iOS() {
		$deviceToken = $devicetoken;
		//$deviceToken = '893a4f678f4afe279a4aea19226b6c51cf2d1c05375a27f6e725f9f26c9e39f7';
		$passphrase = '123456789';
		//$ctx = stream_context_create();
		// ck.pem is your certificate file
		//stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
		//stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
		//echo $_SERVER['DOCUMENT_ROOT'].'/sstechdriver/application/controllers/app_api/ck.pem';exit;
		$ctx = stream_context_create();
		/**** Server ********/
		// stream_context_set_option($ctx, 'ssl', 'local_cert', $_SERVER['DOCUMENT_ROOT'].'/application/controllers/app_api/'.'ck.pem');
		/**** localhost ********/
		stream_context_set_option($ctx, 'ssl', 'local_cert', $_SERVER['DOCUMENT_ROOT'].'/sstechdriver/application/controllers/app_api/'.'ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		/*$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
			    //'title' => $data['mtitle'],
                //'body' => $data['mdesc'],
                 'title' => "abc",
                'body' => "test",
			 ),
			'sound' => 'default'
		);
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		
		// Close the connection to the server
		fclose($fp);
		if (!$result)
			return 'Message not delivered' . PHP_EOL;
		else
			return 'Message successfully delivered' . PHP_EOL;*/
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		$result= 'Connected to APNS' . PHP_EOL;
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
				'title' => $data['mtitle'],
				'body' => $data['mdesc'],//$message,
				'action-loc-key' => 'Bango App',
			),
			'message' => $data['text'],
			//'badge' => 2,
			'badge' => 1,
			'sound' => 'oven.caf',
			);
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		if (!$result)
			$result = 'Message not delivered' . PHP_EOL;
		else
			$result = 'Message successfully delivered' . PHP_EOL;
		// Close the connection to the server
		return json_encode($result);
		fclose($fp);
	}
	
	// Curl 
	//public static function useCurl(&$model, $url, $headers, $fields = null) 
	public static function useCurl($url, $headers, $fields = null) 
	{
	        // Open connection
	        $ch = curl_init();
	        if ($url) {
	            // Set the url, number of POST vars, POST data
	            //print_r($url);exit;
	            curl_setopt($ch, CURLOPT_URL, $url);
	            curl_setopt($ch, CURLOPT_POST, true);
	            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	     
	            // Disabling SSL Certificate support temporarly
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	            if ($fields) {
	                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	            }
	     
	            // Execute post
	            $result = curl_exec($ch);
	            if ($result === FALSE) {
	                die('Curl failed: ' . curl_error($ch));
	            }
	     
	            // Close connection
	            curl_close($ch);
				// print_r($result);exit;
	            return $result;
        }
    }
    
}
?>