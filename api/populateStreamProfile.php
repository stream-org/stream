<?php

//input:: 
//	streamID

//output::
//  streamName
//	number of participants 
//	array of photos ranked chronologically 

include "connection.php";

//grabbing the arguments 
$streamID = $_GET['streamID'];

$responseArray = array();
$pictureArray = array();

$participantResult = mysql_query("SELECT COUNT(Distinct Phone) FROM UserStreams WHERE StreamID='$streamID'");

while($participantRow = mysql_fetch_array($participantResult))
{
	$numberOfParticipants = $participantRow[0];
}


$pictureResult = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$streamID' ORDER BY Created ASC");
while($pictureRow = mysql_fetch_array($pictureResult))
{
	array_push($pictureArray, $pictureRow['Picture']);
}

$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
while($streamNameRow=mysql_fetch_array($streamNameResult))
{
	$streamName = $streamNameRow['StreamName'];

}

$responseArray['streamName'] = $streamName;
$responseArray['numberOfParticipants'] = $numberOfParticipants;
$responseArray['pictures'] = $pictureArray;

echo json_encode($responseArray);

?>