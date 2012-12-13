<?php

//input::
//	streamID
//	phone

//output::
//	Boolean (T/F)

function sendText($phoneNumber, $textString){
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

$phoneArray = explode(',', $phone);

$responseArray = array();

for ($i=0; $i < count($phoneArray) ; $i++) { 

	$currentPhone = $phoneArray[$i];
	mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$currentPhone', '$streamID')");
	$result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$currentPhone' and StreamID='$streamID'");
	$row = mysql_fetch_array($result);

	sendText($phone, "You have been invited to a stream! Reply with 'register', your_first_name, and your_last_name to signup!");

	echo json_encode($responseArray);
}



?>