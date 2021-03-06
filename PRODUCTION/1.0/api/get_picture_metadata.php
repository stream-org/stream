<?PHP

// Get the number of people who like, the uploader name, and comments for a picture

//input::
//	picture_id
//	viewer_phone

//output:: 
//	picture_url
//  picture_id
//	picture_likecount
//  viewer_hasliked
//	uploader_first
//  uploader_last
//  uploader_phone
//	api_name
//  can_delete
//  Comments which is an array ordered chronologically that includes
//  	-commenter_first
//  	-commenter_last
//  	-commenter_phone
//  	-commenter_created
//  	-comment
// 		-comment_id

// example:
// http://75.101.134.112/stream/1.0/api/get_picture_metadata.php?viewer_phone=8477226071&picture_id=1984c05a7f989fdf53c6ea96b86f6bfd8784ff22b3272793a6339716f936ff285977d699b69111a6655abde9ed19c6d2d53a6f588c85a7c26e8473f8680053e8

include('dependencies.php');

//grabbing the arguments 


if(empty($_POST))
{
	$picture_id = $_GET['picture_id'];
	$viewer_phone = $_GET['viewer_phone'];
	$viewer_phone = standardizePhone($viewer_phone);
}

if(empty($_GET))
{
	$picture_id = $_POST['picture_id'];
	$viewer_phone = $_POST['viewer_phone'];
	$viewer_phone = standardizePhone($viewer_phone);
}

$picture_likecount;
$uploader_phone;
$uploader_first;
$uploader_last;
$viewer_hasLiked = "False";

$output = array();

// Gets like information on the picture and the user viewing the picture

$like_result = mysql_query("SELECT COUNT(Distinct Phone) FROM PictureLikes WHERE PictureID='$picture_id'");
while ($like_row = mysql_fetch_array($like_result))
{
	$picture_likecount = $like_row[0];
	$output['picture_likecount'] = $picture_likecount;
}

// Checks if the viewer has liked theo photo
$hasLiked_result = mysql_query("SELECT * FROM PictureLikes WHERE PictureID='$picture_id' AND Phone='$viewer_phone'");
$hasLiked_row = mysql_fetch_array($hasLiked_result);

if($hasLiked_row) 
{
	$viewer_hasLiked = 'True';
}

// Gets Uploader's phone and caption information of the picture
$uploader_phone_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id'");
$uploader_phone_row = mysql_fetch_array($uploader_phone_result);

$uploader_phone = $uploader_phone_row['Phone'];
$output['uploader_phone'] = $uploader_phone;
$output['caption'] = $uploader_phone_row['Caption'];
$output['picture_url'] = $uploader_phone_row['PicURL'];

//Gets the uploader's first and last name
$uploader_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploader_phone'");
$uploader_name_row = mysql_fetch_array($uploader_name_result);

$output['uploader_first'] = $uploader_name_row['First'];
$output['uploader_last'] = $uploader_name_row['Last'];

// Gets metadata for all comments: commenter's first, last, and phone, and created
$stream_id_result = mysql_query("SELECT CommentID,First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

$commentArray= array();

while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$tempArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created'],'comment_id'=>$stream_id_row['CommentID']);
	array_push($commentArray, $tempArray);
}

// Allows for deletion option if uploader is the same as the viewer
$can_delete = 0;

if($uploader_phone==$viewer_phone){
	$can_delete = 1;
}

//Reset user's badge to 0
mysql_query("UPDATE Users SET BadgeCount = 0 WHERE Phone = '$viewer_phone'");


$output['comments'] = $commentArray;
$output['picture_id'] = $picture_id;
$output['viewer_phone'] = $viewer_phone;
$output['viewer_hasLiked'] = $viewer_hasLiked;
$output['api_name'] = "get_picture_metadata";
$output['can_delete'] = $can_delete;
$output['status'] = "ok";

echo json_encode($output);


?>