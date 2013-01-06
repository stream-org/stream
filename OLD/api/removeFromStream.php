<?php

//input:: 
//	phone
//	streamID

//output::
//	none

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

$streamID = $_GET['streamID'];

//Removes users pictures from Stream
mysql_query("DELETE FROM  StreamActivity WHERE Phone = '$phone' AND StreamID = '$streamID'");

echo "User's pictures removed from Stream.";

//Removes user from Stream
mysql_query("DELETE FROM  UserStreams WHERE Phone = '$phone' AND StreamID = '$streamID'");

echo "User removed from Stream.";

?>