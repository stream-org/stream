<?PHP

// Sends the push notification to a particular iPhone according to the token

//input::
//	token
//	message

//output::
//	a string denoting that the message was unsuccessful, or nothing is the message was successful

// example::
//http://75.101.134.112/stream/1.0/api/push_notification.php?token=6e27be3b0190dd6ec5893febc5e92a915e5b7f8aa7d2c5c25f0ae8fa867209a1&message=push_notification_test



$theToken = $_GET['token'];
$theMessage = $_GET['message'];

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
	'sound' => 'default'
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	// echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);