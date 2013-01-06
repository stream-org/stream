<?PHP

include('connection.php');

$phone = $_GET['phone'];
$streamID = $_GET['streamID'];

iPhonePush($phone, $streamID);

function iPhonePush($phone, $streamID)
{
	$theName;
	$theStreamName;
	$userToken;
	$peopleInStreamArray = array();


	// get the user's name and token 
	$nameResult = mysql_query("SELECT * FROM Users WHERE Phone='$phone'");

	while ($nameRow = mysql_fetch_array($nameResult))
	{
		$theName = $nameRow['First'];
		echo $theName;
		echo '<br>';
	}

	$streamNameResult = mysql_query("SELECT * FROM Streams WHERE StreamID='$streamID'");

	while($streamNameRow = mysql_fetch_array($streamNameResult))
	{
		$theStreamName = $streamNameRow['StreamName'];
	}

	echo $theStreamName;
	echo '<br>';
	$theMessage = $theName . ' uploaded a photo to ' . $theStreamName;
	$theMessage = urlencode($theMessage);
	echo $theMessage;
	echo '<br>';


	$url = 'http://75.101.134.112/api/pushNotification.php?token=4af61150a072db0982335b65cb905b77d32bab06c008ce49d1854ed0331a786e&message=' . $theMessage; 
  	$ch = curl_init($url);
  	$response = curl_exec($ch);
  	curl_close($ch);

	
	// $peopleInStreamResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID' AND Phone!='$phone'");
	// while ($peopleInStreamRow = mysql_fetch_array($peopleInStreamResult))
	// {
	// 	array_push($peopleInStreamArray, $peopleInStreamRow['Phone']);
	// }

	// for ($i=0; $i < count($peopleInStreamArray); $i++)
	// {
	// 	$tempPhone = $peopleInStreamArray[$i];
	// 	$hasIPhoneResult = mysql_query("SELECT * FROM Users WHERE Phone='$tempPhone' AND Token!=''");
	// 	$hasIPhoneRow = mysql_fetch_array($hasIPhoneResult);
	// 	if(empty($hasIPhoneRow))
	// 	{
	// 		continue;
	// 	}
	// 	else
	// 	{
 //  			$url = 'http://75.101.134.112/api/pushNotification.php?token=4af61150a072db0982335b65cb905b77d32bab06c008ce49d1854ed0331a786e&message=' . $theMessage; 
 //  			$ch = curl_init($url);
 //  			$response = curl_exec($ch);
 //  			curl_close($ch);
	// 	}
	// }
}


?>