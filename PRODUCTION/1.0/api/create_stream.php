<?PHP

// Creates a stream by adding it to the Streams table and inviting users call calling the invite_users.php

//input:: 
//	inviter_phone
//	stream_name
//  invitees_phone

//output::
//  api_name
//	status
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

//example::
//	http://75.101.134.112/stream/1.0/api/create_stream.php?inviter_phone=18477226071&invitees_phone=16508420492&stream_name=testThisOut

include('dependencies.php');

if(empty($_POST))
{
	$inviter_phone = $_GET['inviter_phone'];
	$inviter_phone = standardizePhone($inviter_phone);
	$stream_name = $_GET['stream_name'];
	$invitees_phone = $_GET['invitees_phone'];
}

if(empty($_GET))
{
	$inviter_phone = $_POST['inviter_phone'];
	$inviter_phone = standardizePhone($inviter_phone);
	$stream_name = $_POST['stream_name'];
	$invitees_phone = $_POST['invitees_phone'];
}

$output = array();

$stream_id = $stream_name . time();
$stream_id = hash('sha512', $stream_id);

//create the stream, and insert the creator into the stream 
$create_result = mysql_query("INSERT INTO Streams (StreamName, StreamID, Phone) VALUES ('$stream_name', '$stream_id', '$inviter_phone')");

// Inserts user and stream into UserStreams table
mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$inviter_phone', '$stream_id')");

if ($create_result)
{
	$output['status'] = "ok";
}
else 
{
	$output['status'] = "error";
	$output['error_descriptipn'] = "Stream was not created.";
}

//Invite invitees to stream
$url = 'http://75.101.134.112/stream/1.0/api/invite_users.php?invitees_phone=' . $invitees_phone . '&stream_id=' . $stream_id . '&inviter_phone=' . $inviter_phone;
$ch = curl_init($url);
$response = curl_exec($ch);
curl_close($ch);

$output['stream_id'] = $stream_id;
$output['invitees_phone'] = $invitees_phone;
$output['inviter_phone'] = $inviter_phone;
$output['api_name'] = "create_stream";

echo json_encode($output);

?>