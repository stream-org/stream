<?php

include "connection.php";


//gets number standardization function
include "formatPhoneNumbers.php";

$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$token = $_GET['token'];

mysql_query("UPDATE Users SET Token='$token' WHERE Phone='$phone'");

$responseArray['response'] = "okay";

echo json_encode($responseArray);



?>