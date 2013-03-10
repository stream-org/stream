<?PHP

include('dependencies.php');

if(empty($_GET))
{
	$short_stream_id = $_POST['short_stream_id'];
}

if(empty($_POST))
{
	$short_stream_id = $_GET['short_stream_id'];
}

$streams_result = mysql_query("SELECT * FROM Streams");

while($streams_row = mysql_fetch_array($streams_result))
{

	$stream_id = $streams_row['StreamID'];

	if($short_stream_id === substr($stream_id, 0, 5))
	{
		$output['status'] = 'ok';
		$output['stream_id'] = $stream_id;
		break;
	}
}

if(empty($output))
{
	$output['status'] = 'error';
	$output['error_description'] = 'no matching stream_id';
}

echo json_encode($output);

?>