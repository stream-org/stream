<?PHP

// Deletes a viewer's picture from a stream

//input:: 
//	viewer_phone
//	stream_id
//	picture_id

//output::
//	viewer_phone
//	stream_id
//	picture_id

include('dependencies.php');

$output = array();

//grabbing the arguments 
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);

$picture_id = $_GET['picture_id'];

//Removes viewer's picture from Stream
mysql_query("DELETE FROM StreamActivity WHERE Phone = '$viewer_phone' AND PictureID = '$picture_id'");

if(mysql_affected_rows()==0)
{
	$output ["status"] = "error";
	$output['error_description'] = "Pictures not deleted!";
}
else
{
	$output ["status"] = "ok";
}

$output['viewer_phone'] = $viewer_phone;
$output['picture_id'] = $picture_id;

echo json_encode($output);

?>