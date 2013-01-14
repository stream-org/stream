<?PHP

// This will get all users in a particular stream

//input::
//	stream_id

//output::
//  status
//  stream_id
//	api_name
//	Users which is an array of users that are part of the stream ordered chronoligically by join date that includes
//		- uploader_first
// 		- uploader_last
//		- uploader_picturecount

// example:
// http://75.101.134.112/stream/1.0/api/get_users_in_stream.php?stream_id=6933fb99f4867cb94b1e0e32287bb12df8636de1386bebf73b54442011bd15a1775eef990f9595cfd052a2f2da8f185145f2931a0bf0fa4a947d0e9a59eb52a5

include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$output = array();
$Users = array();

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
	array_push($Users,$uploader_array);

}

$output['Users'] = $Users;
$output['status'] = "ok";
$output['stream_id'] = $stream_id;
$output['api_name'] = "get_users_in_stream";


echo json_encode($output);

?>