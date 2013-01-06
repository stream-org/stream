<?php

//input::
//	streamID
//  viewer phone

//output::
//	array of
//		phone number 
//		name
//		how many photos they have uploaded 

include "connection.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");



//grabbing the arguments 
$streamID = $_GET['streamID'];
$viewerPhone = _GET['phone'];
$responseArray = array();
$firstName;
$lastName;
$phone;
$numberOfPhotos;

$usersInStream = array();

$metrics->track('view_people_in_stream', array('viewer_phone'=>$viewerPhone,'stream_ID'=>$streamID,'distinct_id'=>$viewer_phone.$streamID));

$result = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID'");
while($row = mysql_fetch_array($result))
{
	$phone = $row['Phone'];
	
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while($nameRow = mysql_fetch_array($nameResult))
	{
		$firstName = $nameRow['First'];
		$lastName = $nameRow['Last'];
	}

	$photoResult = mysql_query("SELECT COUNT(PictureID) FROM StreamActivity WHERE StreamID='$streamID' AND Phone='$phone'");
	while($photoRow = mysql_fetch_array($photoResult))
	{
		$numberOfPhotos = $photoRow[0];
	}

	$userProf = array('phone'=>$phone,'first'=>$firstName, 'last'=>$lastName, 'numberOfPhotos'=>$numberOfPhotos);
	array_push($usersInStream,$userProf);

}

$responseArray['streamID'] = $streamID;
$responseArray['participants'] = $usersInStream;

echo json_encode($responseArray);

?>