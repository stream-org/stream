<?PHP

// Allows a user to sign in

//input::
//	viewer_phone
//	password 

//output::
// 	status
//	viewer_phone
//  if successful login (correct phone number and password):
//  	-viewer_first
//	    -viewer_last
// 		-viewer_profilepic

// example:
// http://75.101.134.112/stream/1.0/api/sign_in.php?viewer_phone=11111111116&password=test

include('dependencies.php');

//grabbing the arguments 
$viewer_phone = $_GET['viewer_phone'];
$viewer_phone = standardizePhone($viewer_phone);
$password = $_GET['password'];
$output = array();

//get the salt
$salt_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone'");

$salt_row = mysql_fetch_array($salt_result);

$salt = $salt_row['SALT'];


$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

// Check if the sign is was successful
$signin_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone' AND HashString='$hash'");

$signin_row = mysql_fetch_array($signin_result);

if($signin_row)
{	
	$output['viewer_first'] = $signin_row['First'];
	$output['viewer_last'] = $signin_row['Last'];
	$output['viewer_profilepic'] = $signin_row['ProfilePic'];
	$output['status'] = "ok";
}
else
{
	$output['status'] = "error";
	$output['error_description'] = "Incorrect phone number or password!";
}

$output['viewer_phone'] = $viewer_phone;

echo json_encode($output);

?>
