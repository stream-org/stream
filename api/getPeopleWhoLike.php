<?php

include "connection.php";

//grabbing the arguments 
$picture = $_GET['picture'];

// $picture = urlencode($picture);

$result = mysql_query("SELECT * FROM PictureLikes WHERE Picture='$picture'");
$responseArray = array();
while ($row = mysql_fetch_array($result))
{
	array_push($responseArray, $row[1]);
}

echo json_encode($responseArray);

?>