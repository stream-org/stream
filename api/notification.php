<?php

include "connection.php";


//gets number standardization function
include "formatPhoneNumbers.php";

$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$streamID = $_GET['streamID'];

function sendText($phoneNumber, $textString)
{
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}

$userResult = mysql_query("SELECT * FROM UserStreams WHERE StreamID='$streamID'");
while($userRow = mysql_fetch_array($userResult))
{
	$tempPhone = $userRow['Phone'];
	if ($tempPhone === $phone)
	{
		continue;
	}
	else
	{
		$pictureResult = mysql_query("SELECT COUNT(*) FROM StreamActivity WHERE Phone!='$tempPhone' AND StreamID='$streamID'");
		while ($pictureRow = mysql_fetch_array($pictureResult))
		{
			if(intval($pictureRow[0]) % 3 === 0)
			{
				sendText($tempPhone, "Three new photos have been uploaded to your stream! bit.ly/12Dy6u5");
			}
		}
	}
}



?>