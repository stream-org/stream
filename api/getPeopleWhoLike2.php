<?php

//input::
//	pictureID
//  viewer phone

//output::
//  array of phone numbers of people who liked it 

include "connection.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$pictureID = $_GET['pictureID'];
$viewerPhone = $_GET['phone'];

$result = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$pictureID'");
$responseArray = array();
$likersArray = array();
while ($row = mysql_fetch_array($result))
{
	$phone = $row['Phone'];
	$liker = array();
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while ($nameRow = mysql_fetch_array($nameResult))
	{
		// $name = $nameRow['First'] . ' ' . $nameRow['Last'];
		$liker['first'] = $nameRow['First'];
		$liker['last'] = $nameRow['Last'];
		$liker['phone'] = $phone;

		array_push($likersArray, $liker);

	}
}

$responseArray['pictureID'] = $pictureID;
$responseArray['likers'] = $likersArray;

echo json_encode($responseArray);

$metrics->track('view_streamers_who_like', array('viewer'=>$viewerPhone,'liked_picture'=>$pictureID,'distinct_id'=>$viewerPhone.$pictureID));

?>