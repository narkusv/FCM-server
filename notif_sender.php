<?php
require 'DB.php';

$helper = new DB();

//Set up data for notification
$load = array(
    'title' => "",
    'message' => "",
	'icon' => "",
    'link' => ""
);

//Payload configuration
$load['title'] = $_POST['notifTitle'];
$load['message'] = $_POST['notifMsg'];
$load['icon'] = $_POST['notifIcon'];
$load['link'] = $_POST['notifUrl'];

//Get 2D array of recipients
$recipients = $helper->GetGCMUsers($_POST['appsToSend']);

	//While iterate through array: 
	if(isset($recipients)){
		//Iterate trough 1nd dimension of array and pass 2nd dimension to sendNotification
		foreach($recipients as $recipientChunk){
			sendNotification($recipientChunk, $load);
		}
		
		
	}
		//call sendNotification();
		




function sendNotification($userIds, $msg)
    {
		// Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

		//Retreive regId's from DB.
		//Set up data.
        $fields = array(
            'registration_ids' => $userIds,
            'data' => $msg,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, true));
        // Execute post
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $status = "";
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            $status = "FAIL";
        }

        $report = array();
        // Close connection
        curl_close($ch);
		
		echo $result;
		file_put_contents("log.txt", $result);

    }





?>