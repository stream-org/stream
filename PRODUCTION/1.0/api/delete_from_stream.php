<?PHP

// Deletes a user from a stream and deletes all photos that user has uploaded

//input:: 
//	viewer_phone
//	stream_id

//output::
//	status
// 	viewer phone
//	stream_id

include('dependencies.php');

//grabbing the arguments 
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$output = array();

$stream_id = $_GET['stream_id'];

//Removes user from Stream
mysql_query("DELETE FROM UserStreams WHERE Phone = '$viewer_phone' AND StreamID = '$stream_id'");

if(mysql_affected_rows()==0)
{
	$output ["status"] = "error";
	$output['error_description'] = "User not removed from Stream! ".$viewer_phone." ".$stream_id;
}
else
{
	//Removes users pictures from Stream by setting IsActive flag to 0
	mysql_query("UPDATE StreamActivity SET IsActive = 0 WHERE Phone = '$viewer_phone' AND StreamID = '$stream_id'");

	if(mysql_affected_rows()==0)
	{
		$output ["status"] = "error";
		$output['error_description'] = "No pictures deleted!";
	}
	$output ["status"] = "ok";
}




$output ["viewer_phone"] = $viewer_phone;
$output ["stream_id"] = $stream_id;

echo json_encode($output);
?>