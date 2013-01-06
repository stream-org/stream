//////////
//signin
//////////

function signIn () {

	console.log ("signIn() initialized...");
	
	var username = $("#signIn_username").val();
	var password = $("#signIn_password").val();
	var API_URL = "http://75.101.134.112/api/signin.php?first=" + username + "&password=" + password;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {

		if (data == null){
			alert('wrong username or password');
		}
		phoneNumber = data["phone"];
		setCookie("phoneNumber",phoneNumber,365);

		if( data !== null){
			$.mobile.changePage( "#streams_your", {
			    transition: "pop",
			    reverse: true
			});
		}

		else
		{
			alert("Whoops! That doesn't look like a username/password combination that we have stored.");
		};

		checkPhoneNumber();
	});
};

//////////
//signup
//////////
function signUp () {

	console.log ("signIn() initialized...");
	
	var firstName = $("#signUp_firstName").val();
	var lastName = $("#signUp_lastName").val();
	var password1 = $("#new_password1").val();
	var password2 = $("#new_password2").val();
	var password; 
	phoneNumber = $("#signUp_phoneNumber").val();

	function passwordCheck() {

		console.log ("passwordCheck() initialized...");

		if (password1 === password2)
		{
			password = password1;
		}

		else
		{
			alert("Whoops!  Look like your passwords are different.");
		}
	};

	passwordCheck();

	var API_URL = "http://75.101.134.112/api/signup.php?first=" + firstName + "&last=" + lastName + "&phone=" + phoneNumber + "&password=" + password;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {

		if( data !== null)
		{ 
			$.mobile.changePage( "#streams_your", {
			    transition: "pop",
			    reverse: true
			});
		}

		else
		{
			alert("Whoops! It looks like somthing is wrong.");
		};

		checkPhoneNumber();
	});
};

function checkPhoneNumber() {
	console.log(phoneNumber);
};

////////////////////////
//populateStreamNewsfeed
////////////////////////

function prePopStream(theID)
{
	console.log('prePopStream initialized...');

	setCookie("latestStreamID", theID, 365);

	$.mobile.changePage( "#streamTemplate", {
		transition: "pop",
		reverse: true
	});
};

function popStreamNF () {
	console.log ("popStreamNF() initialized...");

	phoneNumber = getCookie('phoneNumber');

	var API_URL = "http://75.101.134.112/api/populateStreamNewsfeed.php?phone=" + phoneNumber;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{

		var streamArray = [];

		$.each(data, function(i)
		{
			console.log(i);
			var streamID = i;
			var streamName = data[i]['streamName'];
			var numberOfParticipants = data[i]['numberOfParticipants'];
			var numberOfPictures = data[i]['numberOfPictures'];
			var latestPicture = data[i]['latestPicture'];
			var tempArray = [streamID, streamName, numberOfParticipants, numberOfPictures, latestPicture];
			streamArray.push(tempArray);
		});

		console.log(streamArray);

		streamNewsfeed(streamArray);

	});
};

function streamNewsfeed(theStream)
{
	console.log ("streamNewsfeed(theStream) initialized...");

	for (var i = 0; i < theStream.length; i++)
	{
		var streamID = theStream[i][0];
		var streamName = theStream[i][1];
		var numberOfParticipants = theStream[i][2];
		var numberOfPictures = theStream[i][3];
		var latestPicture = theStream[i][4];
		$("#streamNF").append('<li onClick="prePopStream(this.id)" id='+ streamID +'><a><img src="'+ latestPicture +'" /> <h1>'+  streamName +'</h1> <p>'+ numberOfParticipants +' participants   '+ numberOfPictures +' pictures</p></a></li>');
	}

	$('#streamNF').listview('refresh');

};

///////////////
//createStream
///////////////

function createStream() 
{
	console.log ("createStream() initialized...");

	var theStreamID;
	var theStreamName = document.getElementById('streamName').value;

	var tempCounter = inviteCounter.toString() + inviteCounter.toString();
	var phoneNumberString = '';
	inviteCounter = inviteCounter - 1;

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

	var API_URL = 'http://75.101.134.112/api/createStream.php?phone=' + phoneNumber + '&streamName=' + theStreamName + '&invitees=' + phoneNumberString;
	console.log (API_URL);

  	$.mobile.changePage( "#streams_your", {
		transition: "pop",
		reverse: true
	});
	
	$.getJSON(API_URL, function (data) 
	{
		console.log('page change');
  	});

}

//////////////
//addStreamer
//////////////

var inviteCounter = 1;

function addStreamer()
{
	var tempCounter = inviteCounter.toString() + inviteCounter.toString();
	console.log ("addStreamer() initialized...");
	$('#inviteBox').prepend('<input type="text" name="name" id="' + tempCounter + '" value="" placeholder="Add phone numbers"  />').trigger('create');
	inviteCounter++;
}

//////////////
//addPhoto
//////////////

function addPhoto() 
{
	console.log ("addPhoto() initialized...");

	var theStreamID;
	var theStreamName = document.getElementById('streamName').value;
	var API_URL = 'http://75.101.134.112/upload/uploadDecider.php?phoneNumber=' + phoneNumber + '&streamID=' + theStreamID;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});

}


///////////////
//invitePeople
///////////////

