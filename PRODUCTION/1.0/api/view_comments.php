<?PHP

//input::
//	picture_id

//output::
// 	picture_id
// 	status
//	api_name
//	Comments, which is array of comments ordered chronologically that includes for each comment:
// 		commenter_first
// 		commenter_last
// 		commenter_phone
// 		comment
// 		comment_created

// example:
// http://75.101.134.112/stream/1.0/api/view_comments.php?commenter_phone=8477226071&comment=eyyyy&picture_id=1532d2aefcb206383390e28214a9a326933626b6bb33ad4864b810f20299e3b6a9e99c63de11c960756479456f422f6fad695e4ee618cc64c20af15c0ad2c1ff

include('dependencies.php');

$output = array();
$commentArray = array();

//grabbing the arguments 
$picture_id = $_GET['picture_id'];


//Returns updated array of comments with commenter's name, created, and the comment
$stream_id_result = mysql_query("SELECT First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

while($stream_id_row = mysql_fetch_array($stream_id_result))
{
	$tempArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created']);

	array_push($commentArray, $tempArray);

}

$output['status'] = "ok";
$output['picture_id'] = $picture_id;
$output['Comments'] = $commentArray;
$output['api_name'] = "view_comments";

echo json_encode($output);

?>