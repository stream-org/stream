<?php

//input::
//	streamID
//	phone

//output::
//	Boolean (T/F)

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
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

$phoneArray = explode(',', $phone);
$responseArray = array();
$streamName;

$nameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
while($nameRow = mysql_fetch_array($nameResult))
{
	$streamName = $nameRow['StreamName'];
}

for ($i=0; $i < count($phoneArray) ; $i++) { 

	$firstTimeUser = True;
	$currentPhone = $phoneArray[$i];
	$textString;

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
	// $result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$currentPhone' and StreamID='$streamID'");
	// $row = mysql_fetch_array($result);

	echo $currentPhone;
	echo '<br>';

	$userResult = mysql_query("SELECT * FROM Users WHERE Phone='$currentPhone'");
	// $userR = mysql_fetch_array($userResult);
	// echo $userR;
	
	// $userRow = mysql_fetch_array($userResult);

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
			sendText("6508420492", $textString);
			$firstTimeUser = False;
		} 
	}

	if ($firstTimeUser) {

		$textString = "You've been invited to a Stream! Open the Stream app to view " . $streamName;
		sendText($currentPhone, $textString);
	}


	// sendText($phone, "You have been invited to a stream! Reply with 'register', your_first_name, and your_last_name to signup!");
	// array_push($responseArray, $currentPhone . ' : true');

	// echo json_encode($responseArray);
}



?>