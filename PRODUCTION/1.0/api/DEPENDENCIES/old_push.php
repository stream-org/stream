<?PHP

require_once('connection.php');
require_once('mixPanel.php');
require_once('twilio_text.php');
require_once('shorten.php');

// likePushNotification('18477226071', '297e5926fc018131177d4b4f2673aa4b1619773d073d1cb5582dab6ed14e9592e713f5bf0d787a82a3e627c97ef27d6c13762d8e3bd3c457c062470a10a288cc');

//like push notifciations
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
			$badge_count = $uploader_information_row['BadgeCount'] +1;
			$message = $liker_name . ' likes the photo you posted on ' . $stream_name . '.';
			$url = 'http://75.101.134.112/stream/1.0/api/push_notification.php?token=' . $uploader_token . '&message=' . urlencode($message) . '&picture_id=' . $picture_id.'&badge_count='.$badge_count; 
		  	$ch = curl_init($url);
		  	$response = curl_exec($ch);
		  	curl_close($ch);
		  	$metrics->track('like_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$uploaderPhone,'liker_phone'=>$phone,'liked_picture'=>$pictureID,'distinct_id'=>$pictureID));
		}
		else
		{
			$message = $liker_name . ' likes the photo you posted on ' . $stream_name . '. bit.ly/12Dy6u5';
			twilio_text($uploader_phone, $message);	
			$metrics->track('like_notification', array('notification_type'=>'text','notified_phone'=>$uploaderPhone,'liker_phone'=>$liker_phone,'liked_picture'=>$picture_id,'distinct_id'=>$picture_id));
		}
	}
}

//invite push notifications
function invitePushNotification($inviter_phone, $invitee_array, $stream_id)
{	
	$inviter_name;
	$stream_name;

	$stream_name_result = mysql_query("SELECT * FROM Streams WHERE StreamID='$stream_id'");
	while($stream_name_row = mysql_fetch_array($stream_name_result))
	{
		$stream_name = $stream_name_row['StreamName'];
	}

	$inviter_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$inviter_phone'");
	while($inviter_name_row = mysql_fetch_array($inviter_name_result))
	{
		$inviter_name = $inviter_name_row['First'];
	}

	for ($i = 0; $i < count($invitee_array); $i++)
	{
		$current_phone = $invitee_array[$i];
		$invited_user_result = mysql_query("SELECT * FROM Users WHERE Phone='$current_phone'");
		$invited_user_row = mysql_fetch_array($invited_user_result);

		if($invited_user_row['Token'] != '')
		{
			$current_token = $invited_user_row['Token'];
			$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream.';
			$url = 'http://75.101.134.112/stream/1.0/api/push_notification.php?token=' . $current_token . '&message=' . urlencode($message); 
			$ch = curl_init($url);
			$response = curl_exec($ch);
			curl_close($ch);
		}

		elseif($invited_user_row['Token'] == '' and $invited_user_row['JoinDate'] != 0)
		{
			$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream. bit.ly/12Dy6u5';
	 		twilio_text($current_phone, $message);	
		}

		elseif($invited_user_row['Token'] == '' and $invited_user_row['JoinDate'] == 0)
		{
			$url = "75.101.134.112/test.php?phone=" . $current_phone;
			$shortUrl = shorten($url);
			$message = $inviter_name . ' invited you to the ' . $stream_name . ' stream. ' . $shortUrl;
	 		twilio_text($current_phone, $message);		
		}
	}
}

