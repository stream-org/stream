//ADD_COMMENT

//input::
//	picture_id
//	commenter_phone
//	comment

//output::
//	picture_id
//  status
//  commenter_phone
//	api_name
//  Comments which is an array ordered chronologically that includes
//  	-commenter_first
//  	-commenter_last
//  	-commenter_phone
//  	-commenter_created
//  	-comment

function add_comment(picture_id, commenter_phone, comment)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/add_comment.php?picture_id=" + picture_id + "&commenter_phone=" + commenter_phone + "&comment=" + comment;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//CREATE_STREAM

//input:: 
//	inviter_phone
//	stream_name
//  invitees_phone

//output::
//  api_name
//	status
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

function create_stream(inviter_phone, stream_name, invitees_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/create_stream.php?inviter_phone=" + inviter_phone + "&stream_name=" + stream_name + "&invitees_phone=" + invitees_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//DELETE_FROM_STREAM

//input:: 
//	viewer_phone
//	stream_id

//output::
//	status
//  api_name
// 	viewer phone
//	stream_id

function delete_from_stream(viewer_phone, stream_id)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/delete_from_stream.php?viewer_phone=" + viewer_phone + "&stream_id=" + stream_id;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//DELETE_PICTURE

//input:: 
//	viewer_phone
//	picture_id

//output::
//	api_name
//	viewer_phone
//	picture_id

function delete_picture(viewer_phone, picture_id)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/delete_picture.php?viewer_phone=" + viewer_phone + "&picture_id=" + picture_id;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//GET_PICTURE_METADATA

//input::
//	picture_id
//	viewer_phone

//output:: 
//	picture_url
//  picture_id
//	picture_likecount
//  viewer_hasliked
//	uploader_first
//  uploader_last
//  uploader_phone
//	api_name
//  can_delete
//  Comments which is an array ordered chronologically that includes
//  	-commenter_first
//  	-commenter_last
//  	-commenter_phone
//  	-commenter_created
//  	-comment

function get_picture_metadata(picture_id, viewer_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/get_picture_metadata.php?picture_id=" + picture_id + "&viewer_phone=" +viewer_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//GET_PICTURES_UPLOADED_BY_USER

//input::
//	stream_id
//	uploader_phone
//  viewer_phone

//output::
//  status
//	stream_id
//	api_name
//	uploader_phone
//  viewer_phone
//  Pictures which is an array of all pictures a particular uploader has uploaded ordered reverse chronologically tht includes
// 		-picture_tinyurl
// 		-picture_id

function get_pictures_uploaded_by_user(stream_id, uploader_phone, viewer_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/get_pictures_uploaded_by_user.php?stream_id=" + stream_id + "&uploader_phone=" + uploader_phone + "&viewer_phone=" + viewer_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//GET_USERS_IN_STREAM

//input::
//	stream_id

//output::
//  status
//  stream_id
//	api_name
//	Users which is an array of users that are part of the stream ordered chronoligically by join date that includes
//		- uploader_first
// 		- uploader_last
//		- uploader_picturecount

function get_users_in_stream(stream_id)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/get_users_in_stream.php?stream_id=" + stream_id;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//GET_USERS_WHO_LIKE

//input::
//	picture_id

//output::
//  api_name
//  Likers which is an array of people who liked it that includes
// 		-liker_first
// 		-liker_last
// 		-liker_phone

function get_users_who_like(picture_id)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/get_users_who_like.php?picture_id=" + picture_id;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//INVITE_USERS

//input::
//	stream_id
//	invitees_phone which is either an array of invitees phone numbers or a comma-seperated list of invitee phone number
//  inviter_phone

//output::
//	api_name
//	Status message
// 	stream_id
//	invitees_phone which is an array of invitees phone numbers
//  inviter_phone

function invite_users(stream_id, invitees_phone, inviter_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/invite_users.php?stream_id=" + stream_id + "&invitees_phone=" + invitees_phone + "&inviter_phone=" + inviter_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//LIKE_PICTURE

//input::
//	picture_id
//	liker_phone

//output::
//	api_name
//	picture_id
//	liker_phone
//	picture_likecount 
//	status: ok || status: error & error_description: Picture not liked!

function like_picture(picture_id, liker_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/like_picture.php?picture_id=" + picture_id + "&liker_phone=" + liker_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//POPULATE_STREAM_PROFILE

//input:: 
//	stream_id
//  viewer_phone

//output::
//  status
//  stream_id
//  stream_name
//  stream_usercount
//  viewer_phone
//  api_name
//	Pictures which is an array of pictures ranked reverse chronologically that includes
// 		-picture_id
// 		-picture_likecount
// 		-picture_tinyurl
// 		-picture_commentcount

function populate_stream_profile(stream_id, viewer_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/populate_stream_profile.php?stream_id=" + stream_id + "&viewer_phone=" + viewer_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//POPULATE_USER_STREAMS

//input: 
// 	viewer_phone 

//output:: 
//	viewer_phone 
//  status
//  api_name
// 	Streams which is an array ordered reverse chronologically by StreamJoinDate that includes:
//		-stream_id
//		-stream_name
//		-stream_usercount 
//		-picture_count
//		-picture_latest which is an array which contains metadata on the latest picture that includes
//			-picture_tinyurl
//			-picture_id

function populate_user_streams(viewer_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/populate_user_streams.php?viewer_phone=" + viewer_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//SIGN_IN

//input::
//	viewer_phone
//	password 

//output::
// 	status
//	viewer_phone
//  api_name
//  if successful login (correct phone number and password):
//  	-viewer_first
//	    -viewer_last
// 		-viewer_profilepic

function sign_in(viewer_phone, password)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/sign_in.php?viewer_phone=" + viewer_phone + "&password=" + password;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//SIGN_UP

//input::
//	viewer_first 
//	viewer_last 
//	viewer_phone
//	password 

//output::
//	status
//	api_name
//	viewer_first 
//	viewer_last 
//	viewer_phone

function sign_up(viewer_first, viewer_last, viewer_phone, password)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/sign_up.php?viewer_first=" + viewer_first + "&viewer_last=" + viewer_last + "&viewer_phone=" + viewer_phone + "&password=" + password;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//UNLIKE_PICTURE

//input::
//	picture_id
//	liker_phone

//output::
//	status
//	api_name
//	picture_likecount
//  picture_id
//  liker_phone

function unlike_picture(picture_id, liker_phone)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/unlike_picture.php?picture_id=" + picture_id + "&liker_phone=" + liker_phone;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//UPLOAD_PICTURE

//input::
//	uploader_phone
//	picture_url 
//	stream_id
//  tinyPicture_url
//	caption

//output::
//  api_name
// 	status
//	uploader_phone
//	picture_url
//	picture_tinyurl
//	stream_id
//	caption

function upload_picture(uploader_phone, picture_url, stream_id, tinyPicture_url, caption)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/upload_picture.php?uploader_phone=" + uploader_phone + "&picture_url=" + picture_url + "&stream_id=" + stream_id + "&tinyPicture_url" + tinyPicture_url + "&caption=" + caption;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}

//VIEW_COMMENTS

//input::
//	picture_id

//output::
// 	picture_id
// 	status
//	api_name
//	Comments, which is array of comments ordered chronologically that includes for each comment:
// 		commenter_first
// 		commenter_last
// 		commenter_phone
// 		comment
// 		comment_created

function view_comments(picture_id)
{
	var API_URL = "http://75.101.134.112/stream/1.0/api/view_comments.php?picture_id=" + picture_id;
	console.log(API_URL);

	$.getJSON(API_URL, function (data) 
	{
		console.log(data);
	});
}



