<?php

include('SimpleImage.php');

$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
$filename = $_FILES["file"]["name"];

move_uploaded_file($_FILES["file"]["tmp_name"], 'Pictures/testStream/' . $filename);

$filepath = 'Pictures/testStream/' . $filename;

list($width, $height) = getimagesize($filepath);
echo $width;
echo '<br>';
echo $height;

if(intval($width) >= intval($height))
{
	// exec("sudo mogrify -resize 600x " . $filepath);
	// echo $filepath;
	chdir('Pictures');
	chdir('testStream');
	$image = new SimpleImage();
	$image->load($filename);
	$image->resizeToWidth(600);
	$image->save('newPic2.jpg');
}
else
{
	chdir('Pictures');
	chdir('testStream');
	$image = new SimpleImage();
	$image->load($filename);
	$image->resizeToHeight(600);
	$image->save('newPic2.jpg');
}

// header('Location:http://75.101.134.112/upload/Pictures/testStream/' . $filename);

?>