<?php

//input::
//	streamID
//	phone

//output::
//	array of photos user has uploaded

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$phone = $_GET['phone'];

$responseArray = array();


$result = mysql_query("SELECT * FROM StreamActivity WHERE Phone='$phone' AND StreamID='$streamID' ORDER BY Created");

while($row = mysql_fetch_array($result))
{
	array_push($responseArray, $row['Picture']);
}

echo json_encode($responseArray);

?>