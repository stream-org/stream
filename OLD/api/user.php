<?php

//USER.PHP takes in a phone number, and spits out all of the metadata associated with that user.

//connects to the database
include "connection.php";

//gets number standardization function
include "formatPhoneNumbers.php";

//grabbing the arguments 
$phone = $_GET['phone'];
$phone = standardizePhone($phone);

$responseArray = array();

//iterating through the results
$result = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");
while($row = mysql_fetch_array($result))
  {
  $responseArray['First'] = $row['First'];
  $responseArray['Last'] = $row['Last'];
  $responseArray['Phone'] = $row['Phone'];
  $responseArray['Picture'] = $row['ProfilePic'];
  $responseArray['Password'] = $row['Password'];
  $responseArray['JoinDate'] = $row['JoinDate'];
  }

  echo json_encode($responseArray);

?>