//comment push notifications
function commentPushNotification($commenter_phone, $picture_id, $comment)
{
	$commenter_name;
	$stream_name;
	$uploader_token;
	$uploader_phone;
	$stream_id;

	$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

	// Get the first 10 characters of the comment
	$comment_snippet = substr($comment, 0, 100);

	//fetching the name of the person who commented on the photo
	$commenter_name_result = mysql_query("SELECT * FROM Users WHERE Phone='$commenter_phone'");
	while($commenter_name_row = mysql_fetch_array($commenter_name_result))
	{
		$commenter_name = $commenter_name_row['First'];
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

	
	//if the commenter is not the uploader, fetch the token of the person who uploaded the photo and send him/her a push notification
	if($commenter_phone <> $uploader_phone)
	{
		$uploader_information_result = mysql_query("SELECT * FROM Users WHERE Phone='$uploader_phone'");
		while($uploader_information_row = mysql_fetch_array($uploader_information_result))
		{
			$uploader_token = $uploader_information_row['Token'];
			if($uploader_token !== '')
			{
				$message = $commenter_name . ' commented the photo you posted on ' . $stream_name . ': '.$comment_snippet.'...';
				$url = 'http://75.101.134.112/stream/1.0/api/push_notification.php?token=' . $uploader_token . '&message=' . urlencode($message); 
			  	$ch = curl_init($url);
			  	$response = curl_exec($ch);
			  	curl_close($ch);
			  	$metrics->track('comment_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$uploaderPhone,'commenter_phone'=>$commenter_phone,'commented_picture'=>$pictureID,'distinct_id'=>$pictureID));
			}
			else
			{
				$message = $commenter_name . ' commented on the photo you posted on ' . $stream_name . ': '. $comment_snippet.'... bit.ly/12Dy6u5';
				twilio_text($uploader_phone, $message);	
				$metrics->track('comment_notification', array('notification_type'=>'text','notified_phone'=>$uploaderPhone,'commenter_phone'=>$commenter_phone,'commented_picture'=>$picture_id,'distinct_id'=>$picture_id));
			}
		}
	}

	// Send a text to everyone in the stream that isn't the commenter or the uploader
	$users_in_stream_result = mysql_query("SELECT Phone FROM UserStreams WHERE StreamID='$stream_id' AND Phone != $commenter_phone AND Phone != $uploader_phone");

	while($users_in_stream_row = mysql_fetch_array($users_in_stream_result))
	{
		$user_phone = $users_in_stream_row['Phone'];
		$user_information_result = mysql_query("SELECT * FROM Users WHERE Phone='$user_phone'");
		while($user_information_row = mysql_fetch_array($user_information_result))
		{
			$user_token = $user_information_row['Token'];
			if($user_token !== '')
			{
				$message = $commenter_name . ' commented on a photo in ' . $stream_name . ': '.$comment_snippet.'...';
				$url = 'http://75.101.134.112/stream/1.0/api/push_notification.php?token=' . $user_token . '&message=' . urlencode($message); 
			  	$ch = curl_init($url);
			  	$response = curl_exec($ch);
			  	curl_close($ch);
			  	$metrics->track('comment_notification', array('notification_type'=>'iPhone Push','notified_phone'=>$user_phone,'commenter_phone'=>$commenter_phone,'commented_picture'=>$picture_id,'distinct_id'=>$picture_id));
			}
			else
			{
				$message = $commenter_name . ' commented on a photo in ' . $stream_name . ': '.$comment_snippet.'... bit.ly/12Dy6u5';
				twilio_text($user_phone, $message);	
				$metrics->track('comment_notification', array('notification_type'=>'text','notified_phone'=>$user_phone,'commenter_phone'=>$commenter_phone,'commented_picture'=>$picture_id,'distinct_id'=>$picture_id));
			}
		}


	}


}

//upload push notifications
function uploadPicturePushNotification($uploader_phone, $stream_id)
{
	$uploader_name;
	$stream_name;
	$user_token;
	$invited_iPhone_users = array();
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
	$invited_iPhone_users_result = mysql_query("SELECT * FROM UserStreams, Users WHERE UserStreams.Phone=Users.Phone AND StreamID='$stream_id' AND Token!=''");
	while($invited_iPhone_users_row = mysql_fetch_array($invited_iPhone_users_result))
	{
		if($invited_iPhone_users_row['Phone'] == $uploader_phone)
		{
			continue;
		}
		array_push($invited_iPhone_users, $invited_iPhone_users_row['Token']);
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

	for($iPhone_user_token_index = 0; $iPhone_user_token_index < count($invited_iPhone_users); $iPhone_user_token_index++)
	{
		$current_token = $invited_iPhone_users[$iPhone_user_token_index];
		$message = $uploader_name . ' just uploaded a photo to ' . $stream_name . '.';
		$url = 'http://75.101.134.112/stream/1.0/api/push_notification.php?token=' . $current_token . '&message=' . urlencode($message); 
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
		twilio_text($current_phone, $message);

		$metrics->track('photo_notification', array('notification_type'=>'text','notified_phone'=>$current_phone,'stream_uploaded_to'=>$stream_id,'distinct_id'=>$stream_id));
	}
}

?>