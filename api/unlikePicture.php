<?php

//input::
//	picture
//	phone

//output::
//	number of likes after the photo was unliked

include "connection.php";

//grabbing the arguments 
$picture = $_GET['picture'];
$phone = $_GET['phone'];

mysql_query("DELETE FROM PictureLikes WHERE PictureID='$picture' AND Phone='$phone'");

$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture'");

$count = mysql_fetch_row($result);

$count = $count[0];

$responseArray['likes'] = $count;

echo json_encode($responseArray);




?>