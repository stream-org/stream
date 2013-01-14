<?PHP

//input::
//	picture_id
//	liker_phone

//output::
//	picture_id
//	liker_phone
//	picture_likecount 
//	status: ok || status: error & error_description: Picture not liked!

//example::
//	http://75.101.134.112/stream/1.0/api/like_picture.php?liker_phone=18477226071&picture_id=1532d2aefcb206383390e28214a9a326933626b6bb33ad4864b810f20299e3b6a9e99c63de11c960756479456f422f6fad695e4ee618cc64c20af15c0ad2c1ff

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

	//send like push notification
	likePushNotification($liker_phone, $picture_id);
}
else
{
	$output ["status"] = "error";
	$output['error_description'] = "Picture not liked! ";
}

$like_result = mysql_query("SELECT COUNT(DISTINCT Phone) FROM PictureLikes WHERE PictureID='$picture_id'");

$picture_likecount = mysql_fetch_row($like_result);
$picture_likecount = $picture_likecount[0];

$output['picture_id'] = $picture_id;
$output['liker_phone'] = $liker_phone;
$output['picture_likecount'] = $picture_likecount;

echo json_encode($output);

?>