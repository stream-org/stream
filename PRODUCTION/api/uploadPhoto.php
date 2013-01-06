<?PHP

// Uploads the location of a picture_url to the database if sent through either the iPhone app or Mobile Web Layer
// Saves the photo if photo uploaded through text

//input::
//	uploader_phone number
//	picture_url 
//	stream_id
//  tinyPicture url
//	caption

//output::
//	picture_id or null

include('dependencies.php');

//Mixpanel Tracking
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$uploader_phone = $_GET['uploader_phone'];
$uploader_phone = standardizePhone($uploader_phone);
$picture_url = $_GET['picture_url'];
$tiny_picture_url = $_GET['tiny_picture_url'];
$stream_id = $_GET['stream_id'];
$caption = $_GET['caption'];
$picture_id = $picture_url . $uploader_phone . $stream_id . time();
$picture_id = hash('sha512', $picture_id);

//For testing photo upload notification
if($picture_url == "test"){

  	photopush($uploader_phone, $stream_id);

}

// Logic for photos uploaded through text

elseif ($tiny_picture_url == ""){

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

	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL) VALUES ('$stream_id', '$uploader_phone', '$picture_id','$pictureFilePath', '$tinyPictureFilePath')");

	photoPush($uploader_phone, $stream_id);

	$metrics->track('upload_photo', array('medium'=>'text','uploader'=>$uploader_phone,'stream'=>$stream_id,'picture_url'=>$picture_url,'distinct_id'=>$uploader_phone));

 }

// Logic for photos uploaded thorugh iPhone app r android app
else{
	mysql_query("INSERT INTO StreamActivity (StreamID, Phone, PictureID, PicURL, TinyPicURL, Caption) VALUES ('$stream_id', '$uploader_phone', '$picture_id', '$picture_url','$tiny_picture_url','$caption')");

	photoPush($uploader_phone, $stream_id);

	$metrics->track('upload_photo', array('medium'=>'iPhone','uploader'=>$uploader_phone,'stream'=>$stream_id,'picture_url'=>$picture_url,'distinct_id'=>$uploader_phone));

	$picture_id_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id' AND Phone='$uploader_phone' AND StreamID='$stream_id'");
	$output = array();

	$picture_id_row = mysql_fetch_array($picture_id_result);

	if ($picture_id_row) 
	{
		$output['picture_id'] = $picture_id;
	}

	echo json_encode($output);
}
?>