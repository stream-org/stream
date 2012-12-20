<?php

include('SimpleImage.php');
include('connection.php');

$phone = $_POST['phoneNumber'];
$streamID = $_POST['streamID'];

$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
$filename = $_FILES["file"]["name"];

move_uploaded_file($_FILES["file"]["tmp_name"], 'StreamPictures/Pictures/' . $filename);

$filePath = 'StreamPictures/Pictures/' . $filename;

$pictureFilePath = 'http://75.101.134.112/upload/StreamPictures/Pictures/' . $filename;
$tinyPictureFilePath = 'http://75.101.134.112/upload/StreamPictures/TinyPictures/' . $filename;


list($width, $height) = getimagesize($filePath);

if(intval($width) >= intval($height))
{
	chdir('StreamPictures');
	chdir('Pictures');
	$image = new SimpleImage();
	$image->load($filename);
	chdir('../');
	chdir('TinyPictures');
	$image->resizeToWidth(600);
	$image->save($filename);
}
else
{
	chdir('StreamPictures');
	chdir('Pictures');
	$image = new SimpleImage();
	$image->load($filename);
	chdir('../');
	chdir('TinyPictures');
	$image->resizeToHeight(600);
	$image->save($filename);
}

  $url = 'http://75.101.134.112/api/uploadPhoto.php?picture=' . $pictureFilePath . '&streamID=' . $streamID . '&phone=' . $phone . '&tiny=' . $tinyPictureFilePath;
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
  
?>