<?PHP

// Allows a user to sign up

//input::
//	viewer_first 
//	viewer_last 
//	viewer_phone
//	password 

//output::
//	status
//	api_name
//	viewer_first 
//	viewer_last 
//	viewer_phone

// example: (WARNING!! CHANGE THE PHONE NUMBER IN ORDER FOR THIS TO WORK)
// http://75.101.134.112/stream/1.0/api/sign_up.php?viewer_first=johnny&viewer_last=test&viewer_phone=11111111116&password=test


include('dependencies.php');

//grabbing the arguments 
$viewer_first = $_POST['viewer_first'];
$viewer_last = $_POST['viewer_last'];
$viewer_phone = $_POST['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$password = $_POST['password'];

$output = array();

$salt = rand();
$now = date("Y-m-d H:i:s");

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

$user_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone'");
$user_row = mysql_fetch_array($user_result);

// If User has already signed up
if($user_row['JoinDate'])
{
	$output['status'] = "error";
		$output['error_description'] = "User already signed up";
}
else
{

	// User HAS been invited by another user
	if($user_row)
	{
		mysql_query("UPDATE Users SET First ='$viewer_first', Last ='$viewer_last', SALT = '$salt', HashString='$hash', JoinDate='$now' WHERE Phone = '$viewer_phone'");
	}

	// User has NOT been invited by another user
	else
	{
		mysql_query("INSERT INTO Users (First, Last, Phone, SALT, HashString, JoinDate)
			VALUES ('$viewer_first', '$viewer_last', '$viewer_phone', '$salt', '$hash', '$now')");
	}


	// Check if user was properly stored in database
	$user_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone' AND HashString='$hash'");

	$user_row = mysql_fetch_array($user_result);

	$output['viewer_first'] = $user_row['First'];
	$output['viewer_last'] = $user_row['Last'];
	$output['viewer_phone'] = $user_row['Phone'];

	if ($output)
	{
		$output['status'] = "ok";
	}
	else
	{
		$output['status'] = "error";
		$output['error_description'] = "User does not exist";
	}
}

$output['api_name'] = "sign_up";
echo json_encode($output);

?>