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

mysql_query("INSERT INTO StreamActivity (StreamID, Phone, Picture) VALUES ('$streamID', '$phone','$picture')");

$result = mysql_query("SELECT * FROM StreamActivity WHERE Picture='$picture' AND Phone='$phone' AND StreamID='$streamID'");
$responseArray = array();

$row = mysql_fetch_array($result);

if (empty($row)) 
{
	$responseArray['value'] = 'false';
} 
else 
{
	$responseArray['value'] = 'true';
}

echo json_encode($responseArray);

?>