<?php

//input::
//	streamID
//	invitees phone
//  inviter phone

//output::
//	Boolean (T/F)

include "connection.php";

// //gets number standardization function
include "formatPhoneNumbers.php";
include "push.php";

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

for ($i=0; $i < count($phoneArray); $i++) 
{ 
	echo 'made it here';

	if (substr($phoneArray[$i],0,5) != 'Error')
	{

		$firstTimeUser = True;
		$currentPhone = standardizePhone($phoneArray[$i]);
		$textString;


		mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$currentPhone', '$streamID')");

		$metrics->track('invite_person', array('inviter'=>$inviterPhone,'stream_created'=>$streamID,'num_invitees'=>count($invitees), 'distinct_id'=>$currentPhone));

		mysql_query("INSERT INTO Users (Phone, InvitedBy) VALUES ('$currentPhone', '$inviterPhone')");

		invitePush($inviterPhone, $streamID);
	}
}



?>