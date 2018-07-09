/*
    		Header
    		Content-Type:application/json
			apiKey:SST100000000003		
			Body
			{
				 "jobId": "SSTVJOB10029"        		
			}			
		*/
		$request_data = json_decode(file_get_contents('php://input'),true);
		$header =getallheaders();
		//print_r($header);exit;
		/****** Check the header and request type and body @v *******/
		if($header['Content-Type'] != "application/json")
		{
			$message['success'] = false; 
			$message['message'] =  'Invalid content-type in header only json allowed.';
			echo json_encode($message); exit;
		}
		
		if(empty($header['apiKey']))
		{
			$message['success'] = false; 
			$message['message'] =  'Missing apikey in header.';
			echo json_encode($message); exit;
		}
		
		if(!$this->isJson($request_data)){
				$error = json_last_error_msg();
				$message['success'] = false; 
				$message['message'] =  'Not valid JSON string ($error)';
				echo json_encode($message); exit;
				//echo "Not valid JSON string ($error)";exit;
		}	

		if(!isset($request_data) && empty($request_data))
		{
			$message['success'] = false; 
			$message['message'] =  'Missing request data.';
			echo json_encode($message); exit;
		}	