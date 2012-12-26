<?php

//input:: 
//	phone
//	streamName

//output::
//	streamID

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

function sendText($phoneNumber, $textString)
{
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}

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

	$currentPhone = $invitees[$i];
	$internationalCode = false;
	$inTable = array();

	if (strlen($currentPhone) == 11)
	{
		$internationalCode = true;
	}

	$inTableResult = mysql_query("SELECT * FROM Users WHERE Phone='$currentPhone'");
	while($inTableRow = mysql_fetch_array($inTableResult))
	{
		$inTable['value'] = true;
	}

	if(empty($inTable))
	{
		if ($internationalCode)
		{
			$tempPhone = substr($currentPhone, 1);	
			$inTableResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone'");
			while($inTableRow = mysql_fetch_array($inTableResult))
			{
				$currentPhone = $tempPhone;
			}
		}
		else
		{
			$tempPhone = '1' . $currentPhone;
			$inTableResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone'");
			while($inTableRow = mysql_fetch_array($inTableResult))
			{
				$currentPhone = $tempPhone;
			}
		}
	}

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