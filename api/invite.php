<?php

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

mysql_query("INSERT INTO UserStreams (Phone, StreamID)	
	VALUES ('$phone, $streamID')");


$responseArray = array();
$responseArray['value'] = 'true';

echo json_encode($responseArray);

?>