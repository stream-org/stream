<?PHP

// Deletes a user from a stream and deletes all photos that user has uploaded

//input:: 
//	viewer_phone
//	stream_id

//output::
//	status
// 	viewer phone

include('dependencies.php');

//grabbing the arguments 
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$output = array();

$stream_id = $_GET['stream_id'];

//Removes users pictures from Stream
$status_picture = mysql_query("DELETE FROM  StreamActivity WHERE Phone = '$viewer_phone' AND StreamID = '$stream_id'");

if ($status_picture)
{

	//Removes user from Stream
	$status_stream = mysql_query("DELETE FROM  UserStreams WHERE Phone = '$viewer_phone' AND StreamID = '$stream_id'");

	if($status_stream)
	{
		$output ["status"] = "ok";
	}
	else
	{
		$output ["status"] = "error";
		$output['error_description'] = "User not removed from Stream! ".$viewer_phone." ".$stream_id;
	}
}
else{
	$output ["status"] = "error";
	$output['error_description'] = "Pictures not deleted!"
}

$output ["viewer_phone"] = $viewer_phone;
$output ["stream_id"] = $stream_id;

echo json_encode($output);
?>