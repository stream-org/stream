<?PHP

include('connection.php');
include ('formatPhoneNumbers.php');
$phone = $_GET['phone'];

$standardizedPhone = standardizePhone($phone);

$isIn = false;

$responseArray = array();

$isInResult = mysql_query("SELECT * FROM Users WHERE Phone='$standardizedPhone'");

while($isInRow = mysql_fetch_array($isInResult))
{
	$isIn = true;
}

if ($isIn)
{
	$responseArray['phone'] = $phone;
	$responseArray['isInBeta'] = true;
	echo json_encode($responseArray);
} 
else
{
	$responseArray['phone'] = $phone;
	$responseArray['isInBeta'] = false;
	echo json_encode($responseArray);
}

?>