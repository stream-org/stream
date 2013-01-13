<?PHP

// Gets the number of users who like a particular picture

//input::
//	picture_id

//output::
//  Likers which is an array of people who liked it that includes
// 		-liker_first
// 		-liker_last
// 		-liker_phone

include('dependencies.php');

//grabbing the arguments 
$picture_id = $_GET['picture_id'];

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

$output['Likers'] = $Likers;
$output['status'] = "ok";
$output['picture_id'] = $picture_id;

echo json_encode($output);

?>