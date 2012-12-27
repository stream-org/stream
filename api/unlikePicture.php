<?php

//input::
//	picture
//	phone

//output::
//	number of likes after the photo was unliked

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$pictureID = $_GET['pictureID'];
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

mysql_query("DELETE FROM PictureLikes WHERE PictureID='$pictureID' AND Phone='$phone'");

$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$pictureID'");

$count = mysql_fetch_row($result);

$count = $count[0];

$responseArray['likes'] = $count;

echo json_encode($responseArray);




?>