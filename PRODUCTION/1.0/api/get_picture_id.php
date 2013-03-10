<?PHP

include('dependencies.php');

if(empty($_GET))
{
	$short_picture_id = $_POST['short_picture_id'];
}

if(empty($_POST))
{
	$short_picture_id = $_GET['short_picture_id'];
}

$pictures_result = mysql_query("SELECT * FROM StreamActivity");

while($pictures_row = mysql_fetch_array($pictures_result))
{

	$picture_id = $pictures_row['PictureID'];

	if($short_picture_id === substr($picture_id, 0, 5))
	{
		$output['status'] = 'ok';
		$output['picture_id'] = $picture_id;
		break;
	}
}

if(empty($output))
{
	$output['status'] = 'error';
	$output['error_description'] = 'no matching picture_id';
}

echo json_encode($output);

?>