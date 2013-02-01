<?PHP

// Gets the number of users who like a particular picture

//input::
//	picture_id

//output::
//  api_name
//  Likers which is an array of people who liked it that includes
// 		-liker_first
// 		-liker_last
// 		-liker_phone

// example:
// http://75.101.134.112/stream/1.0/api/get_users_who_like.php?picture_id=1984c05a7f989fdf53c6ea96b86f6bfd8784ff22b3272793a6339716f936ff285977d699b69111a6655abde9ed19c6d2d53a6f588c85a7c26e8473f8680053e8

include('dependencies.php');

//grabbing the arguments 

if(empty($_POST))
{
	$picture_id = $_GET['picture_id'];
}

if(empty($_GET))
{
	$picture_id = $_POST['picture_id'];
}

$output = array();
$Likers = array();

// Gets people who like a pictures
$liker_result = mysql_query("SELECT First, Last, PictureLikes.Phone as Phone FROM PictureLikes INNER JOIN Users ON PictureLikes.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

while ($liker_row = mysql_fetch_array($liker_result))
{
	$liker_phone = $liker_row['Phone'];
	$liker_first = $liker_row['First'];
	$liker_last = $liker_row['Last'];

	$liker_array = array('liker_phone'=>$liker_phone,'liker_first'=>$liker_first, 'liker_last'=>$liker_last);

	array_push($Likers, $liker_array);
}

$output['likers'] = $Likers;
$output['status'] = "ok";
$output['picture_id'] = $picture_id;
$output['api_name'] = "get_users_who_like";

echo json_encode($output);

?>