<?php

//input::
//	picture

//output::
//  array of phone numbers of people who liked it 

include "connection.php";

//grabbing the arguments 
$pictureID = $_GET['pictureID'];

$result = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$pictureID'");
$responseArray = array();
while ($row = mysql_fetch_array($result))
{
	$phone = $row['Phone'];
	$name;
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$name = $nameRow['First'] . ' ' . $nameRow['Last'];
	}

	array_push($responseArray, $name);
}

echo json_encode($responseArray);

?>