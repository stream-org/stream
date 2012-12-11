<?php

//input::
//	first name 
//	password 

//output::
//	Boolean (T/F)

//connects to the database
include "connection.php";

//grabbing the arguments 
$first = $_GET['first'];
$password = $_GET['password'];


$responseArray = array();

$result = mysql_query("SELECT * FROM Users WHERE First='$first' AND Phone='$password'");

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
