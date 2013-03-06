<?PHP

// This API is used to upload files from either the iPhone or the Mobile Web Layer

//input::
//	stream_id
//	uploader_phone
//output::
// api_name
// If successful upload form and calls upload_picture.php which outputs:
// 		-status
//  	-picture_id
// 		-picture
// Else:
// 		-status (error)

// example:
// http://75.101.134.112/stream/1.0/api/upload.html

include('dependencies.php');

$uploader_phone = $_POST['uploader_phone'];
$stream_id = $_POST['stream_id'];

// Instantiate the class
$s3 = new S3($AccessKey, $SecretKey);

// Check if a photo was even uploaded
if(($_FILES["file"]["size"] > 10) && ($_FILES["file"]["size"] < 20000000))
{

	// Grabs the picture - POST
	$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
	$filename = $_FILES["file"]["name"];
	$tinyPicfilename = "tiny" . $filename;
	$image = $_FILES['file']['tmp_name']; 

	$pictureFilePath = 'https://s3.amazonaws.com/stream_pictures/' . $filename;
	$tinyPictureFilePath = 'https://s3.amazonaws.com/stream_tiny_pictures/' . $tinyPicfilename;

	// uploads file to S3 uner bucket stream-pictures
	$s3->putObjectFile($image, "stream_pictures" , $filename, S3::ACL_PUBLIC_READ);



	list($width, $height) = getimagesize($image);

	echo $width;
	echo $height;

	$resizeimage = new SimpleImage();
	$resizeimage->load($image);


	// resizes picture to tiny picture (1024x1024)
	if(intval($width) >= intval($height))
	{
		$resizeimage->resizeToWidth(1024);
	}
	else
	{
		$resizeimage->resizeToHeight(1024);
	}

	chdir('../');
	chdir('../');
	chdir('../');
	chdir('upload');
	chdir('StreamPictures');
	chdir('TinyPictures');

	$resizeimage->save($tinyPicfilename);

	$fileUpload = '/var/www/html/upload/StreamPictures/TinyPictures/'. $tinyPicfilename;

	echo $fileUpload;
	
	$s3->putObjectFile($fileUpload, "stream_tiny_pictures" , $tinyPicfilename , S3::ACL_PUBLIC_READ);

	// destroys the file, muahhahahaha

	echo $fileUpload;

	unlink($fileUpload);

	// calls uploadPhoto to upload file paths to database
	$url = 'http://75.101.134.112/stream/1.0/api/upload_picture.php?picture_url=' . $pictureFilePath . '&stream_id=' . $stream_id . '&uploader_phone=' . $uploader_phone . '&picture_tinyurl=' . $tinyPictureFilePath;
	$ch = curl_init($url);
	$response = curl_exec($ch);
	curl_close($ch);
}

//Send back an error of a photo was not found
else
{
	$output = array();

	$output['status'] = "error";
	$output['error_description'] = "Photo did not upload!";
	$output['api_name'] = "upload_file";
	echo json_encode($output);
}

?>