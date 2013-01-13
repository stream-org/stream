<?PHP

// Gets all of the meta data for teh streams a user is part of

//input: viewer_phone 

//output:: 
//	viewer_phone 
// 	array of streams:
//		stream_id
//		stream_name
//		# of participants 
//		# of pictures
//		most recent photo 


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

$stream_id_result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$viewer_phone' AND IsActive = 1 ORDER BY StreamJoinDate DESC");
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

	$picture_result = mysql_query("SELECT COUNT(PictureID) FROM StreamActivity WHERE StreamID='$stream_id'");
	while($picture_row = mysql_fetch_array($picture_result))
	{
		$picture_count = (int) $picture_row[0];
	}
	$latest_picture_array = null;
	$latest_picture_result = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$stream_id' ORDER BY Created DESC LIMIT 1");
	while($latest_picture_row = mysql_fetch_array($latest_picture_result))
	{
		$latest_picture_array = array();
		$latest_picture_array['url'] = $latest_picture_row['TinyPicURL'];
		$latest_picture_array['id'] = $latest_picture_row['PictureID'];
	}

	array_push($stream_array, array('stream_id'=>$stream_id,'stream_name'=>$stream_name, 'stream_usercount'=>$stream_usercount, 'picture_count'=>$picture_count, 'picture_latest'=>$latest_picture_array));

}

$output ["Streams"] = $stream_array;
$output ["viewer_phone"] = $viewer_phone;
$output ["status"] = "ok";

echo json_encode($output);
	
?>