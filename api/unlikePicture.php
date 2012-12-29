<?php

//input::
//	pictureID
//	phone

//output::
//	number of likes after the photo was unliked

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$pictureID = $_GET['pictureID'];
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

mysql_query("DELETE FROM PictureLikes WHERE PictureID='$pictureID' AND Phone='$phone'");

$metrics->track('like_picture', array('unliker_phone'=>$phone,'unliked_picture'=>$pictureID,'distinct_id'=>$phone));

$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$pictureID'");

$count = mysql_fetch_row($result);

$count = $count[0];

$responseArray['likes'] = $count;

echo json_encode($responseArray);




?>