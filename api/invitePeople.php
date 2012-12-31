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
// include "push.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//Google Voice texing
include "nonMogreetText.php";

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
	if (substr($phoneArray[$i],0,5) != 'Error')
	{
		$currentPhone = standardizePhone($phoneArray[$i]);

		// Check if user is already part of the stream		
		$userInStreamExistsResult = mysql_query("SELECT * FROM UserStreams WHERE Phone='$currentPhone' AND StreamID = '$streamID'");
		$userInStreamExistsRow = mysql_fetch_array($userInStreamExistsResult);

		if(empty($userInStreamExistsRow))
		{
			$userExistsResult = mysql_query("SELECT Phone FROM Users WHERE Phone='$currentPhone'");
			$userExistsRow = mysql_fetch_array($userExistsResult);


			//New User 
			if(empty($userExistsRow)){

				// Get first name for inviter
				$inviterResult = mysql_query("SELECT First FROM Users WHERE Phone='$inviterPhone'");
				$inviterRow = mysql_fetch_array($inviterResult);
				$inviter = $inviterRow[0];
				
				mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$currentPhone', '$streamID',1)");
				mysql_query("INSERT INTO Users (Phone, InvitedBy) VALUES ('$currentPhone', '$inviterPhone')");

				$textString = $inviter." invited you to Stream! Stream lets you easily share pictures with your close friends. See what ".$inviter." uploaded here: bit.ly/12Dy6u5";

				nonMogreetText($currentPhone,$textString);
			}

			//Already a user
			else{
				$maxStreamToUserResult = mysql_query("SELECT MAX(StreamToUser) FROM UserStreams WHERE Phone='$currentPhone'");
				$maxStreamToUserRow = mysql_fetch_array($maxStreamToUserResult);
				$streamToUser = $maxStreamToUserRow[0] + 1;

				mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$currentPhone', '$streamID','$streamToUser')");
			}
		}

	}
}
$metrics->track('invite_person', array('inviter'=>$inviterPhone,'stream_created'=>$streamID,'num_invitees'=>count($invitees), 'distinct_id'=>$currentPhone));

// invitePush($inviterPhone, $streamID);


?>