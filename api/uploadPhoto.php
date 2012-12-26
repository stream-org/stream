<?php

//input::
//	phone number
//	picture_url 
//	stream_id
//  tinyPicture url

//output::
//	pictureID or null

include('SimpleImage.php');
include('connection.php');

//grabbing the arguments 
$phone = $_GET['phone'];
$picture = $_GET['picture'];
$tiny = $_GET['tiny'];
$streamID = $_GET['streamID'];
$pictureID = $picture . $phone . $streamID . time();
$pictureID = hash('sha512', $pictureID);

// Logic for photos uploaded through text

if ($tiny = "null"){


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

  	$url = 'http://75.101.134.112/api/notification.php?phone=' . $phone . '&streamID=' . $streamID;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

 }

else{
	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL) VALUES ('$streamID', '$phone', '$pictureID', '$picture','$tiny')");

	$url = 'http://75.101.134.112/api/notification.php?phone=' . $phone . '&streamID=' . $streamID;
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

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