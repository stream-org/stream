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

//grabbing the arguments 
$phone = $_GET['first'];
$phone = standardizePhone($phone);
$internationalCode;
$password = $_GET['password'];
$salt;
$responseArray = array();

if (strlen($phone) == 11)
{
	$internationalCode = true;
}

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
//

if (empty($responseArray))
{
	//does it already have the international code?

	if ($internationalCode) 
	{
		//code if we need to strip the international code
		$phone = substr($phone, 1);

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
	}

	else
	{
		//code for adding the internation code
		$phone = '1' . $phone;

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
	}
}

if (empty($responseArray))
{
	$responseArray = null;
}


echo json_encode($responseArray);

?>
