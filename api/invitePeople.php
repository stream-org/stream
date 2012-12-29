<?php

//input::
//	streamID
//	invitees phone
//  inviter phone

//output::
//	Boolean (T/F)

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

include "sendText.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$streamID = $_GET['streamID'];
$inviteesPhone = $_GET['inviteesPhone'];
$inviterPhone = $_GET['inviterPhone'];
$phoneArray = explode(',', $inviteesPhone);
$responseArray = array();
$streamName;

//Gets streamName
$nameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
while($nameRow = mysql_fetch_array($nameResult))
{
	$streamName = $nameRow['StreamName'];
}

for ($i=0; $i < count($phoneArray) ; $i++) { 

	if (substr($phoneArray[$i],0,5) != 'Error'){

		$firstTimeUser = True;
		$currentPhone = standardizePhone($phoneArray[$i]);
		$textString;


		//if phone is user with password send one notif
		//if phone with no hash, another notif 
		//if no phone, send register 

		mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$currentPhone', '$streamID')");

		$metrics->track('invite_person', array('inviter'=>$inviterPhone,'stream_created'=>$streamID,'num_invitees'=>count($invitees), 'distinct_id'=>$currentPhone));

		mysql_query("INSERT INTO Users (Phone, InvitedBy) VALUES ('$currentPhone', '$inviterPhone')");


		echo $currentPhone;
		echo '<br>';

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