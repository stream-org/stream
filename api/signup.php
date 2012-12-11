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

mysql_query("INSERT INTO Users (First, Last, Phone, Password, ProfilePic)
	VALUES ('$first', '$last', '$phone', '$password', '$profilePicture')");

$responseArray = array();
$result = mysql_query("SELECT * FROM Users WHERE First='$first' AND Last='$last' AND Password='$password' AND Phone='$phone'");
$row = mysql_fetch_array($result);

if(empty($row))
{
	$responseArray['value'] = 'false';
}
else 
{
	$responseArray['value'] = $row['Phone'];
}

echo json_encode($responseArray);

?>