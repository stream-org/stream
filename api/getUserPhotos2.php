<?php

//input::
//	streamID
//	streamer phone
//  viewer phone

//output::
//	array of photos user has uploaded

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$streamID = $_GET['streamID'];
$streamerPhone = $_GET['streamerPhone'];
$viewerPhone = $_GET['viewerPhone'];
$phone = standardizePhone($streamerPhone);

$responseArray = array();

$pictureArray = array();



$result = mysql_query("SELECT * FROM StreamActivity WHERE Phone='$phone' AND StreamID='$streamID' ORDER BY Created");

while($row = mysql_fetch_array($result))
{
	$tempArray = array();
	$tempArray['url'] = $row['TinyPicURL'];
	$tempArray['id'] = $row['PictureID'];
	array_push($pictureArray, $tempArray);
}

$responseArray['streamID'] = $streamID;
$responseArray['phone'] = $phone;
$responseArray['pictures'] = $pictureArray;


echo json_encode($responseArray);

$metrics->track('view_streamer', array('viewer_phone'=>$viewerPhone,'streamer_phone'=>$streamerPhone,'stream'=>$streamID,'distinct_id'=>$phone));

?>