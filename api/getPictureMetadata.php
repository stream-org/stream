<?php

//input::
//	picture_url

//output:: 
//	picture_url 
//	number of people who like it
//	name and phone of person who uploaded the photo

include "connection.php";

//grabbing the arguments 
$picture = $_GET['picture'];
$phone = $_GET['phone'];
$numberOfLikes;
$uploaderPhone;
$uploaderNameFirst;
$uploaderNameLast;
$hasLiked = "False";

$responseArray = array();

$responseArray['hasLiked'] = $hasLiked;

$likeResult = mysql_query("SELECT COUNT(Distinct Phone) FROM PictureLikes WHERE Picture='$picture'");
while ($likeRow = mysql_fetch_array($likeResult))
{
	$numberOfLikes = $likeRow[0];
	$responseArray['numberOfLikes'] = $numberOfLikes;
}

$hasLikedResult = mysql_query("SELECT * FROM PictureLikes WHERE Picture='$picture' AND Phone='$phone'");
while ($hasLikedRow = mysql_fetch_array($hasLikedResult))
{
	if(!empty($hasLikedRow))
	{
		$hasLiked = 'True';
		$responseArray['hasLiked'] = $hasLiked;
	}
}

$uploaderPhoneResult = mysql_query("SELECT * FROM StreamActivity WHERE Picture='$picture'");
while($uploaderPhoneRow = mysql_fetch_array($uploaderPhoneResult))
{
	$uploaderPhone = $uploaderPhoneRow['Phone'];
	$responseArray['uploaderPhone'] = $uploaderPhone;
}

$uploaderNameResult = mysql_query("SELECT * FROM Users WHERE Phone='$uploaderPhone'");
while($uploaderNameRow = mysql_fetch_array($uploaderNameResult))
{
	$uploaderNameFirst = $uploaderNameRow['First'];
	$uploaderNameLast = $uploaderNameRow['Last'];
	$responseArray['uploaderFirstName'] = $uploaderNameFirst;
	$responseArray['uploaderLastName'] = $uploaderNameLast;
}

$responseArray['picture_url'] = $picture;

echo json_encode($responseArray);

?>