<?php

//input::
//	streamID

//output::
//	array of
//		phone number 
//		name
//		how many photos they have uploaded 

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];
$responseArray = array();
$firstName;
$lastName;
$phone;
$numberOfPhotos;

$result = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID'");
while($row = mysql_fetch_array($result))
{
	$phone = $row['Phone'];
	
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while($nameRow = mysql_fetch_array($nameResult))
	{
		$firstName = $nameRow['First'];
		$lastName = $nameRow['Last'];
	}

	$photoResult = mysql_query("SELECT COUNT(Picture) FROM StreamActivity WHERE StreamID='$streamID' AND Phone='$phone'");
	while($photoRow = mysql_fetch_array($photoResult))
	{
		$numberOfPhotos = $photoRow[0];
	}

	$responseArray[$phone] = array('firstName'=>$firstName, 'lastName'=>$lastName, 'numberOfPhotos'=>$numberOfPhotos);

}

echo json_encode($responseArray);

?>