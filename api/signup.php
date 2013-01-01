<?php

//input::
//	first name
//	last name
//	phone
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
$first = $_GET['first'];
$last = $_GET['last'];
$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$password = $_GET['password'];
$submit = $_GET['submit'];
$profilePicture = $_GET['profilePicture'];
$salt = rand();
$now = date("Y-m-d H:i:s");

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

$userResult = mysql_query("SELECT Phone FROM Users WHERE Phone='$phone'");
$userRow = mysql_fetch_array($userResult);


// User has NOT been invited by another user
if(empty($userRow)){

	mysql_query("INSERT INTO Users (First, Last, Phone, ProfilePic, SALT, HashString, JoinDate)
		VALUES ('$first', '$last', '$phone', '$profilePicture', '$salt', '$hash', '$now')");

}
// User HAS been invited by another user
else{

	mysql_query("UPDATE Users SET First ='$first', Last ='$last', ProfilePic = '$profilePicture', SALT = '$salt', HashString='$hash', JoinDate='$now' WHERE Phone = '$phone'");

}
$name = $first . " " . $last;

$metrics->track('sign_up', array('name'=>$name, 'phone'=>$phone, 'profile_picture'=>$profilePicture, 'distinct_id'=>$phone));

$responseArray = array();
$result = mysql_query("SELECT * FROM Users WHERE Phone='$phone' AND HashString='$hash'");

while($row = mysql_fetch_array($result))
{
	$responseArray['first'] = $row['First'];
	$responseArray['last'] = $row['Last'];
	$responseArray['phone'] = $row['Phone'];
	$responseArray['profilePic'] = $row['ProfilePic'];
}


if (empty($responseArray))
{
	$responseArray = null;
}

echo json_encode($responseArray);

?>