<?php

//Registers the unique apple ID token for a given user 

//input::
//	phone
//	token

//example::
//	http://http://75.101.134.112/stream/1.0/api/register_push_token.php?phone=18585238764&token=6e27be3b0190dd6ec5893febc5e92a915e5b7f8aa7d2c5c25f0ae8fa867209a1


include('dependencies.php');

$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$token = $_GET['token'];

mysql_query("UPDATE Users SET Token='$token' WHERE Phone='$phone'");

$responseArray['response'] = "okay";

echo json_encode($responseArray);

?>