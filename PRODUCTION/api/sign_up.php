<?PHP

// Allows a user to sign up

//input::
//	viewer_first name
//	viewer_last name
//	viewer_phone
//	password 

//output::
//	Boolean (T/F)

include('dependencies.php');

//grabbing the arguments 
$viewer_first = $_GET['viewer_first'];
$viewer_last = $_GET['viewer_last'];
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$password = $_GET['password'];

$salt = rand();
$now = date("Y-m-d H:i:s");

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

$user_result = mysql_query("SELECT Phone FROM Users WHERE Phone='$viewer_phone'");
$user_row = mysql_fetch_array($user_result);


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

$output = array();
$user_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone' AND HashString='$hash'");

while($user_row = mysql_fetch_array($user_result))
{
	$output['viewer_first'] = $user_row['First'];
	$output['viewer_last'] = $user_row['Last'];
	$output['viewer_phone'] = $user_row['Phone'];
}


if ($output)
{
	$output['status'] = "ok";
}
else
{
	$output['status'] = "error";
	$output['error_description'] = "User does not exist";
}


echo json_encode($output);

?>