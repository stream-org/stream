<?PHP

// Invites a user to a stream

//input::
//	stream_id
//	invitees_phone which is either an array of invitees phone numbers or a comma-seperated list of invitee phone number
//  inviter_phone

//output::
//	api_name
//	Status message
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

//example::
//	this gets called in create_stream.php

include('dependencies.php');

//grabbing the arguments 

if(empty($_POST))
{
	$stream_id = $_GET['stream_id'];
	$invitees_phone = $_GET['invitees_phone'];
	$invitees_first = $_GET['invitees_first'];
	$invitees_last = $_GET['invitees_last'];
	$inviter_phone = $_GET['inviter_phone'];
}

if(empty($_GET))
{
	$stream_id = $_POST['stream_id'];
	$invitees_phone = $_POST['invitees_phone'];
	$invitees_first = $_POST['invitees_first'];
	$invitees_last = $_POST['invitees_last'];
	$inviter_phone = $_POST['inviter_phone'];
}

$phone_array = explode(',', $invitees_phone);
$output = array();
$should_push_array = array();

for ($i=0; $i < count($phone_array); $i++) 
{ 
	$current_phone = standardizePhone($phone_array[$i]);

	//only invite if phone number a valid 
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

				mysql_query("INSERT INTO Users (First, Phone, InvitedBy) VALUES ('$current_phone','$current_phone', '$inviter_phone')");
				mysql_query("INSERT INTO UserStreams (Phone, StreamID, StreamToUser) VALUES ('$current_phone', '$stream_id',99)");
				
				
				// creates an array of existing users that need to receive either a pushNotif of a textNotif
				array_push($should_push_array, $current_phone);

			}

			//Already a user
			else{

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

//sends push notifications out to invited users
invitePushNotification($inviter_phone, $should_push_array, $stream_id);

$output['stream_id'] = $stream_id;
$output['invitees_phone'] = $phone_array;
$output['inviter_phone'] = $inviter_phone;
$output['api_name'] = "invite_users";

echo json_encode($output);

?>