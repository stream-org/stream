<?PHP

// This API is used to upload files from either the iPhone or the Mobile Web Layer

// Input: Picture
// Output: nothing output if file sucessfully uploads. Else returns error in dictionary form

include('dependencies.php');

$uploader_phone = $_POST['uploader_phone'];
$stream_id = $_POST['stream_id'];


// Check if a photo was even uploaded
if(($_FILES["file"]["size"] > 10) && ($_FILES["file"]["size"] < 20000000))
{


	$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
	$filename = $_FILES["file"]["name"];

	chdir('../');
	chdir('../');
	chdir('upload');

	$result = move_uploaded_file($_FILES["file"]["tmp_name"], 'StreamPictures/Pictures/' . $filename);

	echo "<br>";

	echo $result;
	echo "<br>";

	echo $filename;

	$filePath = 'StreamPictures/Pictures/' . $filename;

	$pictureFilePath = 'http://75.101.134.112/upload/StreamPictures/Pictures/' . $filename;
	$tinyPictureFilePath = 'http://75.101.134.112/upload/StreamPictures/TinyPictures/' . $filename;


	list($width, $height) = getimagesize($filePath);

	
	chdir('StreamPictures');
	chdir('Pictures');
	$image = new SimpleImage();
	$image->load($filename);
	chdir('../');
	chdir('TinyPictures');

	if(intval($width) >= intval($height))
	{
		$image->resizeToWidth(600);
	}
	else
	{
		$image->resizeToHeight(600);
	}
	$image->save($filename);


	$url = 'http://75.101.134.112/stream/api/uploadPhoto.php?picture_url=' . $pictureFilePath . '&stream_id=' . $stream_id . '&uploader_phone=' . $uploader_phone . '&tiny_picture_url=' . $tinyPictureFilePath;
	$ch = curl_init($url);
	$response = curl_exec($ch);
	curl_close($ch);
}

//Send back an error of a photo was not found
else
{
	$output = array();

	$output['error'] = "Photo did not upload";

	echo json_encode($output);
}

?>