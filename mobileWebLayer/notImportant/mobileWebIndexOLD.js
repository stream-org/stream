function checkPhoneNumber() {
	console.log(phoneNumber);
};


var inviteCounter = 1;
function addStreamer()
{
	$('#inviteBox').prepend('<input type="text" name="name" id="'+ inviteCounter +'" value="" placeholder="Add phone numbers"  />').trigger('create');
	inviteCounter++;
}


function invitePeople(aStreamId)
{
	var tempCounter = inviteCounter.toString() + inviteCounter.toString();
	console.log(tempCounter);
	var phoneNumberString = '';
	inviteCounter = inviteCounter-1; //this is because one last ++ was added at the end of addStreamer

	while (inviteCounter >= 0)
	{
		var tempNum = inviteCounter.toString() + inviteCounter.toString();
		var tempPhoneNumber = document.getElementById(tempNum).value;
		if (inviteCounter == 0)
		{
			phoneNumberString = phoneNumberString + tempPhoneNumber;
		} 
		else 
		{
			phoneNumberString = phoneNumberString + tempPhoneNumber + ',';
		}
		inviteCounter--;
	}

	console.log(phoneNumberString);

	var API_URL = 'http://75.101.134.112/api/invitePeople.php?phone=' + phoneNumberString + '&streamID=' + aStreamId;

	$.getJSON(API_URL, function (data) {
		console.log(data);
	});
}

function createStream() 
{
	var theStreamID;
	var theStreamName = document.getElementById('streamName').value;
	var API_URL = 'http://75.101.134.112/api/createStream.php?phone=' + phoneNumber + '&streamName=' + theStreamName;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {
		theStreamID = data['StreamID'];
		console.log(theStreamID);
		invitePeople(theStreamID);
	});
}



// function to populate Stream news feed (will be called by signIn and signUp)
function popStreamNF () {
	console.log ("popStreamNF() initialized...");
	phoneNumber = getCookie('phoneNumber');
	phoneNumber = getCookie('phoneNumber');
	phoneNumber = getCookie('phoneNumber');
	phoneNumber = getCookie('phoneNumber');
	phoneNumber = getCookie('phoneNumber');
	phoneNumber = getCookie('phoneNumber');
	console.log(phoneNumber);

	var API_URL = "http://75.101.134.112/api/populateStreamNewsfeed.php?phone=" + phoneNumber;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {
		// console.log(data.length);
		var streamArray = [];
		$.each(data, function(i){
			// console.log(data[i]);
			var streamID = i;
			var streamName = data[i]['streamName'];
			var numberOfParticipants = data[i]['numberOfParticipants'];
			var numberOfPictures = data[i]['numberOfPictures'];
			var latestPicture = data[i]['latestPicture'];
			var tempArray = [streamID, streamName, numberOfParticipants, numberOfPictures, latestPicture];
			streamArray.push(tempArray);
		});
		streamNewsfeed(streamArray);
	});
}

function streamNewsfeed(theStream)
{
	for (var i = 0; i < theStream.length; i++)
	{
		var streamID = theStream[i][0];
		var streamName = theStream[i][1];
		var numberOfParticipants = theStream[i][2];
		var numberOfPictures = theStream[i][3];
		var latestPicture = theStream[i][4];
		$("#streamNF").append('<li onClick="prePopStream(this.id)" id='+ streamID +'><a><img src="'+ latestPicture +'" /> <h1>'+  streamName +'</h1> <p>'+ numberOfParticipants +' participants   '+ numberOfPictures +' pictures</p></a></li>');
	}
	$('ul').listview('refresh');
}

//when you click on the <li> element it will call a certain function that will set the cookie, and then call popStreamProfile 
//when the page loads it will just go off of the .live function 

function prePopStream(theID)
{
	console.log('prePopStream initialized...');
	setCookie("latestStreamID", theID, 365);

	$.mobile.changePage( "#streamTemplate", {
		transition: "pop",
		reverse: true
	});
}

