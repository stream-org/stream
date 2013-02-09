<?PHP

// Sends the push notification to a particular iPhone according to the token

//input::
//	token
//	message

//output::
//	a string denoting that the message was unsuccessful, or nothing is the message was successful
//  api_name

// example::
// http://75.101.134.112/stream/1.0/api/push_notification.php?token=6e27be3b0190dd6ec5893febc5e92a915e5b7f8aa7d2c5c25f0ae8fa867209a1&message=push_notification_test&stream_id=qwertyui&picture_id=asdfghjk

if(empty($_GET))
{
$theToken = $_POST['token'];
$theMessage = $_POST['message'];
$small_picture_id = $_POST['picture_id'];
$small_stream_id = $_POST['stream_id'];
$badge_count = $_POST['badge_count'];
$is_comment = $_POST['is_comment'];
}
if(empty($_POST))
{
$theToken = $_GET['token'];
$theMessage = $_GET['message'];
$small_picture_id = $_GET['picture_id'];
$small_stream_id = $_GET['stream_id'];
$badge_count = $_GET['badge_count'];
$is_comment = $_GET['is_comment'];
}

if ($is_comment == 1)
{
	$is_comment == TRUE;
}
else
{
	$is_comment = FALSE;
}

$output = array();

// Put your device token here (without spaces):
$deviceToken = $theToken;

// Put your private key's passphrase here:
$passphrase = 'stream';

// Put your alert message here:
$message = $theMessage;

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

// echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default',
	'badge' => intval($badge_count)
);

$body['actions'] = array(
	'small_stream_id' => $small_stream_id,
	'small_picture_id' => $small_picture_id,
	'is_comment' => $is_comment
);

// Encode the payload as JSON
$payload = json_encode($body);

echo $payload;

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
{	
	$output['status'] = "error";
	$output['error_description'] = 'Message not delivered' . PHP_EOL;
}

else
{
	$output['status'] = "ok";
}

$output['api_name'] = "push_notification";
echo json_encode($output);

fclose($fp);