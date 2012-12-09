<?php

$_FILES["file"]["name"] = hash('sha512', time()) . '.jpg';
$filename = $_FILES["file"]["name"];
move_uploaded_file($_FILES["file"]["tmp_name"], $filename);

header('Location:http://75.101.134.112/upload/' . $filename);

// system('sudo chmod 777' . $filename);
// $filename = "0325f58d2cf7afbbf39124d9d5a273d793f5fbb53f00a88e2c34770fe3173e7b932e26cc4d8826d5d94a6b707dae6033c355cca92cd2b88c772fb95d5ea0f26f.jpg";
// shell_exec('convert ' . $filename . ' -rotate 90' . $filename);
// exec('sudo chmod 777 ' . $filename);
// exec('convert ' . $filename . ' -rotate 90 ' . $filename);
// echo $filename;
// echo $filename;

?>