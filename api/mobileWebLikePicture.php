<?php

//input::
//	pictureID
//	phone

//output::
//	number of people who like it 

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";
include('push.php');

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$picture = $_GET['picture'];
$pictureID;
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

//get the pictureID from the picture 

$pictureIDResult = mysql_query("SELECT * FROM StreamActivity WHERE PicURL='$picture'");

while($pictureIDRow = mysql_fetch_array($pictureIDResult))
{
	$pictureID = $pictureIDRow['PictureID'];
}

mysql_query("INSERT INTO PictureLikes (PictureID, Phone) VALUES ('$pictureID', '$phone')");

//send like push notification
likePush($phone, $pictureID);

$metrics->track('like_picture', array('liker_phone'=>$phone,'liked_picture'=>$pictureID,'distinct_id'=>$phone));

$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$pictureID'");

$count = mysql_fetch_row($result);

$count = $count[0];

$responseArray['likes'] = $count;

echo json_encode($responseArray);

?>