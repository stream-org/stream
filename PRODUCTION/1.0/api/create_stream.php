<?PHP

// Creates a stream by adding it to the Streams table and inviting users call calling the invite_users.php

//input:: 
//	inviter_phone
//	stream_name
//  invitees_phone

//output::
//	status
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

include('dependencies.php');

// //grabbing the arguments 
$inviter_phone = $_GET['inviter_phone'];
$inviter_phone = standardizePhone($inviter_phone);
$stream_name = $_GET['stream_name'];
$invitees_phone = $_GET['invitees_phone'];

$stream_id = $stream_name . time();
$stream_id = hash('sha512', $stream_id);

//create the stream, and insert the creator into the stream 
mysql_query("INSERT INTO Streams (StreamName, StreamID, Phone) VALUES ('$stream_name', '$stream_id', '$inviter_phone')");

// Inserts user and stream into UserStreams table
mysql_query("INSERT INTO UserStreams (Phone, StreamID) VALUES ('$inviter_phone', '$stream_id')");

//Invite invitees to stream
$url = 'http://75.101.134.112/stream/1.0/api/invite_users.php?invitees_phone=' . $invitees_phone . '&stream_id=' . $stream_id . '&inviter_phone=' . $inviter_phone;
$ch = curl_init($url);
$response = curl_exec($ch);
curl_close($ch);

?>