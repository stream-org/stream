<?PHP

include('connection.php');
include('sendText.php');

function likePush($phone, $pictureID)
{
	$likerName;
	$theStreamName;
	$userToken;
	$uploaderPhone;
	$streamID;

	//get the name of the person who liked the photo
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$likerName = $nameRow['First'];
	}

	//find the number of the person who uploaded the photo
	$uploaderPhoneResult = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$pictureID'");
	while ($uploaderPhoneRow = mysql_fetch_array($uploaderPhoneResult))
	{
		$uploaderPhone = $uploaderPhoneRow['Phone'];
		$streamID = $uploaderPhoneRow['StreamID'];
	}

	//find the name of the stream that the photo was uploaded to 
	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$theStreamName = $streamNameRow['StreamName'];
	}

	//I have the uploader's number, the liker's name, and the streamName
	$sendResult = mysql_query("SELECT * FROM Users WHERE Phone='$uploaderPhone'");
	while($sendRow = mysql_fetch_array($sendResult))
	{
		if ($sendRow['Token'] != '')
		{
			$tempToken = $sendRow['Token'];
			$theMessage = $likerName . ' likes the photo you posted on ' . $theStreamName . '.';
			$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $tempToken . '&message=' . urlencode($theMessage); 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		}
		else
		{
			$theMessage = $likerName . ' likes the photo you posted on ' . $theStreamName . '. bit.ly/12Dy6u5';
			sendText($uploaderPhone, $theMessage);	
		}
	}
}

function invitePush($phone, $streamID)
{
	$theName;
	$theStreamName;
	$userToken;
	$peopleInStreamArray = array();


	// get the user's name and token 
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$theName = $nameRow['First'];
	}

	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$theStreamName = $streamNameRow['StreamName'];
		echo $theStreamName;
		echo '<br>';
	}

	//I have the message, now I just need to figure out who to send it to. 

	$peopleInStreamResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID' AND Phone!='$phone'");
	while ($peopleInStreamRow = mysql_fetch_array($peopleInStreamResult))
	{
		array_push($peopleInStreamArray, $peopleInStreamRow['Phone']);
		// echo $peopleInStreamRow['Phone'];
	}

	for ($i = 0; $i < count($peopleInStreamArray); $i++)
	{
		$tempPhone = $peopleInStreamArray[$i];
		$sendPushResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone' and Token!=''");
		while($sendPushRow = mysql_fetch_array($sendPushResult))
		{	
			$tempToken = $sendPushRow['Token'];
			$theMessage = $theName . ' invited you to the ' . $theStreamName . ' stream.';
			$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $tempToken . '&message=' . urlencode($theMessage); 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		}
	}

	for ($j = 0; $j < count($peopleInStreamArray); $j++)
	{
		$tempPhone2 = $peopleInStreamArray[$j];
		$sendPushResult2 = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone2' AND Token=''");
		while($sendPushRow2 = mysql_fetch_array($sendPushResult2))
		{
			$theMessage2 = $theName . ' invited you to the ' . $theStreamName . ' stream. bit.ly/12Dy6u5';
			sendText($tempPhone2, $theMessage2);
		}
	}


}


function photoPush($phone, $streamID)
{
	$theName;
	$theStreamName;
	$userToken;
	$peopleInStreamArray = array();


	// get the user's name and token 
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$theName = $nameRow['First'];
		echo $theName;
		echo '<br>';
	}

	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$theStreamName = $streamNameRow['StreamName'];
	}

	//I have the message, now I just need to figure out who to send it to. 

	$peopleInStreamResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID' AND Phone!='$phone'");
	while ($peopleInStreamRow = mysql_fetch_array($peopleInStreamResult))
	{
		array_push($peopleInStreamArray, $peopleInStreamRow['Phone']);
	}

	for ($i = 0; $i < count($peopleInStreamArray); $i++)
	{
		$tempPhone = $peopleInStreamArray[$i];
		$sendPushResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone' and Token!=''");
		while($sendPushRow = mysql_fetch_array($sendPushResult))
		{	
			$tempToken = $sendPushRow['Token'];
			$theMessage = $theName . ' uploaded a photo to ' . $theStreamName . '.';
			$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $tempToken . '&message=' . urlencode($theMessage); 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		}
	}

	for ($j = 0; $j < count($peopleInStreamArray); $j++)
	{
		$tempPhone2 = $peopleInStreamArray[$j];
		$sendPushResult2 = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone2' AND Token=''");
		while($sendPushRow2 = mysql_fetch_array($sendPushResult2))
		{
			$theMessage2 = $theName . ' uploaded a photo to ' . $theStreamName . '. bit.ly/12Dy6u5';
			sendText($tempPhone2, $theMessage2);
		}
	}
}


?>