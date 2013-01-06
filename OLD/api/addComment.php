<?php

//input::
//	pictureID
//	phone
//	comment

//output::
//	JSON object of comments

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
$comment = $_GET['comment'];


mysql_query("INSERT INTO Comments (PictureID, Phone, Comment) VALUES ('$pictureID', '$phone', '$comment')");

$metrics->track('add_comment', array('commentor'=>$phone,'picture'=>$pictureID,'distinct_id'=>$phone));

$streamidResult = mysql_query("SELECT * FROM Comments WHERE PictureID='$pictureID' ORDER BY Created ASC");

while($streamidRow = mysql_fetch_array($streamidResult))
{
	$responseArray[$streamidRow['Created']] = array('phone'=>$streamidRow['Phone'],'comment'=>$streamidRow['Comment']);
}

echo json_encode($responseArray);

?>