// function to populate Stream profile. will get called when a user clicks on a stream object in stream newsfeed. {{rousseau built this}}
function popStreamProfile(theID) 
{
	// var latestStreamID = theID;
	console.log('popStreamProfile initialized...');
	$('#streamTitle').html('');
	$('#numParticipants').html('');
	$('#streamPictures').html('');

	$.mobile.changePage( "#streamTemplate", {
		transition: "pop",
		reverse: true
	});

	console.log(latestStreamID);
	console.log(phoneNumber);

	var API_URL = "http://75.101.134.112/api/populateStreamProfile.php?phone=" + phoneNumber + "&streamID=" + theID;
	$.getJSON(API_URL, function (data) {
		console.log(data);
		console.log(data['pictures']);
		$('#streamTitle').html(data['streamName']);
		$('#numParticipants').html(data['numberOfParticipants'] + ' Participants');
		var lengthOfPictures = data['pictures'].length;
		var pictureIndex = 0;

		if (pictureIndex%2==1)
		{
			while (pictureIndex < lengthOfPictures-1)
			{	
				console.log(data['pictures'][pictureIndex]);
				pictureIndex++;
				if (pictureIndex%2==0)
				{
					$('#streamPictures').append('<div class="ui-block-b frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
				} 
				else 
				{
					$('#streamPictures').append('<div class="ui-block-a frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
				}

				var cw = $('.ui-grid-a img').width();
				$('.ui-grid-a img').css({'height':cw+'px'});
				
			};
		}
		else
		{
			console.log(data['pictures'][pictureIndex]);
				pictureIndex++;
				if (pictureIndex%2==0)
				{
					$('#streamPictures').append('<div class="ui-block-a frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
				} 
				else 
				{
					$('#streamPictures').append('<div class="ui-block-b frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
				}

				var cw = $('.ui-grid-a img').width();
				$('.ui-grid-a img').css({'height':cw+'px'});
		};
	});
	
}

function likePicture()
{
	var picture = document.getElementById("mainPic").getElementsByTagName("img")[0].src;
	console.log(picture);

	var API_URL = 'http://75.101.134.112/api/likePicture.php?picture=' + picture + '&phone=' + phoneNumber;
	console.log(API_URL);
	$.getJSON(API_URL, function (data) {
		$('#likeButton').html('');
		$('#likeButton').append('<a data-role="button" onClick="unlikePicture()">Unlike</a>').trigger('create');

		if (data['likes'] == '1')
		{
			$('#numberOfLikes').html('');
			$('#numberOfLikes').html(data['likes'] + ' like').trigger('create');
		}
		else
		{
			$('#numberOfLikes').html('');
			$('#numberOfLikes').html(data['likes'] + ' likes').trigger('create');
		}
	});
}

function unlikePicture() 
{
	console.log('unlikePicture function');
	var picture = document.getElementById("mainPic").getElementsByTagName("img")[0].src;
	console.log(picture);

	var API_URL = 'http://75.101.134.112/api/unlikePicture.php?picture=' + picture + '&phone=' + phoneNumber;
	console.log(API_URL);
	$.getJSON(API_URL, function (data) {
		$('#likeButton').html('');
		$('#likeButton').append('<a data-role="button" onClick="likePicture()">Like</a>').trigger('create');

		if (data['likes'] == '1')
		{
			$('#numberOfLikes').html('');
			$('#numberOfLikes').html(data['likes'] + ' like').trigger('create');
		}
		else
		{
			$('#numberOfLikes').html('');
			$('#numberOfLikes').html(data['likes'] + ' likes').trigger('create');
		}
	});
}

function preShowPicture(pictureID)
{
	console.log('preShowPicture initialized...');
	setCookie("latestPicture", pictureID, 365);
	$.mobile.changePage( "#photoView", {
		transition: "pop",
		reverse: true
	});
}



function showPicture(pictureID)
{
	$('#thePictureFrame').html('');
	$('#numberOfLikes').html('');
	var API_URL = 'http://75.101.134.112/api/getPictureMetadata.php?picture=' + pictureID + '&phone=' + phoneNumber;
	$.getJSON(API_URL, function (data) {
		
		console.log(data);
		var first = data['uploaderFirstName'];
		var last = data['uploaderLastName'];
		var fullName = first + ' ' + last;
		var phone = data['uploaderPhone'];
		var likes = data['numberOfLikes'];
		var hasLiked = data['hasLiked'];

		$('#photographersName').html(fullName).trigger('create');
		$('#numberOfLikes').html(likes + ' likes').trigger('create');

		var cw = $('#photographersName').height();
		$('#numberOfLikes').css({'height':cw+'px'});

		// $.mobile.changePage( "#photoView", {
		// 	    transition: "pop",
		// 	    reverse: true
		// 	});

		if(hasLiked == 'True')
		{
			$('#thePictureFrame').html('');
			$('#likeButton').html('');
			$('#thePictureFrame').append('<div id="mainPic"><img src="' + pictureID + '" /></div>').trigger('create'); 
			$('#likeButton').append('<a data-role="button" onClick="unlikePicture()">Unlike</a>').trigger('create');
		}
		else
		{
			$('#thePictureFrame').html('');
			$('#likeButton').html('');
			$('#thePictureFrame').append('<div id="mainPic"><img src="' + pictureID + '" /></div>').trigger('create'); 
			$('#likeButton').append('<a data-role="button" onClick="likePicture()">Like</a>').trigger('create');
		}
			});
	}

// sign in to Stream (for existing users)
function signIn () {
	console.log ("signIn() initialized...");
	
	var username = $("#signIn_username").val();
	var password = $("#signIn_password").val();
	console.log(username);
	console.log(password);

	var API_URL = "http://75.101.134.112/api/signin.php?first=" + username + "&password=" + password;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {
		// set global var "phoneNumber" equal to user's phone number - this will drive mennay APIs
		phoneNumber = data["phone"];
		setCookie("phoneNumber",phoneNumber,365);
		console.log(getCookie('phoneNumber'));
		console.log(getCookie('phoneNumber'));
		console.log(getCookie('phoneNumber'));
		console.log(getCookie('phoneNumber'));

		// transition to Stream News Feed - logic
		if( data["value"] !== "false"){
			console.log(data["value"]);
			// popStreamNF();
			$.mobile.changePage( "#streams_your", {
			    transition: "pop",
			    reverse: true
			});
		}else{
			alert("Whoops! That doesn't look like a username/password combination that we have stored.");
		};

		checkPhoneNumber();
	});
};

// sign up for Stream (for new users)
function signUp () {
	console.log ("signIn() initialized...");
	
	// store dem inputs as varbulls
	var firstName = $("#signUp_firstName").val();
	var lastName = $("#signUp_lastName").val();
	var password1 = $("#new_password1").val();
	var password2 = $("#new_password2").val();
	var password; 

	// set global var "phoneNumber" equal to user's phone number - this will drive mennay APIs
	phoneNumber = $("#signUp_phoneNumber").val();

	// make sure their password & confirmation password are the same
	function passwordCheck() {
		if (password1 === password2){
			password = password1;
		}else{
			alert("Whoops!  Look like your passwords are different.");
		}};

	passwordCheck();

	// store the API HTTP request as a variable
	var API_URL = "http://75.101.134.112/api/signup.php?first=" + firstName + "&last=" + lastName + "&phone=" + phoneNumber + "&password=" + password;
	console.log(API_URL);


	$.getJSON(API_URL, function (data) {
		console.log(data);
		console.log(data["value"]);

		if( data["value"] !== "false"){ //fix this 
			console.log(data["value"]);
			$.mobile.changePage( "#streams_your", {
			    transition: "pop",
			    reverse: true
			});
		}else{
			alert("Whoops! It looks like somthing is wrong.");
		};

		checkPhoneNumber();
	});
};

//////////cookie
function getCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}

function checkPhoneCookie{
var phoneNumber=getCookie("phoneNumber");
if (phoneNumber!=null && phoneNumber!="")
  {
  	return phoneNumber;
  }
else 
  {
  	$.mobile.changePage( "#introPage", {
			    transition: "pop",
			    reverse: true
			});
  }
}

function checkStreamIDCookie()
{
var latestStreamID = getCookie("latestStreamID");
if (latestStreamID!=null && latestStreamID!="")
  {
  	return latestStreamID;
  }
else 
  {
  	$.mobile.changePage( "#introPage", {
			    transition: "pop",
			    reverse: true
			});
  }
}

function checkPictureCookie()
{
var latestPicture = getCookie("latestPicture");
if (latestPicture!=null && latestPicture!="")
  {
  	return latestPicture;
  }
else 
  {
  	$.mobile.changePage( "#introPage", {
			    transition: "pop",
			    reverse: true
			});
  }
}

