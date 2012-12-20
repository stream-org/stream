<?php

//input::
//	phone number
//	picture_url 
//	stream_id
//  tinyPicture url

//output::
//	pictureID or null

include "connection.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$picture = $_GET['picture'];
$tiny = $_GET['tiny'];
$streamID = $_GET['streamID'];
$pictureID = $picture . $phone . $streamID . time();
$pictureID = hash('sha512', $pictureID);

mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL) VALUES ('$streamID', '$phone','$picture', '$pictureID', '$tiny')");

$result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture' AND Phone='$phone' AND StreamID='$streamID'");
$responseArray = array();

$row = mysql_fetch_array($result);

if (empty($row)) 
{
	$responseArray = null;
} 
else 
{
	$responseArray['pictureID'] = $pictureID;
}

echo json_encode($responseArray);



?>