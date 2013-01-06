<?PHP

require_once('connection.php');
require_once('mixPanel.php');

function likePushNotification($liker_phone, $picture_id)
{
	//variable names
	$liker_name;
	$stream_name;
	$uploader_token;
	$uploader_phone;
	$stream_id;

	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

	//fetching the name of the person who liked the photo
	$liker_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$liker_phone'");
	while($liker_name_row = mysql_fetch_array($liker_name_result))
	{
		$liker_name = $liker_name_row['First'];
	}
	echo $liker_name;
	echo '<br>';

	//finding the uploader's phone number by using the $picture_id
	$uploader_phone_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id'");
	while($uploader_phone_row = mysql_fetch_array($uploader_phone_result))
	{
		$uploader_phone = $uploader_phone_row['Phone'];
		$stream_id = $uploader_phone_row['StreamID'];
	}

	//fetching the name of the stream that the photo was uploaded to using the $stream_id
	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	//fetching the token of the person who uploaded the photo and sending them a push notification
	$uploader_information_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploader_phone'");
	while($uploader_information_row = mysql_fetch_array($uploader_information_result))
	{
		$uploader_token = $uploader_information_row['Token'];
		if($uploader_token !== '')
		{
			$the_message = $liker_name . ' likes the photo you posted on ' . $stream_name . '.';
			$url = 'http://75.101.134.112/stream/api/pushNotification.php?token=' . $uploader_token . '&message=' . urlencode($the_message); 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		  	$metrics->track('like_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$uploaderPhone,'liker_phone'=>$phone,'liked_picture'=>$pictureID,'distinct_id'=>$pictureID));
		}
		else
		{
			$the_message = $liker_name . ' likes the photo you posted on ' . $stream_name . '.';
			sendText($uploader_phone, $the_message);	
			$metrics->track('like_notification', array('notification_type'=>'text','notified_phone'=>$uploaderPhone,'liker_phone'=>$liker_phone,'liked_picture'=>$picture_id,'distinct_id'=>$picture_id));
		}
	}
}



// function inviteSinglePush($inviterPhone, $inviteePhone, $streamID)
// {
// 	$theName;
// 	$theStreamName;
// 	$userToken;

// 	// get the user's name and token 
// 	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$inviterPhone'");

// 	while ($nameRow = mysql_fetch_array($nameResult))
// 	{
// 		$theName = $nameRow['First'];
// 	}

// 	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

// 	while($streamNameRow = mysql_fetch_array($streamNameResult))
// 	{
// 		$theStreamName = $streamNameRow['StreamName'];
// 	}



// 	$tokenResult = mysql_query("SELECT * FROM Users WHERE Phone='$inviteePhone' AND Token!=''");
// 	while($tokenRow = mysql_fetch_array($tokenResult))
// 	{
// 		$userToken = $tokenRow['Token'];
// 	}

// 	$theMessage = $theName . ' invited you to the ' . $theStreamName . ' stream.';
// 	$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $userToken . '&message=' . urlencode($theMessage); 
// 	$ch = curl_init($url);
// 	$response = curl_exec($ch);
// 	curl_close($ch);

// }

// function invitePush($phone, $streamID)
// {
// 	$theName;
// 	$theStreamName;
// 	$userToken;
// 	$peopleInStreamArray = array();
// 	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

// 	// get the user's name and token 
// 	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

// 	while ($nameRow = mysql_fetch_array($nameResult))
// 	{
// 		$theName = $nameRow['First'];
// 	}

// 	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

// 	while($streamNameRow = mysql_fetch_array($streamNameResult))
// 	{
// 		$theStreamName = $streamNameRow['StreamName'];
// 		echo $theStreamName;
// 		echo '<br>';
// 	}

// 	//I have the message, now I just need to figure out who to send it to. 

// 	$peopleInStreamResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID' AND Phone!='$phone'");
// 	while ($peopleInStreamRow = mysql_fetch_array($peopleInStreamResult))
// 	{
// 		array_push($peopleInStreamArray, $peopleInStreamRow['Phone']);
// 		// echo $peopleInStreamRow['Phone'];
// 	}

// 	for ($i = 0; $i < count($peopleInStreamArray); $i++)
// 	{
// 		$tempPhone = $peopleInStreamArray[$i];
// 		$sendPushResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone' and Token!=''");
// 		while($sendPushRow = mysql_fetch_array($sendPushResult))
// 		{	
// 			$tempToken = $sendPushRow['Token'];
// 			$theMessage = $theName . ' invited you to the ' . $theStreamName . ' stream.';
// 			$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $tempToken . '&message=' . urlencode($theMessage); 
// 		  	$ch = curl_init($url);
// 		  	$response = curl_exec($ch);
// 		  	curl_close($ch);
// 		}
// 		$metrics->track('invite_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$tempPhone,'stream_invited_to'=>$streamID,'distinct_id'=>$streamID));
// 	}

// 	for ($j = 0; $j < count($peopleInStreamArray); $j++)
// 	{
// 		$tempPhone2 = $peopleInStreamArray[$j];
// 		$sendPushResult2 = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone2' AND Token=''");
// 		while($sendPushRow2 = mysql_fetch_array($sendPushResult2))
// 		{
// 			$theMessage2 = $theName . ' invited you to the ' . $theStreamName . ' stream. bit.ly/12Dy6u5';
// 			sendText($tempPhone2, $theMessage2);
// 		}
// 		$metrics->track('invite_notification', array('notification_type'=>'text','notified_phone'=>$tempPhone2,'stream_invited_to'=>$streamID,'distinct_id'=>$streamID));
// 	}	
// }


// function photoPush($phone, $streamID)
// {
// 	$theName;
// 	$theStreamName;
// 	$userToken;
// 	$peopleInStreamArray = array();

// 	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

// 	// get the user's name and token 
// 	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

// 	while ($nameRow = mysql_fetch_array($nameResult))
// 	{
// 		$theName = $nameRow['First'];
// 		echo $theName;
// 		echo '<br>';
// 	}

// 	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

// 	while($streamNameRow = mysql_fetch_array($streamNameResult))
// 	{
// 		$theStreamName = $streamNameRow['StreamName'];
// 	}

// 	//I have the message, now I just need to figure out who to send it to. 

// 	$peopleInStreamResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID' AND Phone!='$phone'");
// 	while ($peopleInStreamRow = mysql_fetch_array($peopleInStreamResult))
// 	{
// 		array_push($peopleInStreamArray, $peopleInStreamRow['Phone']);
// 	}

// 	for ($i = 0; $i < count($peopleInStreamArray); $i++)
// 	{
// 		$tempPhone = $peopleInStreamArray[$i];
// 		$sendPushResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone' and Token!=''");
// 		while($sendPushRow = mysql_fetch_array($sendPushResult))
// 		{	
// 			$tempToken = $sendPushRow['Token'];
// 			$theMessage = $theName . ' uploaded a photo to ' . $theStreamName . '.';
// 			$url = 'http://75.101.134.112/api/pushNotification.php?token=' . $tempToken . '&message=' . urlencode($theMessage); 
// 		  	$ch = curl_init($url);
// 		  	$response = curl_exec($ch);
// 		  	curl_close($ch);
// 		}

// 		$metrics->track('photo_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$tempPhone,'stream_uploaded_to'=>$streamID,'distinct_id'=>$tempPhone));

// 	}

// 	for ($j = 0; $j < count($peopleInStreamArray); $j++)
// 	{
// 		$tempPhone2 = $peopleInStreamArray[$j];
// 		$sendPushResult2 = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone2' AND Token=''");
// 		while($sendPushRow2 = mysql_fetch_array($sendPushResult2))
// 		{
// 			$theMessage2 = $theName . ' uploaded a photo to ' . $theStreamName . '. bit.ly/12Dy6u5';
// 			sendText($tempPhone2, $theMessage2);
// 		}
// 		$metrics->track('photo_notification', array('notification_type'=>'text','notified_phone'=>$tempPhone2,'stream_uploaded_to'=>$streamID,'distinct_id'=>$tempPhone));
// 	}
// }


?>