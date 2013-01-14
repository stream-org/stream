<?PHP

// Invites a user to a stream

//input::
//	stream_id
//	invitees_phone which is either an array of invitees phone numbers or a comma-seperated list of invitee phone number
//  inviter_phone

//output::
//	Status message
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

//example::
//	http://75.101.134.112/stream/1.0/api/invite_users.php?inviter_phone=16508420492&invitees_phone=18477226071&stream_id=d4d18a4f110bfbc3c4a750815a592c6fd167964addc458c01e3c610f52ddcddab53bbdd25177753b88bebd69513594fb39282ef9601614d671bdb96d87d46858

include('dependencies.php');

//grabbing the arguments 
$stream_id = $_GET['stream_id'];
$invitees_phone = $_GET['invitees_phone'];
$inviter_phone = $_GET['inviter_phone'];
$phone_array = explode(',', $invitees_phone);
$output = array();
$should_push_array = array();

for ($i=0; $i < count($phone_array); $i++) 
{ 
	$current_phone = standardizePhone($phone_array[$i]);

	if (substr($current_phone,0,5) != 'Error')
	{

		// Check if user is already part of the stream		
		$user_in_stream_result = mysql_query("SELECT * FROM UserStreams WHERE Phone='$current_phone' AND StreamID = '$stream_id'");
		$user_in_stream_row = mysql_fetch_array($user_in_stream_result);

		if(empty($user_in_stream_row))
		{

			// Checks if a new user or existing user
			$user_exists_result = mysql_query("SELECT Phone FROM Users WHERE Phone='$current_phone'");
			$user_exists_row = mysql_fetch_array($user_exists_result);


			//New User 
			if(empty($user_exists_row)){

				// Get first name for inviter
				$inviter_result = mysql_query("SELECT * FROM Users WHERE Phone='$inviter_phone'");
				$inviter_row = mysql_fetch_array($inviter_result);
				$inviter_first = $inviter_row['First'];
				$inviter_last = $inviter_row['Last'];
				
				mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$current_phone', '$stream_id',1)");
				mysql_query("INSERT INTO Users (Phone, InvitedBy) VALUES ('$current_phone', '$inviter_phone')");

				$textString = $inviter_first . " invited you to Stream! Stream lets you easily share pictures with your close friends. See what " . $inviter_first . " uploaded here: bit.ly/12Dy6u5";

				// Texts new user
				googlevoice_text($current_phone,$textString);

			}

			//Already a user
			else{

				//Get the max stream to user number. This is a feature built out for texting
				$max_stream_to_user_result = mysql_query("SELECT MAX(StreamToUser) FROM UserStreams WHERE Phone='$current_phone'");
				$max_stream_to_user_row = mysql_fetch_array($max_stream_to_user_result);
				$stream_to_user = $max_stream_to_user_row[0] + 1;

				//Insert user into UserStream 
				mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$current_phone', '$stream_id','$stream_to_user')");

				// creates an array of existing users that need to receive either a pushNotif of a textNotif
				array_push($should_push_array, $current_phone);
				
			}
			// check if an error occurred previously
			if(!$output ["status"] == "error")
			{
				$output['status'] = "ok";
			}
		}
		else
		{
			$output['status'] = "error";
			$output['error_description'] = $output['error_description']. " Error: User already in stream: ".$phone_array[$i];
		}
	}
	else
	{
		$output['status'] = "error";
		$output['error_description'] = $output['error_description']. " Error: Improper Phone Number: ".$phone_array[$i];
	}

}

//sends push notifications out to the existing users
invitePushNotification($inviter_phone, $should_push_array, $stream_id);

$output['stream_id'] = $stream_id;
$output['invitees_phone'] = $phone_array;
$output['inviter_phone'] = $inviter_phone;

echo json_encode($output);

?>