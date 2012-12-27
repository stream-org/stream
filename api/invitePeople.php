<?php

//input::
//	streamID
//	phone

//output::
//	Boolean (T/F)

include "sendText.php";

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];
$phoneArray = explode(',', $phone);
$responseArray = array();
$streamName;

//Gets streamName
$nameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
while($nameRow = mysql_fetch_array($nameResult))
{
	$streamName = $nameRow['StreamName'];
}

for ($i=0; $i < count($phoneArray) ; $i++) { 

	if (substr($phone,0,5 == 'Error'){

		$firstTimeUser = True;
		$currentPhone = standardizePhone($phoneArray[$i]);
		$textString;


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
	}
}



?>