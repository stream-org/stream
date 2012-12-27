<?php

include "connection.php";


//gets number standardization function
include "formatPhoneNumbers.php";

include "sendText.php";

$phone = $_GET['phone'];
$phone = standardizePhone($phone);
$streamID = $_GET['streamID'];

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