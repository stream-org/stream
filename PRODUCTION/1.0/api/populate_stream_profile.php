<?PHP

// Retrieves all pictures and the number of likes and comments for each picture as well as number of participants for the stream
// in a particular stream

//input:: 
//	stream_id
//  viewer_phone

//output::
//  status
//  stream_id
//  stream_name
//  stream_usercount
//  viewer_phone
//	array of photos ranked reverse chronologically which includes
// 		-picture_id
// 		-picture_likecount
// 		-picture_tinyurl
// 		-picture_commentcount

// example:
// 


include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$viewer_phone = $_GET['viewer_phone'];

$output = array();
$picture_array = array();

// Gets the number of partcipants in the stream
$usercount_result = mysql_query("SELECT COUNT(Distinct Phone) FROM UserStreams WHERE StreamID='$stream_id'");

$stream_usercount = $usercount_result[0];

// Gets the tinyPictureURL, pictureID, and the number of likes for each picture in a stream
$picture_result = mysql_query("SELECT * FROM StreamActivity WHERE StreamID='$stream_id' ORDER BY Created DESC");
while($picture_row = mysql_fetch_array($picture_result))
{
	$picture = array();

	$picture['picture_tinyurl'] = $picture_row['TinyPicURL'];

	$picture_id = $picture_row['PictureID'];
	$picture['picture_id'] = $picture_id;

	// gets the number of likes for a picture
	$picture_likecount_result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture_id'");

	$picture_likecount = mysql_fetch_row($picture_likecount_result);

	$picture_likecount = $picture_likecount[0];
	$picture['picture_likecount'] = $picture_likecount;

	// gets the number of comments for a picture
	$picture_commentcount_result = mysql_query("SELECT COUNT(*) FROM Comments WHERE PictureID='$picture_id'");

	$picture_commentcount = mysql_fetch_row($picture_commentcount_result);

	$picture_commentcount = $picture_commentcount[0];
	$picture['picture_commentcount'] = $picture_commentcount;


	array_push($picture_array, $picture);
}

// Gets the stream's name
$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
while($stream_name_row=mysql_fetch_array($stream_name_result))
{
	$stream_name = $stream_name_row['StreamName'];

}

$output['stream_id'] = $stream_id;
$output['stream_name'] = $stream_name;
$output['stream_usercount'] = $stream_usercount;
$output['picture_array'] = $picture_array;
$output['viewer_phone'] = $viewer_phone;
$output["status"] = "ok";


echo json_encode($output);

?>