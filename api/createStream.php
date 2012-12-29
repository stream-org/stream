<?php

//input:: 
//	inviter's phone
//	streamName
//  invitee's phone numbers
//	medium

//output::
//	streamID

include "connection.php";

// //gets number standardization function
include "formatPhoneNumbers.php";

include "sendText.php";
// include "push.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

// //grabbing the arguments 
$phone = $_GET['phone'];
// echo $phone;
// echo '<br>';

$phone = standardizePhone($phone);
// echo $phone;
// echo '<br>';

$streamName = $_GET['streamName'];
// echo $streamName;
// echo '<br>';

$invitees = $_GET['invitees'];
// echo $invitees;
// echo '<br>';
$invitees = explode(',', $invitees);

$medium = $_GET['medium'];

$streamID = $streamName . time();
$streamID = hash('sha512', $streamID);

$responseArray = array();
//create the stream, and insert the creator into the stream 
mysql_query("INSERT INTO Streams (StreamName, StreamID, Phone) VALUES ('$streamName', '$streamID', '$phone')");

//gets the largest streamToUser value
$result = mysql_query("SELECT MAX(StreamToUser) FROM UserStreams WHERE Phone='$phone'");
$row = mysql_fetch_array($result);
$streamToUser = $row[0] + 1;

mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$phone', '$streamID','$streamToUser')");

$metrics->track('create_stream', array('medium'=>$medium,'stream_creator'=>$phone,'stream_created'=>$streamID,'num_invitees'=>count($invitees), 'distinct_id'=>$streamID));

//Invite invitees to stream
for ($i=0; $i < count($invitees) ; $i++) { 

	$currentPhone = standardizePhone($invitees[$i]);
	$url = 'http://75.101.134.112/api/invitePeople.php?inviteesPhone=' . $currentPhone . '&streamID=' . $streamID . '&inviterPhone=' . $phone;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

}

$responseArray['streamID'] = $streamID;

echo json_encode($responseArray);

?>