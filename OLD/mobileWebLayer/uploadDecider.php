<?php

$phone = $_GET['phoneNumber'];
$streamID = $_GET['streamID'];

$userAgent =  $_GET['userAgent'];

$browserVersion = exec("python userAgentParser.py \"" . $userAgent ."\"");

echo $browserVersion

if ($browserVersion == "iOS_False"){


	echo "To upload a photo download the iphone app or text your picture to...";

}

else{
	
	echo"<html>
			<body>
				<form action='/upload/upload_file.php' method='post' enctype='multipart/form-data'>
					<label for='file'>Filename:</label>
					<input type='file' name='file' id='file'><br>
					<input type='submit' name='submit' value='Submit'>
				</form>
			</body>
		</html>";
}


// if ($browserVersion >= 6){

// 	echo"<html>
// 			<body>
// 				<form action='/upload/upload_file.php' method='post' enctype='multipart/form-data'>
// 					<label for='file'>Filename:</label>
// 					<input type='file' name='file' id='file'><br>
// 					<input type='submit' name='submit' value='Submit'>
// 				</form>
// 			</body>
// 		</html>";

// }
// else{
// 	echo "cannot upload, please download the iphone app or text your picture to...";
// }





// $command = exec("python userAgentParser.py ";

// $command .= " $userAgent";

// $pid = popen( $command,"r");





// $phone = $_GET['phone'];

// echo
// "<html>
// <body>

// <form action="upload_file.php" method="post"
// enctype="multipart/form-data">
// <label for="file">Filename:</label>
// <input type="file" name="file" id="file"><br>
// <input type="submit" name="submit" value="Submit">
// </form>

// </body>
// </html>";

?>