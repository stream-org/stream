<?PHP

// Unlikes a picture that a liker as previously liked

//input::
//	picture_id
//	liker_phone

//output::
//	number of likes after the photo was unliked

include('dependencies.php');

$output = array();

//grabbing the arguments 
$picture_id = $_GET['picture_id'];
$liker_phone = $_GET['liker_phone'];
$liker_phone = standardizePhone($liker_phone);

$unlike_result = mysql_query("DELETE FROM PictureLikes WHERE PictureID='$picture_id' AND Phone='$liker_phone'");

// If the SQL delete query didn't work return error
if($unlike_result){
	$output ["status"] = "ok";
}
else{
	$output ["status"] = "error";
	$output['error_description'] = "Picture not not unliked! ";
}

$like_result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture_id'");

$count = mysql_fetch_row($like_result);

$count = $count[0];

$output['picture_likecount'] = $count;
$output['picture_id'] = $picture_id;
$output['liker_phone'] = $liker_phone;

echo json_encode($output);

?>