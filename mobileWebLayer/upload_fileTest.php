<?php

include('connection.php');

$_FILES["file"]["name"] = "test";

move_uploaded_file($_FILES["file"], 'StreamPictures/' . $filename);

?>