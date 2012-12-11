<?php

$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
$filename = $_FILES["file"]["name"];
move_uploaded_file($_FILES["file"]["tmp_name"], 'Pictures/testStream/' . $filename);

// header('Location:http://75.101.134.112/upload/Pictures/testStream/' . $filename);

system('sudo chmod 777' . $filename);
$filename = "0325f58d2cf7afbbf39124d9d5a273d793f5fbb53f00a88e2c34770fe3173e7b932e26cc4d8826d5d94a6b707dae6033c355cca92cd2b88c772fb95d5ea0f26f.jpg";
shell_exec('convert ' . $filename . ' -rotate 90' . $filename);
exec('sudo chmod 777 ' . $filename);
exec('convert ' . $filename . ' -rotate 90 ' . $filename);
echo $filename;
echo $filename;


?>



// <?PHP

// require '/path/to/sdk/vendor/autoload.php';

// include 'sdk-1.5.10/sdk.class.php';

// $uploaddir = getcwd();
// $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
// $filename = $_FILES['userfile']['name'];

// if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

// 	$s3 = new AmazonS3();

// 	$response = $s3->create_object('ph-content', $filename, array('fileUpload' => $uploadfile));
// 	if($response->isOK()) { echo "file uploaded"; }

// }

// ?>



