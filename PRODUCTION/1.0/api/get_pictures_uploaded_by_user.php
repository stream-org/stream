<?PHP

// Get the pictures uploaded by a user

//input::
//	stream_id
//	uploader_phone
//  viewer_phone

//output::
//  status
//	stream_id
//	api_name
//	uploader_phone
//  viewer_phone
//  Pictures which is an array of all pictures a particular uploader has uploaded ordered reverse chronologically tht includes
// 		-picture_tinyurl
// 		-picture_id

// example:
// http://75.101.134.112/stream/1.0/api/get_pictures_uploaded_by_user.php?viewer_phone=8477226071&uploader_phone=6508420492&stream_id=6933fb99f4867cb94b1e0e32287bb12df8636de1386bebf73b54442011bd15a1775eef990f9595cfd052a2f2da8f185145f2931a0bf0fa4a947d0e9a59eb52a5

include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$uploader_phone = $_GET['uploader_phone'];
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$uploader_phone = standardizePhone($uploader_phone);

$output = array();

$picture_array = array();

$picture_result = mysql_query("SELECT * FROM StreamActivity WHERE Phone='$uploader_phone' AND StreamID='$stream_id' AND IsActive = 1 ORDER BY Created DESC");

while($picture_row = mysql_fetch_array($picture_result))
{
	$temp_array = array();
	$temp_array['picture_tinyurl'] = $picture_row['TinyPicURL'];
	$temp_array['picture_id'] = $picture_row['PictureID'];
	array_push($picture_array, $temp_array);
}

$output['stream_id'] = $stream_id;
$output['uploader_phone'] = $uploader_phone;
$output['viewer_phone'] = $viewer_phone;
$output['Pictures'] = $picture_array;
$output['api_name'] = "get_pictures_uploaded_by_user";	
$output ["status"] = "ok";


echo json_encode($output);

?>