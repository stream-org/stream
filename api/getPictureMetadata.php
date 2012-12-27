<?php

//input::
//	picture_url

//output:: 
//	picture_url 
//	number of people who like it
//	name and phone of person who uploaded the photo

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$pictureID = $_GET['pictureID'];
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$numberOfLikes;
$uploaderPhone;
$uploaderNameFirst;
$uploaderNameLast;
$hasLiked = "False";

$responseArray = array();

$responseArray['hasLiked'] = $hasLiked;

// Gets like information on the picture and the user viewing the picture

$likeResult = mysql_query("SELECT COUNT(Distinct Phone) FROM PictureLikes WHERE PictureID='$pictureID'");
while ($likeRow = mysql_fetch_array($likeResult))
{
	$numberOfLikes = $likeRow[0];
	$responseArray['numberOfLikes'] = $numberOfLikes;
}

$hasLikedResult = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$pictureID' AND Phone='$phone'");
while ($hasLikedRow = mysql_fetch_array($hasLikedResult))
{
	if(empty($hasLikedRow)) 
	{
		//do nothing
	}
	{
		$hasLiked = 'True';
		$responseArray['hasLiked'] = $hasLiked;
	}
}

// Gets Uploader's information

$uploaderPhoneResult = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$pictureID'");
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

// Gets comments
$streamidResult = mysql_query("SELECT * FROM Comments WHERE PictureID='$pictureID' ORDER BY Created ASC");

$tempResponseArray;

while($streamidRow = mysql_fetch_array($streamidResult))
{
	$tempResponseArray[$streamidRow['Created']] = array('phone'=>$streamidRow['Phone'],'comment'=>$streamidRow['Comment']);
}

$responseArray['Comments'] = $tempResponseArray;

$responseArray['picture_url'] = $picture;

echo json_encode($responseArray);

?>