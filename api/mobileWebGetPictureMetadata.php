<?php

//input::
//	pictureID
//	phone

//output:: 
//	picture URL ['picture_url']
//  picture ID ['pictureID']
//	number of people who like it ['numberOfLikes']
//  whether the user has liked the photo ['hasLiked']
//	name of person who uploaded the photo ['uploaderFirstName'] ['uploaderLastName'] 
//  phone of person who uploaded the photo ['uploaderPhone']
// 	photo's comments ['Comments']

include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$picture = $_GET['picture'];
$pictureID = 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$numberOfLikes;
$uploaderPhone;
$uploaderNameFirst;
$uploaderNameLast;
$hasLiked = "False";

$responseArray = array();

$responseArray['hasLiked'] = $hasLiked;

//Get the pictureID 

$pictureIDResult = mysql_query("SELECT * FROM StreamActivity WHERE TinyPicURL='$picture'");

while($pictureIDRow = mysql_fetch_array($pictureIDResult))
{
	$pictureID = $pictureIDRow['PictureID'];
}

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
	else{
		$hasLiked = 'True';
		$responseArray['hasLiked'] = $hasLiked;
	}
}

// Gets Uploader's and Caption information

$uploaderPhoneResult = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$pictureID'");
while($uploaderPhoneRow = mysql_fetch_array($uploaderPhoneResult))
{
	$uploaderPhone = $uploaderPhoneRow['Phone'];
	$responseArray['uploaderPhone'] = $uploaderPhone;
	$responseArray['caption'] = $uploaderPhoneRow['Caption'];
}

$uploaderNameResult = mysql_query("SELECT * FROM Users WHERE Phone='$uploaderPhone'");
while($uploaderNameRow = mysql_fetch_array($uploaderNameResult))
{
	$uploaderNameFirst = $uploaderNameRow['First'];
	$uploaderNameLast = $uploaderNameRow['Last'];
	$uploaderName = $uploaderNameFirst." ".$uploaderNameLast;
	$responseArray['uploaderFirstName'] = $uploaderNameFirst;
	$responseArray['uploaderLastName'] = $uploaderNameLast;
}

// Gets comments
$streamidResult = mysql_query("SELECT * FROM Comments WHERE PictureID='$pictureID' ORDER BY Created ASC");

$tempResponseArray= array();



while($streamidRow = mysql_fetch_array($streamidResult))
{
	$tempResponseArray[$streamidRow['Created']] = array('phone'=>$streamidRow['Phone'],'comment'=>$streamidRow['Comment']);
}
$responseArray['Comments'] = $tempResponseArray;


//Gets full sized picture

$uploaderNameResult = mysql_query("SELECT PicURL FROM StreamActivity WHERE PictureID='$pictureID'");
while($uploaderNameRow = mysql_fetch_array($uploaderNameResult))
{
	$pictureURL = $uploaderNameRow['PicURL'];
}


$responseArray['picture_url'] = $pictureURL;
$responseArray['pictureID'] = $pictureID;

echo json_encode($responseArray);

$metrics->track('view_picture', array('viewer_phone'=>$phone,'uploader_phone'=>$uploaderPhone,'uploader_name'=>$uploaderName, 'picture'=>$pictureURL,'distinct_id'=>$phone));

?>