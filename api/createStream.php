<?php

//input:: 
//	phone
//	streamName

//output::
//	streamID

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

include "sendText.php";

include "connection.php";

//grabbing the arguments 
$phone = $_GET['phone'];

$phone = standardizePhone($phone);

$streamName = $_GET['streamName'];

$invitees = $_GET['invitees'];
$invitees = explode(',', $invitees);

$streamID = $streamName . time();
$streamID = hash('sha512', $streamID);

$responseArray = array();

//create the stream, and insert the creator into the stream 
mysql_query("INSERT INTO Streams (StreamName, StreamID, Phone) VALUES ('$streamName', '$streamID', '$phone')");
mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$phone', '$streamID')");


//Invite invitees to stream
for ($i=0; $i < count($invitees) ; $i++) { 

	$currentPhone = standardizePhone($invitees[$i]);

	$url = 'http://75.101.134.112/api/invitePeople.php?phone=' . $currentPhone . '&streamID=' . $streamID;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

}

$responseArray['streamID'] = $streamID;

echo json_encode($responseArray);

?>