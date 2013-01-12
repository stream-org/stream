<?PHP

//input::
//	picture_id

//output::
//	JSON object of all comments
// 	picture_id

include('dependencies.php');

$output = array();
$commentArray = array();

//grabbing the arguments 
$picture_id = $_GET['picture_id'];

$comment_result = mysql_query("SELECT * FROM Comments WHERE PictureID='$picture_id' ORDER BY Created ASC");

while($comment_row = mysql_fetch_array($comment_result))
{

	$comments_array[$streamidRow['Created']] = array('phone'=>$streamidRow['Phone'],'comment'=>$streamidRow['Comment']);
}

//Returns updated array of comments with commenter's name, created, and the comment
$stream_id_result = mysql_query("SELECT First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$commentArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created']);

	array_push($output, $commentArray);

}

$output['status'] = "ok";
$output['picture_id'] = $picture_id;
$output['Comments'] = $commentArray;

echo json_encode($output);

?>