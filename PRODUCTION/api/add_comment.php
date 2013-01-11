<?PHP

// Adds a commenter's comments into the database for a particular picture

//input::
//	picture_id
//	commenter_phone
//	comment

//output::
//	JSON object of picture_id, status, and commenter_phone

include "dependencies.php";

//grabbing the arguments 
$picture_id = $_GET['picture_id'];
$commenter_phone = $_GET['commenter_phone'];
$commenter_phone = standardizePhone($commenter_phone);
$comment = $_GET['comment'];

$output = array();


//Error if no comment passed through
if ($comment == '')
{

	$output['status'] = "error";
	$output['error_description'] = "No comment passed through!";

	echo json_encode($output);
}

//Otherwise insert comment into DB and return updated comment array
else
{
	// Inserts commenter's comments into database
	mysql_query("INSERT INTO Comments (PictureID, Phone, Comment) VALUES ('$picture_id', '$commenter_phone', '$comment')");

	//Returns updated array of comments with commenter's name, created, and the comment
	$stream_id_result = mysql_query("SELECT First, Last, Comments.Phone as Phone, Comment, Comments.Created as Created FROM Comments INNER JOIN Users ON Comments.Phone = Users.Phone WHERE PictureID='$picture_id' ORDER BY Created ASC");

	while($stream_id_row = mysql_fetch_array($stream_id_result))
	{
		$commentArray = array('commenter_first'=>$stream_id_row['First'], 'commenter_last'=>$stream_id_row['Last'], 'commenter_phone'=>$stream_id_row['Phone'],'comment'=>$stream_id_row['Comment'], 'comment_created'=>$stream_id_row['Created']);

		array_push($output, $commentArray);

	}

	$output['status'] = "ok";

	echo json_encode($output);
}
?>