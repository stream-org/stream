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



for ($i=0; $i < count($invitees) ; $i++) { 

	$firstTimeUser = True;
	$textString;

	$currentPhone = standardizePhone($invitees[$i]);

	//if phone is user with password send one notif
	//if phone with no hash, another notif 
	//if no phone, send register 

	mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$currentPhone', '$streamID')");

	$userResult = mysql_query("SELECT * FROM Users WHERE Phone='$currentPhone'");

	while ($userRow = mysql_fetch_array($userResult)) 
	{
		if ($userRow['First'] !== '' && $userRow['HashString'] == '')
		{
			$textString = "You've been invited to a Stream! Open the Stream app to view " . $streamName;
			sendText($currentPhone, $textString);
			$firstTimeUser = False;
		}

		if ($userRow['First'] !== '' && $userRow['HashString'] !== '')
		{
			$textString = "You've been invited to a Stream! Open the Stream app to view " . $streamName;
			sendText($currentPhone, $textString);
			$firstTimeUser = False;
		} 
	}

	if ($firstTimeUser) {

		$textString = "You've been invited to a Stream! Open the Stream app to view " . $streamName;
		sendText($currentPhone, $textString);
	}

}

$responseArray['streamID'] = $streamID;

echo json_encode($responseArray);

?>