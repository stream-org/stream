<?php

//input::
//	picture
//	phone

//output::
//	JSON object of comments

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

include "viewComments.php"

//grabbing the arguments 
$pictureID = $_GET['pictureID'];
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$comment = $_GET['comment'];

mysql_query("INSERT INTO Comments (PictureID, Phone, Comment) VALUES ('$pictureID', '$phone', '$comment')");

$streamidResult = mysql_query("SELECT * FROM Comments WHERE PictureID='$pictureID' ORDER BY Created ASC");

while($streamidRow = mysql_fetch_array($streamidResult))
{
	$responseArray = array('phone'=>$streamidRow['Phone'], 'created'=>$streamidRow['Created'],'comment'=>$streamidRow['Comment']);
}

echo json_encode($responseArray);

?>