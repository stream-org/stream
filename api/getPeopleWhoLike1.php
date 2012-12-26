<?php

//input::
//	picture

//output::
//  array of phone numbers of people who liked it 

include "connection.php";

//grabbing the arguments 
$picture = $_GET['picture'];

$result = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$picture'");
$responseArray = array();
$likersArray = array();
while ($row = mysql_fetch_array($result))
{
	$phone = $row['Phone'];
	$liker = array();
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while ($nameRow = mysql_fetch_array($nameResult))
	{
		// $name = $nameRow['First'] . ' ' . $nameRow['Last'];
		$liker['first'] = $nameRow['First'];
		$liker['last'] = $nameRow['Last'];
		$liker['phone'] = $phone;

		array_push($likersArray, $liker);

	}
}

$responseArray['picture'] = $picture;
$responseArray['likers'] = $likersArray;

echo json_encode($responseArray);

?>