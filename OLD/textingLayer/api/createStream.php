<?php

//input:: 
//	phone
//	streamName

//output::
//	streamID

include "connection.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$streamName = $_GET['streamName'];
$streamID = $streamName . time();
$streamID = hash('sha512', $streamID);


mysql_query("INSERT INTO Streams (StreamName, StreamID) VALUES ('$streamName', '$streamID')");

mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$phone', '$streamID')");

$responseArray = array();
$result = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

while ($row = mysql_fetch_array($result))
{
	$streamID = $row['StreamID'];
	$responseArray['StreamID'] = $streamID;
}		

echo json_encode($responseArray);

?>