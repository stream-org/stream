<?PHP

// This script transfers all of the files located in EC2 and transfers them to S3

include('dependencies.php');

// Instantiate the class
$s3 = new S3($AccessKey, $SecretKey);


$picture_result = mysql_query("SELECT * FROM StreamActivity");
while ($picture_row = mysql_fetch_array($picture_result))
{
	$picture = $picture_row['PicURL'];
	$tinyPicture = $picture_row['TinyPicURL'];
	$picture_id = $picture_row['PictureID'];

	echo $picture;
	echo"<br>";
	echo"<br>";
	echo $tinyPicture;
	echo"<br>";
	echo"<br>";
	echo $picture_id;

	if(substr($picture,0,5) == "http:")
	{
		$picfilename = baseName($picture);
		echo"<br>";
		echo"<br>";
		echo $picfilename;
		$tinyPicfilename = baseName($tinyPicture);
		echo"<br>";
		echo"<br>";
		echo $tinyPicfilename;
		$pictureUpload = '/var/www/html/upload/StreamPictures/Pictures/'. $picfilename;
		$tinyPictureUpload = '/var/www/html/upload/StreamPictures/TinyPictures/'. $tinyPicfilename;

		$tinyPicfilename = "tiny".$tinyPicfilename;

		echo $pictureUpload;

		chdir('../');
		chdir('../');
		chdir('../');
		chdir('upload');
		chdir('StreamPictures');
		chdir('Pictures');



		if($s3->putObjectFile($pictureUpload, "stream_pictures" , $picfilename , S3::ACL_PUBLIC_READ)){
			echo "1 works";
		}
		
		chdir('../');
		chdir('TinyPictures');

		if($s3->putObjectFile($tinyPictureUpload, "stream_tiny_pictures" , $tinyPicfilename , S3::ACL_PUBLIC_READ)){
			echo "2 works";
		}


		$pictureFilePath = 'https://s3.amazonaws.com/stream_pictures/' . $picfilename;
		$tinyPictureFilePath = 'https://s3.amazonaws.com/stream_tiny_pictures/' . $tinyPicfilename;
		
		echo"<br>";
		echo"<br>";
		echo $tinyPictureFilePath;

		$pic = mysql_query("UPDATE StreamActivity SET PicURL = '$pictureFilePath' WHERE PictureID = '$picture_id'");


		$tinypic = mysql_query("UPDATE StreamActivity SET TinyPicURL = '$tinyPictureFilePath' WHERE PictureID = '$picture_id'");
		echo"<br>";
		echo"<br>";
		echo $pic;
		echo"<br>";
		echo"<br>";
		echo $tinypic;
			
	}
	else{
		echo"<br>";
		echo "nope";
	}

	echo "yo";
}




?>