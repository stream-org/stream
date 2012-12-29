<?PHP

	include "connection.php";

	$phone = $_GET['phone'];
	$streamID = $_GET['streamID'];

	$theName;
	$theStreamName;
	$userToken;


	// get the user's name and token 
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$theName = $nameRow['First'] . ' ' . $nameRow['Last'];
		$userToken = $nameRow['Token'];
	}

	// //get the stream's name
	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");
	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$theStreamName = $streamNameRow['StreamName'];
	}

	$theMessage = $theName . ' uploaded a photo to ' . $theStreamName;
	$theMessage = urlencode($theMessage);

	
	//send the push notification 
  	// $url = 'http://75.101.134.112/api/pushNotification.php?token=' . $userToken . '&message=' . $theMessage;
  	$url = 'http://75.101.134.112/api/pushNotification.php?token=4af61150a072db0982335b65cb905b77d32bab06c008ce49d1854ed0331a786e&message=' . $theMessage; 
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);
?>