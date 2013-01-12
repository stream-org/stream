<?PHP

// Get the pictures uploaded by a user

//input::
//	stream_id
//	uploader phone
//  viewer phone

//output::
//	array of photos user has uploaded

include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$uploader_phone = $_GET['uploader_phone'];
$viewer_phone = $_GET['viewer_phone'];
$uploader_phone = standardizePhone($uploader_phone);

$output = array();

$picture_array = array();

$picture_result = mysql_query("SELECT * FROM StreamActivity WHERE Phone='$uploader_phone' AND stream_id='$stream_id' ORDER BY Created");

while($picture_row = mysql_fetch_array($picture_result))
{
	$temp_array = array();
	$temp_array['url'] = $picture_row['TinyPicURL'];
	$temp_array['id'] = $picture_row['PictureID'];
	array_push($picture_array, $temp_array);
}

$output['stream_id'] = $stream_id;
$output['uploader_phone'] = $uploader_phone;
$output['viewer_phone'] = $viewer_phone;
$output['Pictures'] = $pictureArray;
$output ["status"] = "ok";


echo json_encode($responseArray);

?>