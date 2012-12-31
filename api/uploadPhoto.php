<?php

//input::
//	phone number
//	picture_url 
//	stream_id
//  tinyPicture url
//	caption

//output::
//	pictureID or null

include('SimpleImage.php');
include('connection.php');	
include('push.php');


//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$picture = $_GET['picture'];
$tiny = $_GET['tiny'];
$streamID = $_GET['streamID'];
$caption = $_GET['caption'];
$pictureID = $picture . $phone . $streamID . time();
$pictureID = hash('sha512', $pictureID);

//For testing photo upload notification
if($picture == "test"){

  	photopush($phone, $streamID);

}

// Logic for photos uploaded through text

elseif ($tiny == ""){


	echo "Mogreet!";
	echo "<br>Picture: ".$picture;

	$val = time().$picture;
	$filename = hash('sha512', $val) . '.jpg';
	$tinyfilename = "tiny".$filename;

	echo "<br> filename: ".$filename;

	exec("touch ".$filename);
	exec("chmod go+w ".$filename);
	$command = "wget -O ".$filename." ".$picture;
	exec($command);
	exec("mv ".$filename." ~/html/upload/StreamPictures/Pictures/");

	$filePath = '~/html/upload/StreamPictures/Pictures/' . $filename;

	$pictureFilePath = 'http://75.101.134.112/upload/StreamPictures/Pictures/' . $filename;
	$tinyPictureFilePath = 'http://75.101.134.112/upload/StreamPictures/TinyPictures/' . $tinyfilename;
	

	list($width, $height) = getimagesize($filePath);

	if(intval($width) >= intval($height))
	{
		
		chdir('../');
		chdir('upload');
		chdir('StreamPictures');
		chdir('Pictures');		
		$image = new SimpleImage();
		$image->load($filename);
		chdir('../');	
		chdir('TinyPictures');
		$image->resizeToWidth(600);
		$image->save($tinyfilename);
	}
	else
	{
		chdir('../');
		chdir('upload');
		chdir('StreamPictures');
		chdir('Pictures');
		$image = new SimpleImage();
		$image->load($filename);
		chdir('../');
		chdir('TinyPictures');
		$image->resizeToHeight(600);
		$image->save(tinyfilename);
	}

	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL) VALUES ('$streamID', '$phone', '$pictureID','$pictureFilePath', '$tinyPictureFilePath')");

	photoPush($phone, $streamID);

	$metrics->track('upload_photo', array('medium'=>'text','uploader'=>$phone,'stream'=>$streamID,'picture'=>$picture,'distinct_id'=>$pictureID));

 }


else{
	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL, Caption) VALUES ('$streamID', '$phone', '$pictureID', '$picture','$tiny','$caption')");

	photoPush($phone, $streamID);

	$metrics->track('upload_photo', array('medium'=>'iPhone','uploader'=>$phone,'stream'=>$streamID,'picture'=>$picture,'distinct_id'=>$pictureID));

	$result = mysql_query("SELECT * FROM StreamActivity WHERE PicURL='$picture' AND Phone='$phone' AND StreamID='$streamID'");
	$responseArray = array();

	$row = mysql_fetch_array($result);

	if (empty($row)) 
	{
		$responseArray = null;
	} 
	else 
	{
		$responseArray['pictureID'] = $pictureID;
	}

	echo json_encode($responseArray);
}
?>