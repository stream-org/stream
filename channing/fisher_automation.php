<?php
// connects to our EC2 environment
include "connection.php";

// grabs the URL input from form and saves
$URL = $_GET['URL'];

// need to make this take $URL as an argument 
// and run FI_testScripts on it.
$spannedURL = exec('python FI_testScripts.py ' . $URL)

// return the spanned URL
echo json_encode($spannedURL);

?>