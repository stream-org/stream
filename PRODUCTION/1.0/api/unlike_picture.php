<?PHP

// Unlikes a picture that a liker as previously liked

//input::
//	picture_id
//	liker_phone

//output::
//	status
//	api_name
//	picture_likecount
//  picture_id
//  liker_phone 

include('dependencies.php');

$output = array();

//grabbing the arguments 
$picture_id = $_GET['picture_id'];
$liker_phone = $_GET['liker_phone'];
$liker_phone = standardizePhone($liker_phone);

mysql_query("DELETE FROM PictureLikes WHERE PictureID='$picture_id' AND Phone='$liker_phone'");

// If the SQL delete query didn't work return error
if(mysql_affected_rows()==0){
	$output ["status"] = "error";
	$output['error_description'] = "Picture not unliked! ";
}
else{
	$output ["status"] = "ok";
}

$like_result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture_id'");

$count = mysql_fetch_row($like_result);

$count = $count[0];

$output['api_name'] = "unlike_picture";
$output['picture_likecount'] = $count;
$output['picture_id'] = $picture_id;
$output['liker_phone'] = $liker_phone;

echo json_encode($output);

?>