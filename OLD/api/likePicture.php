<?PHP

//input::
//	picture_id
//	liker_phone

//output::
//	number of people who like it 

include('dependencies.php');

$output = array();

//grabbing the arguments 
$picture_id = $_GET['picture_id'];
$liker_phone = $_GET['liker_phone'];
$liker_phone = standardizePhone($liker_phone);

//	Adds the picture like to the database
$like_result = mysql_query("INSERT INTO PictureLikes (PictureID, Phone) VALUES ('$picture_id', '$liker_phone')");

if($like_result){
	$output ["status"] = "ok";
}
else
{
	$output ["status"] = "error";
	$output['error_description'] = "Picture not not liked! ";
}

//send like push notification
likePush($liker_phone, $picture_id);

$like_result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture_id'");

$picture_likecount = mysql_fetch_row($like_result);
$picture_likecount = $picture_likecount[0];

$output['picture_id'] = $picture_id;
$output['liker_phone'] = $liker_phone;
$output['picture_likecount'] = $picture_likecount;

echo json_encode($output);

?>