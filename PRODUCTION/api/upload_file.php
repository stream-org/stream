<?PHP

// This API is used to upload files from either the iPhone or the Mobile Web Layer

// Input: Picture
// Output: nothing output if file sucessfully uploads. Else returns error in dictionary form

include('dependecies.php');

$phone = $_POST['phoneNumber'];
$streamID = $_POST['streamID'];


// Check if a photo was even uploaded
if(($_FILES["file"]["size"] > 10) && ($_FILES["file"]["size"] < 20000000))
{


	$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
	$filename = $_FILES["file"]["name"];

	move_uploaded_file($_FILES["file"]["tmp_name"], 'StreamPictures/Pictures/' . $filename);

	$filePath = 'StreamPictures/Pictures/' . $filename;

	$pictureFilePath = 'http://75.101.134.112/upload/StreamPictures/Pictures/' . $filename;
	$tinyPictureFilePath = 'http://75.101.134.112/upload/StreamPictures/TinyPictures/' . $filename;


	list($width, $height) = getimagesize($filePath);

	chdir('../');
	chdir('../');
	chdir('upload');
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

	$url = 'http://75.101.134.112/api/uploadPhoto.php?picture=' . $pictureFilePath . '&streamID=' . $streamID . '&phone=' . $phone . '&tiny=' . $tinyPictureFilePath;
	$ch = curl_init($url);
	$response = curl_exec($ch);
	curl_close($ch);
}

//Send back an error of a photo was not found
else
{
	$errorArray = array();

	$errorArray['error'] = "Photo did not upload";

	echo json_encode($errorArray);
}

?>