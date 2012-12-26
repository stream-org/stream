<?php

//input::
//	phone
//  streamName

//output::
//	streamID

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$streamName = $_GET['streamName'];
$responseArray = array();

$result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$phone'");

while($row=mysql_fetch_array($result))
{
	$streamID = $row['StreamID'];
	$anotherResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
	while($anotherRow = mysql_fetch_array($anotherResult))
	{
		$tempStreamName = $anotherRow['StreamName'];
		if($tempStreamName === $streamName)
		{
			echo $streamID;
		}
	}
}

?>