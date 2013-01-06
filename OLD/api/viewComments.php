<?php

//input::
//	pictureID

//output::
//	JSON object of all comments

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$pictureID = $_GET['pictureID'];

$streamidResult = mysql_query("SELECT * FROM Comments WHERE PictureID='$pictureID' ORDER BY Created ASC");

while($streamidRow = mysql_fetch_array($streamidResult))
{
	$responseArray[$streamidRow['Created']] = array('phone'=>$streamidRow['Phone'],'comment'=>$streamidRow['Comment']);
}

echo json_encode($responseArray);

?>