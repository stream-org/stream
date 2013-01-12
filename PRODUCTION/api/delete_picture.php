<?PHP

// Deletes a viewer's picture from a stream

//input:: 
//	viewer_phone
//	stream_idh
//	picture_id

//output::
//	none
//	none

include('dependencies.php');

$output = array();

//grabbing the arguments 
$ = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);

$stream_id = $_GET['stream_id'];

$picture_id = $_GET['picture_id'];

//Removes viewer's picture from Stream
$status_picture = mysql_query("DELETE FROM StreamActivity WHERE Phone = '$viewer_phone' AND StreamID = '$stream_id' AND PictureID = '$picture_id'");

if($status_picture){
	$output ["status"] = "ok";
}
else{
	$output ["status"] = "error";
	$output['error_description'] = "Picture not deleted!"
}

$output['viewer_phone'] = $viewer_phone;
$output['stream_id'] = $stream_id;
$output['picture_id'] = $picture_id;

echo json_encode($output);

?>