<?PHP

// Allows a user to sign in

//input::
//	viewer_phone
//	password 

//output::
//	phone
//  if succesfull login
//  	first name
//	    last name
// 		profile pic
// 	else
// 		just phone

include('dependencies.php');

//grabbing the arguments 
$viewer_phone = $_GET['first'];
$viewer_phone = standardizePhone($viewer_phone);
$password = $_GET['password'];
$salt;
$output = array();

//get the salt
$salt_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone'");

while ($salt_row = mysql_fetch_array($salt_result))
{
	$salt = $usernameRow['SALT'];
}

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

$signin_result = mysql_query("SELECT * FROM Users WHERE Phone='$viewer_phone' AND HashString='$hash'");



if($signin_result){
	while($signin_row = mysql_fetch_array($signin_result))
	{
		$output['viewer_first'] = $signin_row['First'];
		$output['viewer_last'] = $signin_row['Last'];
		$output['viewer_profilepic'] = $signin_row['ProfilePic'];
	}

	$output['status'] = "ok";
}
else{
	$output['status'] = "error";
	$output['error_description'] = "Incorrect phone number or password!";
}

$output['viewer_phone'] = $viewer_phone;

echo json_encode($output);

?>
