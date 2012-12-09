<?php

include "connection.php";

//extracting data from the XML object
$rawMessage = (string) file_get_contents('php://input');
$simpleXML = simplexml_load_string($rawMessage);
$picture = (string)$simpleXML->images->image;
$phone = (string)$simpleXML->msisdn;
$message = (string)$simpleXML->message;

//putting the message in an array, so we can tell which command key the user typed in 
$message = trim($message);
$messageArray = explode(" ", $message);
$messageArray[0] = strtolower($messageArray[0]);

//function for sending a text
function sendText($phoneNumber, $textString){
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}


//register

if ($messageArray[0] === "register")
{
	$first = $messageArray[1];
	$last = $messageArray[2];
	$url = 'http://75.101.134.112/api/signup.php?first=' . $first . '&last=' . $last . '&phone=' . $phone;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

  	sendText($phone, "You're all signed up!");
}

//invite
elseif ($messageArray[0] === "invite")
{
	$inviteePhone = $messageArray[1];
	sendText($inviteePhone, "Reply with 'register', your_first_name, and your_last_name to signup!");
}

//upload
else 
{
	$streamID = 4;
	$url = 'http://75.101.134.112/api/uploadPhoto.php?phone=' . $phone . '&picture=' . $picture . '&streamID=' . $streamID;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

  	sendText($phone, "Your photo photo has been uploaded!");
}




?>