<?PHP

// Adds a commenter's comments into the database for a particular picture

//input::
//	picture_id
//	commenter_phone
//	comment

//output::
//	picture_id
//  status
//  commenter_phone
//	api_name
//  Comments which is an array ordered chronologically that includes
//  	-commenter_first
//  	-commenter_last
//  	-commenter_phone
//  	-commenter_created
//  	-comment
// 		-comment_id

// Example: 
// http://75.101.134.112/stream/1.0/api/add_comment.php?commenter_phone=8477226071&comment=eyyyy&picture_id=1532d2aefcb206383390e28214a9a326933626b6bb33ad4864b810f20299e3b6a9e99c63de11c960756479456f422f6fad695e4ee618cc64c20af15c0ad2c1ff

include('dependencies.php');

if(empty($_POST))
{
	$picture_id = $_GET['picture_id'];
	$commenter_phone = $_GET['commenter_phone'];
	$commenter_phone = standardizePhone($commenter_phone);
	$comment = $_GET['comment'];
}

if(empty($_GET))
{
	$picture_id = $_POST['picture_id'];
	$commenter_phone = $_POST['commenter_phone'];
	$commenter_phone = standardizePhone($commenter_phone);
	$comment = mysql_real_escape_string($_POST['comment']);
}

//grabbing the arguments 
// $picture_id = $_GET['picture_id'];
// $commenter_phone = $_GET['commenter_phone'];
// $commenter_phone = standardizePhone($commenter_phone);
// $comment = $_GET['comment'];

$output = array();
$Comments = array();


//Error if no comment passed through
if ($comment == '')
{

	$output['status'] = "error";
	$output['error_description'] = "No comment passed through!";

}

//Otherwise insert comment into DB and return updated comment array
else
{
	$comment_id = time().$comment . $commenter_phone . $picture_id;

	$comment_id = hash('sha512', $comment_id);


	// Inserts commenter's comments into database
	$comment_result = mysql_query("INSERT INTO Comments (PictureID, Phone, Comment, CommentID) VALUES ('$picture_id', '$commenter_phone', '$comment','$comment_id')");

	if($comment_result){
		$output ["status"] = "ok";

		//send like push notification
		commentPushNotification($commenter_phone, $picture_id, $comment);
	}
	else
	{
		$output ["status"] = "error";
		$output['error_description'] = "Picture not Commented!";
	}
}

//Returns updated array of comments with commenter's name, created, and the comment
$stream_id_result = mysql_query("SELECT CommentID, First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");



while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$commentArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created'], 'comment_id'=>$stream_id_row['CommentID']);

	array_push($Comments, $commentArray);

}

$output['Comments'] = $Comments;
$output['picture_id'] = $picture_id;
$output['commenter_phone'] = $commenter_phone;
$output['comment'] = $comment;
$output['api_name'] = "add_comment";


echo json_encode($output);
?>