<?PHP

// Get the number of people who like, the uploader name, and comments for a picture

//input::
//	picture_id
//	viewer_viewer_phone

//output:: 
//	picture URL ['picture_url']
//  picture ID ['picture_id']
//	number of people who like it ['picture_likecount']
//  whether the viewer has liked the photo ['viewer_hasliked']
//	name of person who uploaded the photo ['uploader_first'] ['uploader_last'] 
//  phone of person who uploaded the photo ['uploader_phone']
// 	photo's comments {['comment']['created']['commenter']}
//	photo's caption ['caption']

include('dependencies.php');

//grabbing the arguments 
$picture_id = $_GET['picture_id'];
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$picture_likecount;
$uploader_phone;
$uploader_first;
$uploader_last;
$viewer_hasLiked = "False";

$output = array();

$output['viewer_hasliked'] = $viewer_hasLiked;

// Gets like information on the picture and the user viewing the picture

$like_result = mysql_query("SELECT COUNT(Distinct Phone) FROM PictureLikes WHERE PictureID='$picture_id'");
while ($like_row = mysql_fetch_array($like_result))
{
	$picture_likecount = $like_row[0];
	$output['picture_likecount'] = $picture_likecount;
}

$hasLiked_result = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$picture_id' AND Phone='$viewer_phone'");
while ($hasLiked_row = mysql_fetch_array($hasLiked_result))
{
	if($hasLiked_row) 
	{
		$viewer_hasLiked = 'True';
		$output['viewer_hasLiked'] = $viewer_hasLiked;
	}
}

// Gets Uploader's phone and caption information of the picture
$uploader_phone_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id'");
while($uploader_phone_row = mysql_fetch_array($uploader_phone_result))
{
	$uploader_phone = $uploader_phone_row['Phone'];
	$output['uploader_phone'] = $uploader_phone;
	$output['caption'] = $uploader_phone_row['Caption'];
}

//Gets the uploader's first and last name
$uploader_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploaderPhone'");
while($uploader_name_row = mysql_fetch_array($uploader_name_result))
{
	$uploader_first = $uploader_name_row['First'];
	$uploader_last = $uploader_name_row['Last'];
	$picture_url = $uploader_name_row['PicURL'];
	$output['picture_url'] = $picture_url;
	$output['picture_id'] = $picture_id;
	$output['uploader_first'] = $uploader_first;
	$output['uploader_last'] = $uploader_last;
}

// Gets comment, commenter's first, last, and phone, and created
$stream_id_result = mysql_query("SELECT First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

$tempOutput= array();

while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$tempArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created']);
	array_push($tempOutput, $tempArray);
}

$output['Comments'] = $tempOutput;

$output['status'] = "ok";

echo json_encode($output);


?>