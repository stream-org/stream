<?PHP

//Registers the unique apple ID token for a given user 

//input::
//	viewer_phone
//	token

//ouput::
//	status
//	api_name

//example::
//	http://75.101.134.112/stream/1.0/api/register_push_token.php?viewer_phone=18585238764&token=6e27be3b0190dd6ec5893febc5e92a915e5b7f8aa7d2c5c25f0ae8fa867209a1

$output = array();

include('dependencies.php');

if(empty($_POST))
{
	$viewer_phone = $_GET['viewer_phone'];
	$viewer_phone = standardizePhone($viewer_phone);
	$token = $_GET['token'];
}

if(empty($_GET))
{
	$viewer_phone = $_POST['viewer_phone'];
	$viewer_phone = standardizePhone($viewer_phone);
	$token = $_POST['token'];
}



mysql_query("UPDATE Users SET Token='$token' WHERE Phone='$viewer_phone'");

$output['status'] = "ok";
$output['api_name'] = "register_push_token";

echo json_encode($output);

?>