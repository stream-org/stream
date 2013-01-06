<?php

//input:: 
//	phone
//	streamID
//	pictureID

//output::
//	none

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

$streamID = $_GET['streamID'];

$pictureID = $_GET['pictureID'];

//Removes users pictures from Stream
mysql_query("DELETE FROM StreamActivity WHERE Phone = '$phone' AND StreamID = '$streamID' AND PictureID = '$pictureID'");

echo "User's picture: ".$pictureID." has been removed from the stream: ".$streamID;

?>