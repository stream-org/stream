<html> 
<head> 
	<title>Page Title</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<style type="text/css">
	</style>
	
</head>

<script>

function redirect() {
	window.location="/register/index.php";
}

</script>
<?php

//SIGNUP.PHP
//input: first name, last name, phone, password 
//output: boolean T/F

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

function sendText($phoneNumber, $textString){
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}

sendText($phone, "Welcome to Champagne and Shackles! Reply with the word 'Stream' and a photo!");

$responseArray = array();
$result = mysql_query("SELECT * FROM Users WHERE First='$first' AND Last='$last' AND Password='$password' AND Phone='$phone'");
$row = mysql_fetch_array($result);

if(empty($row))
{
	$responseArray['value'] = 'false';
}
else 
{
	$responseArray['value'] = 'true';
}

echo json_encode($responseArray);

?>

	<div data-role="page" id="confirmation" data-theme="a" style="background:url(stream_desktop_bg.png) no-repeat center" > 
		<div data-role="content" align="center">
			<br><br><br><br>
			<br><br><br><br>
			<br><br><br><br>
			<br><br>
			

			<h1 style="color:white">Champagne &amp; Shackles</h1>
			<br>
			<h2>Thanks!</h2>
			<p>Please check your phone to confirm you were added. Then grab a bottle of champagne and start sharing photos!</p>
			<br>
			<br>
			<div style="width: 300px"><button data-theme="a" onClick="redirect()">All Done!</button></div>
		</div>
	</div> 


</html>
