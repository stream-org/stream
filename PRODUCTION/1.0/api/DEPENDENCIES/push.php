<?PHP

require_once('connection.php');
require_once('mixPanel.php');
require_once('sendText.php');
require_once('googlevoice_text.php');

function likePushNotification($liker_phone, $picture_id)
{
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

	//fetching the uploader's phone number by using the $picture_id
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
			$message = $liker_name . ' likes the photo you posted on ' . $stream_name . '.';
			$url = 'http://75.101.134.112/stream/api/push_notification.php?token=' . $uploader_token . '&message=' . urlencode($message); 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		  	$metrics->track('like_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$uploaderPhone,'liker_phone'=>$phone,'liked_picture'=>$pictureID,'distinct_id'=>$pictureID));
		}
		else
		{
			$message = $liker_name . ' likes the photo you posted on ' . $stream_name . '. bit.ly/12Dy6u5';
			sendText($uploader_phone, $message);	
			$metrics->track('like_notification', array('notification_type'=>'text','notified_phone'=>$uploaderPhone,'liker_phone'=>$liker_phone,'liked_picture'=>$picture_id,'distinct_id'=>$picture_id));
		}
	}
}

//this is used for inviting someone post stream creation.
function singleInvitePushNotification($inviter_phone, $invitee_phone, $stream_id)
{
	$inviter_name;
	$stream_name;
	$invitee_token;

	//fetching the inviter's name by using the $inviter_phone
	$inviter_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$inviter_phone'");
	while($inviter_name_row = mysql_fetch_array($inviter_name_result))
	{
		$inviter_name = $inviter_name_row['First'];
	}

	//fetching the stream name by using the $stream_id
	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	//fetching the invitee's token by using the invitee's phone number 
	$invitee_token_result = mysql_query("SELECT * FROM Users WHERE Phone='$invitee_phone'");
	while($invitee_token_row = mysql_fetch_array($invitee_token_result))
	{
		$invitee_token = $invitee_token_row['Token'];
	}

	//if the user has a token, send them a push notification. if not, send them a text
	if($invitee_token != '')
	{
		$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream.';
		$url = 'http://75.101.134.112/stream/api/push_notification.php?token=' . $invitee_token . '&message=' . urlencode($message); 
		$ch = curl_init($url);
		$response = curl_exec($ch);
		curl_close($ch);
		$metrics->track('invite_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$invitee_phone,'stream_invited_to'=>$stream_id,'distinct_id'=>$stream_id));
	}
	else
	{
		$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream. bit.ly/12Dy6u5';
		googlevoice_text($invitee_phone, $message);	
		$metrics->track('invite_notification', array('notification_type'=>'text','notified_phone'=>$invitee_phone,'stream_invited_to'=>$stream_id,'distinct_id'=>$stream_id));
	}
}

//this is used for stream creation
function multipleInvitePushNotification($inviter_phone, $stream_id)
{
	$inviter_name;
	$stream_name;
	$user_token;
	$invited_iPhone_users_array = array();
	$invited_non_iPhone_users_array = array();

	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

	//fetching the inviter's name by using the $inviter_phone
	$inviter_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$inviter_phone'");
	while($inviter_name_row = mysql_fetch_array($inviter_name_result))
	{
		$inviter_name = $inviter_name_row['First'];
	}

	//fetching the stream name by using the $stream_id
	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	//fetching the users that have iPhones and that are in the stream, and putting their Tokens in an array
	$invited_iPhone_users_array_result = mysql_query("SELECT * FROM UserStreams, Users WHERE UserStreams.Phone=Users.Phone AND StreamID='$stream_id' AND Token!=''");
	while($invited_iPhone_users_array_row = mysql_fetch_array($invited_iPhone_users_array_result))
	{
		if($invited_iPhone_users_array_row['Phone'] == $inviter_phone)
		{
			continue;
		}
		array_push($invited_iPhone_users_array, $invited_iPhone_users_array_row['Token']);
	}

	//fetching the users that don't have iPhones and that are in the stream, and putting their phone number in an array
	$invited_non_iPhone_users_array_result = mysql_query("SELECT * FROM UserStreams, Users WHERE UserStreams.Phone=Users.Phone AND StreamID='$stream_id' AND Token=''");
	while($invited_non_iPhone_users_array_row = mysql_fetch_array($invited_non_iPhone_users_array_result))
	{
		if($invited_non_iPhone_users_array_row['Phone'] == $inviter_phone)
		{
			continue;
		}
		array_push($invited_non_iPhone_users_array, $invited_non_iPhone_users_array_row['Phone']);
	}

	//sending push notifications out to the iPhone users

	for($iPhone_user_token_index = 0; $iPhone_user_token_index < count($invited_iPhone_users_array); $iPhone_user_token_index++)
	{
		$current_token = $invited_iPhone_users_array[$iPhone_user_token_index];
		$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream.';
		$url = 'http://75.101.134.112/stream/api/push_notification.php?token=' . $current_token . '&message=' . urlencode($message); 
	  	$ch = curl_init($url);
	  	$response = curl_exec($ch);
	  	curl_close($ch);

	  	$metrics->track('invite_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$current_token,'stream_invited_to'=>$stream_id,'distinct_id'=>$stream_id));
	}

	//sending text messages out to non iPhone users
	for($non_iPhone_user_phone_index = 0; $non_iPhone_user_phone_index < count($invited_non_iPhone_users_array); $non_iPhone_user_phone_index++)
	{
		$current_phone = $invited_non_iPhone_users_array[$non_iPhone_user_phone_index];
		$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream. bit.ly/12Dy6u5';
		googlevoice_text($current_phone, $message);

		$metrics->track('invite_notification', array('notification_type'=>'text','notified_phone'=>$current_phone,'stream_invited_to'=>$stream_id,'distinct_id'=>$stream_id));
	}
}

function uploadPicturePushNotification($uploader_phone, $stream_id)
{
	$uploader_name;
	$stream_name;
	$user_token;
	$invited_iPhone_users_array = array();
	$invited_non_iPhone_users_array = array();

	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

	//fetching the uploader's name by using $uploader_phone
	$uploader_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploader_phone'");
	while($uploader_name_row = mysql_fetch_array($uploader_name_result))
	{
		$uploader_name = $uploader_name_row['First'];
	}

	echo $uploader_name;
	echo '<br>';

	//fetching the stream name by using the $stream_id
	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	echo $stream_name;
	echo '<br>';

		//fetching the users that have iPhones and that are in the stream, and putting their Tokens in an array
	$invited_iPhone_users_array_result = mysql_query("SELECT * FROM UserStreams, Users WHERE UserStreams.Phone=Users.Phone AND StreamID='$stream_id' AND Token!=''");
	while($invited_iPhone_users_array_row = mysql_fetch_array($invited_iPhone_users_array_result))
	{
		if($invited_iPhone_users_array_row['Phone'] == $uploader_phone)
		{
			continue;
		}
		array_push($invited_iPhone_users_array, $invited_iPhone_users_array_row['Token']);
	}

	//fetching the users that don't have iPhones and that are in the stream, and putting their phone number in an array
	$invited_non_iPhone_users_array_result = mysql_query("SELECT * FROM UserStreams, Users WHERE UserStreams.Phone=Users.Phone AND StreamID='$stream_id' AND Token=''");
	while($invited_non_iPhone_users_array_row = mysql_fetch_array($invited_non_iPhone_users_array_result))
	{
		if($invited_non_iPhone_users_array_row['Phone'] == $uploader_phone)
		{
			continue;
		}
		array_push($invited_non_iPhone_users_array, $invited_non_iPhone_users_array_row['Phone']);
	}

	//sending push notifications out to the iPhone users

	for($iPhone_user_token_index = 0; $iPhone_user_token_index < count($invited_iPhone_users_array); $iPhone_user_token_index++)
	{
		$current_token = $invited_iPhone_users_array[$iPhone_user_token_index];
		$message = $uploader_name . ' just uploaded a photo to ' . $stream_name . '.';
		$url = 'http://75.101.134.112/stream/api/push_notification.php?token=' . $current_token . '&message=' . urlencode($message); 
	  	$ch = curl_init($url);
	  	$response = curl_exec($ch);
	  	curl_close($ch);

	  	$metrics->track('photo_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$current_token,'stream_uploaded_to'=>$stream_id,'distinct_id'=>$stream_id));
	}

	//sending text messages out to non iPhone users
	for($non_iPhone_user_phone_index = 0; $non_iPhone_user_phone_index < count($invited_non_iPhone_users_array); $non_iPhone_user_phone_index++)
	{
		$current_phone = $invited_non_iPhone_users_array[$non_iPhone_user_phone_index];
		$message = $uploader_name . ' just uploaded a photo to ' . $stream_name . ' stream. bit.ly/12Dy6u5';
		googlevoice_text($current_phone, $message);

		$metrics->track('photo_notification', array('notification_type'=>'text','notified_phone'=>$current_phone,'stream_uploaded_to'=>$stream_id,'distinct_id'=>$stream_id));
	}
}

?>