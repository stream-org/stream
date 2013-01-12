<?PHP

// This will get all users in a particular stream

//input::
//	stream_id

//output::
//	array of
//		name
// 		phone
//		how many photos they have uploaded 

include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$output = array();

//Gets name and number of photos uploaded for every user in the particular stream
$picture_id_result = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$stream_id' ORDER BY StreamJoinDate ASC");
while($picture_id_row = mysql_fetch_array($picture_id_result))
{

	$uploader_phone = $picture_id_row['Phone'];
	
	// Gets the name for each uploader
	$name_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploader_phone'");
	while($name_row = mysql_fetch_array($name_result))
	{
		$uploader_first = $name_row['First'];
		$uploader_last = $name_row['Last'];
	}

	// Gets the number of photos uploaded for each uploader
	$picture_result = mysql_query("SELECT COUNT(PictureID) FROM StreamActivity WHERE StreamID='$stream_id' AND Phone='$uploader_phone'");
	while($picture_row = mysql_fetch_array($picture_result))
	{
		$uploader_picturecount = $picture_row[0];
	}

	$uploader_array = array('uploader_phone'=>$uploader_phone,'uploader_first'=>$uploader_first, 'uploader_last'=>$uploader_last, 'uploader_picturecount'=>$uploader_picturecount);
	array_push($output,$uploader_array);

}

$output['status'] = "ok";
$output['$stream_id'] = $stream_id;


echo json_encode($output);

?>