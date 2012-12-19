<?php

//input::
//	phone number
//	picture_url 
//	stream_id

//output::
//	boolean t/f

include "connection.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$picture = $_GET['picture'];
$streamID = $_GET['streamID'];
$pictureID = $picture . $phone . $streamID . time();
$pictureID = hash('sha512', $pictureID);

mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID) VALUES ('$streamID', '$phone','$picture')");

$result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture' AND Phone='$phone' AND StreamID='$streamID'");
$responseArray = array();

$row = mysql_fetch_array($result);

if (empty($row)) 
{
	$responseArray['value'] = 'false';
} 
else 
{
	$responseArray['pictureID'] = $pictureID;
}

echo json_encode($responseArray);



?>