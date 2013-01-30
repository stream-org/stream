<?PHP
// Reset the badge number for a user

//input::
//	user_feedback
//	user_phone
// 	feedback_category

//output:: 
//	user_feedback
//	user_phone
// 	feedback_category

// Example: 
// http://75.101.134.112/stream/1.0/api/reset_badge.php?user_phone=8477226071


include('dependencies.php');


// grabbing the arguments
if(empty($_POST))
{

	$user_phone = $_GET['user_phone'];

}

if(empty($_GET))
{
	$user_phone = $_POST['user_phone'];
}

$user_phone = standardizePhone($user_phone);

$output['user_phone'] = $user_phone;

//Reset user's badge to 0
mysql_query("UPDATE Users SET BadgeCount = 0 WHERE Phone = '$user_phone'");


if(mysql_affected_rows()==0)
{
	$output ["status"] = "ok";
}


else 
{
	$output['status'] = "error";
	$output['error_description'] = "feedback not recorded.";
}

echo json_encode($output);

?>