<?php

include "connection.php";

//grabbing the arguments 
$picture = $_GET['picture'];
// $picture = urlencode($picture);
$phone = $_GET['phone'];

mysql_query("INSERT INTO PictureLikes (Picture, Phone) VALUES ('$picture', '$phone')");

$result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE Picture='$picture'");

$count = mysql_fetch_row($result);

$count = $count[0];

$responseArray['likes'] = $count;

echo json_encode($responseArray);

?>