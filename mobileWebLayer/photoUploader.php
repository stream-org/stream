<!DOCTYPE html> 
<html> 
<head> 
	<title>Photo Upload</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<script src="https://api.filepicker.io/v1/filepicker.js"></script>
	<script type="text/javascript">
	    //Seting up Filepicker.io with your api key
	    filepicker.setKey('AnNwHQ2cUSyyLVQwg7Xw6z');
	</script>
</head>
	
<body> 
	<!-- <h1>Photo Uploader</h1>
	<form enctype="multipart/form-data" action="uploadPhoto.php" > -->

	 <h1>Picture uploader</h1>
	 <form enctype="multipart/form-data" action="uploadPhoto.php" id="uploadForm" data-ajax="false" method="POST">
	 	<input type="hidden" name="MAX_FILE_SIZE" value="3000000000" />
	 	Select Picture/File To Upload: <input type="file" name="userfile" onchange="getFileName(this.files)" id="file" />
	 	<input type="submit" value="Upload" data-role="button" />
	 </form> 
 
	 <script>
	 	function getFileName(fileName) {
	 	//	alert(fileName[0].name);
	 	//	alert(fileName[0].size);
	 	
	 	}
	 </script>

<!-- 	</form> -->
		<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000000000"> -->
		<!-- Select Picture/File to Upload: <input type="file" name="picture" id="picture">
		<button data-theme="a" onClick="upload()">Submit</button>
	<div id="photoBucket">
	</div> -->

</body>

// <script type="text/javascript">
// 	function upload()
// {
// 	var phone = "";
// 	var picture = $("#picture").val();
// 	var streamID = "";
// 	$("#photoBucket").after("<image src='" + (picture) + "'>");
// 	// $.post("uploadPhoto.php?phone=" + phone + "&picture=" + picture + "&streamID=" + streamID);
// };
// </script>

</html>