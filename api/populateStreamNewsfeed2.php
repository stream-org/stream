<?php

//input:phone_number

//output:: 
//	viwer's phone number
//	stream_id
//	stream_name
//	# of participants 
//	# of pictures
//	most recent photo 

include "connection.php";


//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
	
$streamID;
$streamName;
$numberOfParticipants;
$numberOfPictures;
$latestPicture;

$responseArray = array();

$streamidResult = mysql_query("SELECT * FROM UserStreams WHERE Phone='$phone'");
while($streamidRow = mysql_fetch_array($streamidResult))
{
	$streamID = $streamidRow['StreamID'];

	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$streamName = $streamNameRow['StreamName'];
	}

	$participantResult = mysql_query("SELECT COUNT(Distinct Phone) FROM UserStreams WHERE StreamID='$streamID'");
	while($participantRow = mysql_fetch_array($participantResult))
	{
		$numberOfParticipants = $participantRow[0];
	}

	$pictureResult = mysql_query("SELECT COUNT(PictureID) FROM StreamActivity WHERE StreamID='$streamID'");
	while($pictureRow = mysql_fetch_array($pictureResult))
	{
		$numberOfPictures = $pictureRow[0];
	}
	$latestPictureArray = array();
	$latestPictureResult = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$streamID' ORDER BY Created DESC LIMIT 1");
	while($latestPictureRow = mysql_fetch_array($latestPictureResult))
	{
		
		$latestPictureArray['url'] = $latestPictureRow['TinyPicURL'];
		$latestPictureArray['id'] = $latestPictureRow['PictureID'];
	}

	$responseArray[$streamID] = array('phone'=>$phone,'streamName'=>$streamName, 'numberOfParticipants'=>$numberOfParticipants, 'numberOfPictures'=>$numberOfPictures, 'latestPicture'=>$latestPictureArray);
}

echo json_encode($responseArray);

$metrics->track('view_stream_news_feed', array('viewer'=>$phone,'distinct_id'=>$phone));
	
?>
