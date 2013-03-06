<?PHP

//This flags photos for innapropriate content

//input::
//	picture_id
//	flagger_phone

//output::
//	api_name
//	picture_id
//	flagger_phone 
//	status: ok || status: error & error_description: Picture not liked!

//example::
//	http://75.101.134.112/stream/1.0/api/flag_picture.php?flagger_phone=18477226071&picture_id=1532d2aefcb206383390e28214a9a326933626b6bb33ad4864b810f20299e3b6a9e99c63de11c960756479456f422f6fad695e4ee618cc64c20af15c0ad2c1ff

include('dependencies.php');

$output = array();

//grabbing the arguments 

if(empty($_GET))
{
	$picture_id = $_POST['picture_id'];
	$flagger_phone = $_POST['flagger_phone'];
	$flagger_phone = standardizePhone($flagger_phone);	
}

if(empty($_POST))
{
	$picture_id = $_GET['picture_id'];
	$flagger_phone = $_GET['flagger_phone'];
	$flagger_phone = standardizePhone($flagger_phone);	
}

//	Adds the flag to the database
$flag_result = mysql_query("INSERT INTO FlaggedPictures (PictureID, Phone) VALUES ('$picture_id', '$flagger_phone')");

if($flag_result){
	$output ["status"] = "ok";

	$picture_result = mysql_query("SELECT * FROM StreamActivity WHERE PictureID='$picture_id'");
	$picture_row = mysql_fetch_array($picture_result);
	
	$picture_url = $picture_row['PicURL'];


	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Flagger@learnToStream.com' . "\r\n";

	$subject = $flagger_phone.' flagged a picture!';

	$to = 'svenkat45@gmail.com, kazi.rousseau@gmail.com, cchanningallen@gmail.com';

	$message = '
		<html>
		<body>
		  <p>PictureID: '.$picture_id.'</p>
		  <br/>
		  <br/>
		  <p>Link to picture: '.$picture_url.'</p>
		</body>
		</html>
		';

	//sends email notification to Suman, Rousseau, and Channing
	mail($to, $subject, $message, $headers);
}
else
{
	$output ["status"] = "error";
	$output['error_description'] = "Picture not flagged! ";
}

$output['api_name'] = "flag_picture";
$output['picture_id'] = $picture_id;
$output['flagger_phone'] = $flagger_phone;


echo json_encode($output);

?>