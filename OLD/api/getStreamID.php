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
$streamToUser = $_GET['streamToUser'];
$responseArray = array();

//getting streamID based on streamName
if ($streamToUser == ''){
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
}

//Getting streamID based on streamToUser number
else{

	$result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$phone' AND StreamToUser = '$streamToUser'");
	$row=mysql_fetch_array($result);
	$streamID = $row['StreamID'];

	echo $streamID;

}

?>