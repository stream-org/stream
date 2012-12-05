<?php

//input::
//	streamID
//	phone

//output::
//	Boolean (T/F)

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

$responseArray = array();

mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$phone', '$streamID')");
$result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$phone' and StreamID='$streamID'");
$row = mysql_fetch_array($result);

if(empty($row))
{
	$responseArray['value'] = 'false';
}
else 
{
	$responseArray['value'] = 'true';
}

echo json_encode($responseArray);

?>