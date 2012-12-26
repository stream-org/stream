<?php

include "connection.php";

$phone = $_GET['phone'];
$token = $_GET['token'];

mysql_query("UPDATE Users SET Token='$token' WHERE Phone='$phone'");

$responseArray['response'] = "okay";

echo json_encode($responseArray);



?>