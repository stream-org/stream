<?PHP

// Gets all of the metadata for the streams a user is part of

//input: 
// 	viewer_phone 

//output:: 
//	viewer_phone 
//  status
//  api_name
// 	Streams which is an array ordered reverse chronologically by StreamJoinDate that includes:
//		-stream_id
//		-stream_name
//		-stream_usercount 
//		-picture_count
//		-picture_latest which is an array which contains metadata on the latest picture that includes
//			-picture_tinyurl
//			-picture_id

// example:
// http://75.101.134.112/stream/1.0/api/populate_user_streams.php?viewer_phone=8477226071

include('dependencies.php');
//grabbing the arguments 


$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
	
$stream_id;
$stream_name;
$stream_usercount;
$picture_count;
$picture_latest;

$output = array();

$stream_array = array();

$stream_id_result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$viewer_phone' ORDER BY StreamJoinDate DESC");
while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$stream_id = $stream_id_row['StreamID'];

	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	$usercount_result = mysql_query("SELECT COUNT(Distinct Phone) FROM UserStreams WHERE StreamID='$stream_id'");
	while($usercount_row = mysql_fetch_array($usercount_result))
	{
		$stream_usercount = (int) $usercount_row[0];
	}

	$picture_result = mysql_query("SELECT COUNT(PictureID) FROM StreamActivity WHERE StreamID='$stream_id' AND IsActive = 1 ");
	while($picture_row = mysql_fetch_array($picture_result))
	{
		$picture_count = (int) $picture_row[0];
	}
	$latest_picture_array = null;
	$latest_picture_result = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$stream_id' AND IsActive = 1 ORDER BY Created DESC LIMIT 1");
	while($latest_picture_row = mysql_fetch_array($latest_picture_result))
	{
		$latest_picture_array = array();
		$latest_picture_array['picture_tinyurl'] = $latest_picture_row['TinyPicURL'];
		$latest_picture_array['picture_id'] = $latest_picture_row['PictureID'];
	}

	array_push($stream_array, array('stream_id'=>$stream_id,'stream_name'=>$stream_name, 'stream_usercount'=>$stream_usercount, 'picture_count'=>$picture_count, 'picture_latest'=>$latest_picture_array));

}

$output["streams"] = $stream_array;
$output["viewer_phone"] = $viewer_phone;
$output["api_name"] = "populate_user_streams";
$output["status"] = "ok";

echo json_encode($output);
	
?>
