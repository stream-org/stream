<?PHP

// Uploads the location of a picture_url to the database if sent through either the iPhone app or Mobile Web Layer
// Saves the photo if photo uploaded through text

//input::
//	uploader_phone
//	picture_url 
//	stream_id
//  tinyPicture url
//	caption

//output::
//  api_name
// 	status
//	uploader_phone
//	picture_url
//	picture_tinyurl
//	stream_id
//	caption

// example:
// http://75.101.134.112/stream/1.0/api/upload.html

include('dependencies.php');

// //Mixpanel Tracking
// $metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 

if(empty($_GET))
{
	$uploader_phone = $_POST['uploader_phone'];
	$uploader_phone = standardizePhone($uploader_phone);
	$picture_url = $_POST['picture_url'];
	$picture_tinyurl = $_POST['picture_tinyurl'];
	$stream_id = $_POST['stream_id'];
	$caption = $_POST['caption'];
	$picture_id = $picture_url . $uploader_phone . $stream_id . time();
	$picture_id = hash('sha512', $picture_id);

}

if(empty($_POST))
{

	$uploader_phone = $_GET['uploader_phone'];
	$uploader_phone = standardizePhone($uploader_phone);
	$picture_url = $_GET['picture_url'];
	$picture_tinyurl = $_GET['picture_tinyurl'];
	$stream_id = $_GET['stream_id'];
	$caption = $_GET['caption'];
	$picture_id = $picture_url . $uploader_phone . $stream_id . time();
	$picture_id = hash('sha512', $picture_id);
}

//For testing photo upload notification
if($picture_url == "test"){

  	photopush($uploader_phone, $stream_id);

}

// Logic for photos uploaded through text

elseif ($picture_tinyurl == ""){

	$val = time().$picture_url;
	$filename = hash('sha512', $val) . '.jpg';
	$tinyfilename = "tiny".$filename;

	exec("touch ".$filename);
	exec("chmod go+w ".$filename);
	$command = "wget -O ".$filename." ".$picture_url;
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
		$image->resizeToWidth(1024);
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
		$image->resizeToHeight(1024);
		$image->save(tinyfilename);
	}

	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL,Caption, IsActive) VALUES ('$stream_id', '$uploader_phone', '$picture_id','$pictureFilePath', '$tinyPictureFilePath','$caption',1)");

	uploadPicturePushNotification($uploader_phone, $stream_id, $picture_id);

	$metrics->track('upload_photo', array('medium'=>'text','uploader'=>$uploader_phone,'stream'=>$stream_id,'picture_url'=>$picture_url,'distinct_id'=>$uploader_phone));

 }

// Logic for photos uploaded thorugh iPhone app or android app
else{

	//Inserts picture and tiny picture into database
	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL, Caption, IsActive) VALUES ('$stream_id', '$uploader_phone', '$picture_id', '$picture_url','$picture_tinyurl','$caption',1)");


	// Sends out iPhone push notification
	uploadPicturePushNotification($uploader_phone, $stream_id, $picture_id);

	// // MixPanel tracking
	// $metrics->track('upload_photo', array('medium'=>'iPhone','uploader'=>$uploader_phone,'stream'=>$stream_id,'picture_url'=>$picture_url,'distinct_id'=>$uploader_phone));

	// Checks if the picture was actually uploaded to the database
	$picture_id_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id' AND Phone='$uploader_phone' AND StreamID='$stream_id'");
	

	$picture_id_row = mysql_fetch_array($picture_id_result);

	$output = array();

	if ($picture_id_row) 
	{
		$output['picture_id'] = $picture_id;
		$output['status'] = "ok";
	}
	else
	{
		$output ["status"] = "error";
		$output['error_description'] = "Photo not uploaded";
	}
	
	$output['api_name'] = "upload_picture";
	$output['uploader_phone'] = $uploader_phone;
	$output['picture_url'] = $picture_url;
	$output['picture_tinyurl'] = $picture_tinyurl;
	$output['stream_id'] = $stream_id;
	$output['caption'] = $caption;
	
	echo json_encode($output);
}
?>