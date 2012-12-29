<?php

//input::
//	first name 
//	password 

//output::
//	Boolean (T/F)

//connects to the database
include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//Mixpanel Tracking
require_once("mixPanel.php");
$metrics = new MetricsTracker("b0002cbf8ca96f2dfdd463bdc2902c28");

//grabbing the arguments 
$phone = $_GET['first'];
$phone = standardizePhone($phone);
$password = $_GET['password'];
$salt;
$responseArray = array();

//get the salt
$usernameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

while ($usernameRow = mysql_fetch_array($usernameResult))
{
	$salt = $usernameRow['SALT'];
}

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

$result = mysql_query("SELECT * FROM Users WHERE Phone='$phone' AND HashString='$hash'");

while($row = mysql_fetch_array($result))
{
	$responseArray['first'] = $row['First'];
	$responseArray['last'] = $row['Last'];
	$responseArray['phone'] = $row['Phone'];
	$responseArray['profilepic'] = $row['ProfilePic'];
}

if (empty($responseArray))
{
	$responseArray = null;
}

else{
	$name  = $responseArray['first']. " ".$responseArray['last'];
	$metrics->track('sign_in', array('name'=>$name, 'phone'=>$phone,'distinct_id'=>$phone));
}

echo json_encode($responseArray);

?>
