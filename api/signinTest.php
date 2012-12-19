<?php

//input::
//	first name 
//	password 

//output::
//	Boolean (T/F)

//connects to the database
include "connection.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$password = $_GET['password'];
$salt;
$responseArray = array();

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

echo json_encode($responseArray);
?>
