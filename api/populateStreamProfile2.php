<?php

//input:: 
//	streamID
//  phone

//output::
//  streamName
//	number of participants 
//	array of photos ranked chronologically 

include "connection.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

$responseArray = array();
$pictureArray = array();

$participantResult = mysql_query("SELECT COUNT(Distinct Phone) FROM UserStreams WHERE StreamID='$streamID'");

while($participantRow = mysql_fetch_array($participantResult))
{
	$numberOfParticipants = $participantRow[0];
}


$pictureResult = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$streamID' ORDER BY Created DESC");
while($pictureRow = mysql_fetch_array($pictureResult))
{
	$picture = array();
	$pictureID = $pictureRow['PictureID'];
	$TinyPicURL = $pictureRow['TinyPicURL'];
	$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$pictureID'");
	$numLikes = mysql_fetch_row($result);
	$numLikes = $numLikes[0];
	$picture['TinyPicURL'] = $TinyPicURL;
	$picture['numLikes'] = $numLikes;
	$picture['pictureID'] = $pictureID;
	array_push($pictureArray, $picture);
}

$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
while($streamNameRow=mysql_fetch_array($streamNameResult))
{
	$streamName = $streamNameRow['StreamName'];

}

$responseArray['streamID'] = $streamID;
$responseArray['streamName'] = $streamName;
$responseArray['numberOfParticipants'] = $numberOfParticipants;
$responseArray['pictures'] = $pictureArray;

echo json_encode($responseArray);

$metrics->track('view_stream', array('viewer'=>$phone,'stream_ID'=>$streamID,'stream_name'=>$streamName,'distinct_id'=>$phone.$streamName));

?>