function invitePeople(aStreamId)
{
	console.log ("invitePeople() initialized...");

	var tempCounter = inviteCounter.toString() + inviteCounter.toString();
	var phoneNumberString = '';
	inviteCounter = inviteCounter - 1;

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

	var API_URL = 'http://75.101.134.112/api/invitePeople.php?phone=' + phoneNumberString + '&streamID=' + aStreamId;
	console.log (API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data); //add logic here
	});
};

////////////////////////
//populateStreamProfile
////////////////////////

function popStreamProfile(theID) 
{
	console.log('popStreamProfile initialized...');

	$('#streamTitle').html('');
	$('#numParticipants').html('');
	$('#streamPictures').html('');

	$.mobile.changePage( "#streamTemplate", {
		transition: "pop",
		reverse: true
	});

	var API_URL = "http://75.101.134.112/api/populateStreamProfile.php?phone=" + phoneNumber + "&streamID=" + theID;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		$('#streamTitle').html(data['streamName']);
		$('#numParticipants').html(data['numberOfParticipants'] + ' Participants');

		console.log(data['pictures']);

		var lengthOfPictures = data['pictures'].length;
		var pictureIndex = 0;

		while (pictureIndex < lengthOfPictures)
		{

			if (pictureIndex%2==0)
			{
				$('#streamPictures').append('<div class="ui-block-b frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
			} 
			else 
			{
				$('#streamPictures').append('<div class="ui-block-a frame"><a href=""><div class="pictureFrame"><img class="thumb" onClick="preShowPicture(this.src)" src="' + data['pictures'][pictureIndex] + '" /></div></a></div>').trigger('create');
			}
			pictureIndex++;
			var cw = $('.ui-grid-a img').width();
			$('.ui-grid-a img').css({'height':cw+'px'});
			
		}

	});
	
};



//////////////
//viewPicture
//////////////

function preShowPicture(pictureID)
{
	console.log('preShowPicture initialized...');

	setCookie("latestPicture", pictureID, 365);

	$.mobile.changePage( "#photoView", {
		transition: "pop",
		reverse: true
	});
};

function showPicture(pictureID)
{
	$('#thePictureFrame').html('');
	$('#numberOfLikes').html('');

	var API_URL = 'http://75.101.134.112/api/getPictureMetadata.php?picture=' + pictureID + '&phone=' + phoneNumber;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) {
		
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

//////////////
//likePicture
//////////////

function likePicture()
{
	console.log('likePicture() initialized...');

	var picture = document.getElementById("mainPic").getElementsByTagName("img")[0].src;
	var API_URL = 'http://75.101.134.112/api/likePicture.php?picture=' + picture + '&phone=' + phoneNumber;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
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
};

///////////////
//unlikePicture
///////////////

function unlikePicture() 
{
	console.log('unlikePicture initialized...');

	var picture = document.getElementById("mainPic").getElementsByTagName("img")[0].src;
	var API_URL = 'http://75.101.134.112/api/unlikePicture.php?picture=' + picture + '&phone=' + phoneNumber;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
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
};

//////////////////
//getPeopleWhoLike
//////////////////

function preGetPeopleWhoLike() 
{
	console.log('preGetPeopleWhoLike() initialized...');

	$.mobile.changePage( "#peopleWhoLike", {
		transition: "pop",
		reverse: true
	});

}

function getPeopleWhoLike(thePicture)
{
	console.log('getPeopleWhoLike() initialized...');

	// var picture = checkPictureCookie();
	var API_URL = 'http://75.101.134.112/api/getPeopleWhoLike.php?picture=' + thePicture;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);

		$('#likes').html('');
		for ( var j = 0; j < data.length; j++)
		{
			$('#likes').append('<li><a><h1>' + data[j] + '</h1></a></li>').trigger('create');
		}

		$('#likes').listview('refresh');
	});
}

///////////////////
//getPeopleInStream
///////////////////

function preGetPeopleInStream() 
{
	console.log('preGetPeopleInStream initialized...');

	$.mobile.changePage( "#peopleParticipating", {
		transition: "pop",
		reverse: true
	});

}

function getPeopleInStream (theStreamID) {

	console.log('getPeopleInStream initialized...');
	console.log('the streamID: ' + theStreamID);

	var API_URL = 'http://75.101.134.112/api/getPeopleInStream.php?streamID=' + theStreamID;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		$('#participants').html('');
		for ( var j = 0; j < data.length; j++)
		{
			$('#participants').append('<li><a><h1>' + data[j]['first'] + ' ' + data[j]['last'] + '</h1><p>' + data[j]['numberOfPhotos'] + ' pictures</p></a></li>').trigger('create');
		}

		$('#participants').listview('refresh');
		
	});
}

///////////
//getCookie
///////////

function getCookie(c_name)
{

console.log('getCookie(c_name) initialized...');

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
};

///////////
//setCookie
///////////

function setCookie(c_name,value,exdays)
{

console.log('setCookie(c_name, exdays) initialized...');

var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;

}

////////////////
//getPhoneCookie
////////////////

function checkPhoneCookie()
{

console.log('checkPhoneCookie() initialized...');

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
};

///////////////////
//getStreamIDCookie
///////////////////

function checkStreamIDCookie()
{

console.log('checkStreamIDCookie() initialized...');

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
};

//////////////////
//getPictureCookie
//////////////////

function checkPictureCookie()
{

console.log('checkPictureCookie() initialized...');

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
};