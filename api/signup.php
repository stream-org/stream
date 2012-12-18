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

//grabbing the arguments 
$first = $_GET['first'];
$last = $_GET['last'];
$phone = $_GET['phone'];
$password = $_GET['password'];
$submit = $_GET['submit'];
$profilePicture = $_GET['profilePicture'];
$salt = rand();

$hash = hash('sha256', $password . $salt);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

mysql_query("INSERT INTO Users (First, Last, Phone, ProfilePic, SALT, HashString)
	VALUES ('$first', '$last', '$phone', '$profilePicture', '$salt', '$hash')");

$responseArray = array();
$result = mysql_query("SELECT * FROM Users WHERE Phone='$phone' AND HashString='$hash'");

while($row = mysql_fetch_array($result))
{
	$responseArray['First'] = $row['First'];
	$responseArray['Last'] = $row['Last'];
	$responseArray['Phone'] = $row['Phone'];
	$responseArray['ProfilePic'] = $row['ProfilePic'];
}

if (empty($responseArray))
{
	$responseArray = null;
}

echo json_encode($responseArray);

?>